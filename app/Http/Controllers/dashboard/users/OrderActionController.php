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
        abort_if(!in_array($order->status, ['in_progress', 'completed_pending_confirmation']), 400, 'Invalid state');

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
        });

        return redirect()->back()->with('success', 'تم إرسال التسليم بنجاح. في انتظار تأكيد العميل.');
    }

    /**
     * EITHER PARTY: Request completion
     */
    public function requestCompletion(Request $request, Order $order)
    {
        $user = $this->authUser();

        abort_if($order->client_id !== $user->id && $order->provider_id !== $user->id, 403);
        abort_if(!in_array($order->status, ['in_progress', 'completed_pending_confirmation']), 400, 'Invalid state');
        abort_if(
            $order->completionRequests()->where('status', 'pending')->exists(),
            400,
            'Pending completion request already exists'
        );

        \App\Models\OrderCompletionRequest::create([
            'order_id'     => $order->id,
            'requested_by' => $user->id,
            'status'       => 'pending',
        ]);

        return redirect()->back()->with('success', 'تم إرسال طلب تأكيد الإنجاز. في انتظار موافقة الطرف الآخر.');
    }

    /**
     * OTHER PARTY: Accept or reject a completion request
     */
    public function respondCompletion(Request $request, Order $order, \App\Models\OrderCompletionRequest $completionRequest)
    {
        $user = $this->authUser();

        abort_if($order->client_id !== $user->id && $order->provider_id !== $user->id, 403);
        abort_if($completionRequest->requested_by === $user->id, 403, 'Cannot respond to your own request');
        abort_if($completionRequest->status !== 'pending', 400, 'Already handled');

        $validated = $request->validate(['action' => 'required|in:accept,reject']);

        DB::transaction(function () use ($order, $completionRequest, $user, $validated) {
            if ($validated['action'] === 'accept') {
                $completionRequest->update([
                    'status'       => 'agreed',
                    'responded_by' => $user->id,
                    'responded_at' => now(),
                ]);
                $order->update([
                    'status'              => 'completed',
                    'completed_at'        => now(),
                    'confirm_deadline_at' => null,
                ]);
                $order->serviceRequest->update(['status' => 'completed']);
            } else {
                $completionRequest->update([
                    'status'       => 'rejected',
                    'responded_by' => $user->id,
                    'responded_at' => now(),
                ]);
            }
        });

        return redirect()->back()->with(
            'success',
            $validated['action'] === 'accept' ? 'تم الموافقة على طلب الإنجاز واكتمال الطلب بنجاح.' : 'تم رفض طلب الإنجاز. الطلب لا يزال مستمراً.'
        );
    }

    /**
     * CLIENT: Request revision (service order, pending_confirmation, revision_count < cap)
     * → sets status back to in_progress
     */
    public function requestRevision(Request $request, Order $order)
    {
        $user = $this->authUser();

        abort_if($order->client_id !== $user->id, 403);
        abort_if(!in_array($order->status, ['in_progress', 'completed_pending_confirmation']), 400, 'Invalid state');
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
