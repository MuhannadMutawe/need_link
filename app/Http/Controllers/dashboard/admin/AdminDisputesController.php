<?php

namespace App\Http\Controllers\dashboard\admin;

use App\Http\Controllers\Controller;
use App\Models\OrderDispute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDisputesController extends Controller
{
    public function index()
    {
        $disputes = OrderDispute::with([
            'order.client',
            'order.provider',
            'order.serviceRequest',
            'opener',
            'resolver',
        ])->latest()->paginate(20);

        $openCount     = OrderDispute::where('status', 'open')->count();
        $resolvedCount = OrderDispute::where('status', 'resolved')->count();

        return view('dashboard.admin.disputes', compact('disputes', 'openCount', 'resolvedCount'));
    }

    public function resolve(Request $request, OrderDispute $dispute)
    {
        abort_if($dispute->status !== 'open', 400, 'Already resolved');

        $validated = $request->validate(['resolution_note' => 'required|string|min:10']);

        DB::transaction(function () use ($dispute, $validated) {
            $dispute->update([
                'status'          => 'resolved',
                'resolved_by'     => auth()->id(),
                'resolved_at'     => now(),
                'resolution_note' => $validated['resolution_note'],
            ]);

            // Mark the order as cancelled when resolved
            $dispute->order->update(['status' => 'cancelled']);
        });

        return redirect()->back()->with('success', 'تم حل النزاع وإغلاق الطلب بنجاح.');
    }
}
