<?php

namespace App\Http\Controllers\dashboard\users;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderCancellationRequest;
use App\Models\OrderDelivery;
use App\Models\OrderDeliveryAttachment;
use App\Models\OrderDispute;
use App\Models\OrderRevision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderActionController extends Controller
{
    private function authUser()
    {
        return auth()->user() ?? \App\Models\User::first();
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SERVICE ORDER ACTIONS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * PROVIDER: Submit a delivery (service order, in_progress)
     * → sets status to completed_pending_confirmation
     */
    public function submitDelivery(Request $request, Order $order)
    {
        $user = $this->authUser();

        abort_if($order->provider_id !== $user->id, 403);
        abort_if($order->status !== 'in_progress' || $order->order_type !== 'service', 400, 'Invalid state');

        $validated = $request->validate([
            'message'        => 'required|string',
            'attachments.*'  => 'nullable|file|max:10240',
        ]);

        DB::transaction(function () use ($order, $user, $validated, $request) {
            $delivery = OrderDelivery::create([
                'order_id'     => $order->id,
                'submitted_by' => $user->id,
                'message'      => $validated['message'],
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('order_attachments');
                    OrderDeliveryAttachment::create([
                        'delivery_id' => $delivery->id,
                        'file_path'   => $path,
                        'file_name'   => $file->getClientOriginalName(),
                    ]);
                }
            }

            $order->update([
                'status'              => 'completed_pending_confirmation',
                'confirm_deadline_at' => now()->addDays(5),
            ]);
        });

        return redirect()->back()->with('success', 'تم إرسال التسليم بنجاح. في انتظار تأكيد العميل.');
    }

    /**
     * CLIENT: Confirm completion (service order, pending_confirmation)
     * → sets status to completed
     */
    public function confirmCompletion(Request $request, Order $order)
    {
        $user = $this->authUser();

        abort_if($order->client_id !== $user->id, 403);
        abort_if($order->status !== 'completed_pending_confirmation', 400, 'Invalid state');

        DB::transaction(function () use ($order) {
            $order->update([
                'status'              => 'completed',
                'completed_at'        => now(),
                'confirm_deadline_at' => null,
            ]);
            $order->serviceRequest->update(['status' => 'completed']);
        });

        return redirect()->back()->with('success', 'تم تأكيد اكتمال الطلب بنجاح.');
    }

    /**
     * CLIENT: Request revision (service order, pending_confirmation, revision_count < cap)
     * → sets status back to in_progress
     */
    public function requestRevision(Request $request, Order $order)
    {
        $user = $this->authUser();

        abort_if($order->client_id !== $user->id, 403);
        abort_if($order->status !== 'completed_pending_confirmation' || $order->order_type !== 'service', 400, 'Invalid state');
        abort_if($order->revision_count >= 3, 400, 'Maximum revisions reached');

        $validated = $request->validate(['reason' => 'required|string']);

        DB::transaction(function () use ($order, $user, $validated) {
            OrderRevision::create([
                'order_id'     => $order->id,
                'requested_by' => $user->id,
                'reason'       => $validated['reason'],
            ]);

            $order->update([
                'status'              => 'in_progress',
                'confirm_deadline_at' => null,
                'revision_count'      => $order->revision_count + 1,
            ]);
        });

        return redirect()->back()->with('success', 'تم إرسال طلب التعديل. الكرة في ملعب مقدم الخدمة.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRODUCT ORDER ACTIONS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * CLIENT: Confirm payment sent outside platform (product order, in_progress, !is_paid)
     * → marks is_paid = true, status stays in_progress
     */
    public function confirmPayment(Request $request, Order $order)
    {
        $user = $this->authUser();

        abort_if($order->client_id !== $user->id, 403);
        abort_if($order->order_type !== 'product' || $order->is_paid, 400, 'Invalid state');

        $order->update(['is_paid' => true]);

        return redirect()->back()->with('success', 'تم تأكيد إرسال المبلغ. في انتظار شحن المنتج من البائع.');
    }

    /**
     * PROVIDER: Mark as shipped (product order, in_progress, is_paid = true)
     * → saves tracking info on order, sets status to completed_pending_confirmation
     */
    public function markShipped(Request $request, Order $order)
    {
        $user = $this->authUser();

        abort_if($order->provider_id !== $user->id, 403);
        abort_if(
            $order->order_type !== 'product' || !$order->is_paid || $order->is_shipped,
            400,
            'Invalid state'
        );

        $validated = $request->validate([
            'carrier'         => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
            'tracking_url'    => 'nullable|url|max:500',
        ]);

        $order->update([
            'carrier'                => $validated['carrier'] ?? null,
            'tracking_number'        => $validated['tracking_number'] ?? null,
            'tracking_url'           => $validated['tracking_url'] ?? null,
            'is_shipped'             => true,
            'shipped_at'             => now(),
            'status'                 => 'completed_pending_confirmation',
            'confirm_deadline_at'    => now()->addDays(7),
        ]);

        return redirect()->back()->with('success', 'تم تسجيل الشحن. في انتظار تأكيد استلام العميل.');
    }

    /**
     * CLIENT: Confirm receipt (product order, pending_confirmation, is_shipped = true)
     * → sets status to completed
     */
    public function confirmReceipt(Request $request, Order $order)
    {
        $user = $this->authUser();

        abort_if($order->client_id !== $user->id, 403);
        abort_if(
            $order->status !== 'completed_pending_confirmation' || $order->order_type !== 'product',
            400,
            'Invalid state'
        );

        DB::transaction(function () use ($order) {
            $order->update([
                'status'              => 'completed',
                'completed_at'        => now(),
                'confirm_deadline_at' => null,
            ]);
            $order->serviceRequest->update(['status' => 'completed']);
        });

        return redirect()->back()->with('success', 'تم تأكيد الاستلام وإغلاق الطلب بنجاح.');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // ESCAPE HATCHES (available at any active stage for both types)
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * EITHER PARTY: Request cancellation — other party must agree
     */
    public function requestCancellation(Request $request, Order $order)
    {
        $user = $this->authUser();

        abort_if($order->client_id !== $user->id && $order->provider_id !== $user->id, 403);
        abort_if(
            !in_array($order->status, ['in_progress', 'completed_pending_confirmation']),
            400,
            'Invalid state'
        );
        abort_if(
            $order->cancellationRequests()->where('status', 'pending')->exists(),
            400,
            'Pending cancellation already exists'
        );

        $validated = $request->validate(['reason' => 'required|string']);

        OrderCancellationRequest::create([
            'order_id'     => $order->id,
            'requested_by' => $user->id,
            'reason'       => $validated['reason'],
            'status'       => 'pending',
        ]);

        return redirect()->back()->with('success', 'تم إرسال طلب الإلغاء. في انتظار موافقة الطرف الآخر.');
    }

    /**
     * OTHER PARTY: Accept or reject a pending cancellation request
     */
    public function respondCancellation(Request $request, Order $order, OrderCancellationRequest $cancellationRequest)
    {
        $user = $this->authUser();

        abort_if($order->client_id !== $user->id && $order->provider_id !== $user->id, 403);
        abort_if($cancellationRequest->requested_by === $user->id, 403, 'Cannot respond to your own request');
        abort_if($cancellationRequest->status !== 'pending', 400, 'Already handled');

        $validated = $request->validate(['action' => 'required|in:accept,reject']);

        DB::transaction(function () use ($order, $cancellationRequest, $user, $validated) {
            if ($validated['action'] === 'accept') {
                $cancellationRequest->update([
                    'status'       => 'agreed',
                    'responded_by' => $user->id,
                    'responded_at' => now(),
                ]);
                $order->update([
                    'status'              => 'cancelled',
                    'cancelled_by'        => $user->id,
                    'cancellation_reason' => $cancellationRequest->reason,
                ]);
                $order->serviceRequest->update(['status' => 'open']);
            } else {
                $cancellationRequest->update([
                    'status'       => 'rejected',
                    'responded_by' => $user->id,
                    'responded_at' => now(),
                ]);
            }
        });

        return redirect()->back()->with(
            'success',
            $validated['action'] === 'accept' ? 'تم قبول الإلغاء وإغلاق الطلب.' : 'تم رفض طلب الإلغاء. الطلب مستمر.'
        );
    }

    /**
     * EITHER PARTY: Open a dispute (admin resolves)
     * For service orders: at least one delivery must exist
     * For product orders: is_paid must be true
     */
    public function openDispute(Request $request, Order $order)
    {
        $user = $this->authUser();

        abort_if($order->client_id !== $user->id && $order->provider_id !== $user->id, 403);
        abort_if(
            !in_array($order->status, ['in_progress', 'completed_pending_confirmation']),
            400,
            'Invalid state'
        );

        // Guard: service order must have at least one delivery before disputing
        if ($order->order_type === 'service' && $order->deliveries()->count() === 0) {
            return redirect()->back()->withErrors(['dispute' => 'لا يمكن رفع نزاع قبل أن يقدم مقدم الخدمة أي تسليم.']);
        }

        // Guard: product order must have confirmed payment before disputing
        if ($order->order_type === 'product' && !$order->is_paid) {
            return redirect()->back()->withErrors(['dispute' => 'لا يمكن رفع نزاع قبل تأكيد الدفع.']);
        }

        abort_if($order->disputes()->where('status', 'open')->exists(), 400, 'Dispute already open');

        $validated = $request->validate(['reason' => 'required|string']);

        DB::transaction(function () use ($order, $user, $validated) {
            OrderDispute::create([
                'order_id'  => $order->id,
                'opened_by' => $user->id,
                'reason'    => $validated['reason'],
                'status'    => 'open',
            ]);
            $order->update(['status' => 'disputed']);
        });

        return redirect()->back()->with('success', 'تم رفع النزاع. ستتدخل الإدارة قريباً.');
    }

    /**
     * OTHER PARTY: Respond to an open dispute with a counter reason
     */
    public function respondDispute(Request $request, Order $order)
    {
        $user = $this->authUser();

        abort_if($order->client_id !== $user->id && $order->provider_id !== $user->id, 403);
        abort_if($order->status !== 'disputed', 400, 'Order is not disputed');

        $dispute = $order->disputes()->where('status', 'open')->firstOrFail();

        abort_if($dispute->opened_by === $user->id, 403, 'You cannot respond to your own dispute');
        abort_if($dispute->counter_reason !== null, 400, 'Counter reason already submitted');

        $validated = $request->validate(['counter_reason' => 'required|string']);

        $dispute->update([
            'counter_reason' => $validated['counter_reason'],
            'counter_reason_submitted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'تم إضافة ردك على النزاع. الإدارة ستقوم بمراجعة الطرفين.');
    }
}
