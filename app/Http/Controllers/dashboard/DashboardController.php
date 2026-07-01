<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Order;
use App\Models\OrderCancellationRequest;
use App\Models\OrderCompletionRequest;
use App\Models\OrderDispute;

class DashboardController extends Controller
{
    private function authUser()
    {
        return auth()->user() ?? \App\Models\User::first();
    }

    public function index()
    {
        $user = $this->authUser();
        $userId = $user->id;

        // ── 1. Completion requests awaiting your response ──
        $pendingCompletions = OrderCompletionRequest::where('status', 'pending')
            ->where('requested_by', '!=', $userId)
            ->whereHas('order', function ($q) use ($userId) {
                $q->where('client_id', $userId)
                  ->orWhere('provider_id', $userId);
            })
            ->with(['order.serviceRequest', 'requester'])
            ->latest()
            ->get();

        // ── 2. Cancellation requests awaiting your response ──
        $pendingCancellations = OrderCancellationRequest::where('status', 'pending')
            ->where('requested_by', '!=', $userId)
            ->whereHas('order', function ($q) use ($userId) {
                $q->where('client_id', $userId)
                  ->orWhere('provider_id', $userId);
            })
            ->with(['order.serviceRequest', 'requester'])
            ->latest()
            ->get();

        // ── 3. Disputes awaiting your counter-reason ──
        $pendingDisputes = OrderDispute::where('status', 'open')
            ->where('opened_by', '!=', $userId)
            ->whereNull('counter_reason')
            ->whereHas('order', function ($q) use ($userId) {
                $q->where('client_id', $userId)
                  ->orWhere('provider_id', $userId);
            })
            ->with(['order.serviceRequest', 'opener'])
            ->latest()
            ->get();

        // ── 4. Deliveries to review (client side) ──
        $deliveriesToReview = Order::where('status', 'completed_pending_confirmation')
            ->where('client_id', $userId)
            ->with(['serviceRequest', 'provider'])
            ->latest()
            ->get();

        // ── 5. New offers on your requests ──
        $newOffers = Offer::where('status', 'pending')
            ->whereHas('serviceRequest', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with(['serviceRequest', 'user'])
            ->latest()
            ->get();

        // Total count for badge
        $totalActions = $pendingCompletions->count()
            + $pendingCancellations->count()
            + $pendingDisputes->count()
            + $deliveriesToReview->count()
            + $newOffers->count();

        return view('dashboard.users.main', compact(
            'user',
            'pendingCompletions',
            'pendingCancellations',
            'pendingDisputes',
            'deliveriesToReview',
            'newOffers',
            'totalActions',
        ));
    }
}
