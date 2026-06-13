<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDispute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrdersController extends Controller
{
    public function disputes()
    {
        // Admin views all disputed orders
        $disputes = OrderDispute::with(['order.client', 'order.provider', 'opener', 'resolver'])
            ->orderBy('status', 'asc') // 'open' before 'resolved'
            ->orderBy('created_at', 'desc')
            ->get();

        // Pass to an admin view (stubbed for now or create a basic one)
        return view('dashboard.admin.disputes.index', compact('disputes'));
    }

    public function resolveDispute(Request $request, OrderDispute $dispute)
    {
        $admin = auth()->user() ?? \App\Models\User::first(); // used only for testing

        if ($dispute->status !== 'open') {
            return response()->json(['error' => 'Dispute is already resolved'], 400);
        }

        $validated = $request->validate([
            'resolution_note' => 'required|string',
            'ruling' => 'required|in:client,provider'
        ]);

        DB::transaction(function () use ($dispute, $admin, $validated) {
            $dispute->update([
                'status' => 'resolved',
                'resolved_by' => $admin->id,
                'resolved_at' => now(),
                'resolution_note' => $validated['resolution_note']
            ]);

            $order = $dispute->order;

            if ($validated['ruling'] === 'provider') {
                // Rule in provider's favour
                $order->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'closed_by' => $admin->id,
                ]);
                $order->serviceRequest->update(['status' => 'completed']);
            } else {
                // Rule in client's favour
                $order->update([
                    'status' => 'cancelled',
                    'cancelled_by' => $admin->id,
                    'cancellation_reason' => 'Dispute resolved in favour of client. ' . $validated['resolution_note'],
                ]);
                $order->serviceRequest->update(['status' => 'open']);
            }
        });

        return redirect()->back()->with('success', 'Dispute resolved successfully');
    }
}
