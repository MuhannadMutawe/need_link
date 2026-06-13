<?php

namespace App\Http\Controllers\dashboard\users;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user() ?? \App\Models\User::first();
        
        $statusFilter = $request->query('status', 'all');
        
        $clientOrdersQuery = Order::with(['provider', 'serviceRequest'])
            ->where('client_id', $user->id);
            
        $providerOrdersQuery = Order::with(['client', 'serviceRequest'])
            ->where('provider_id', $user->id);

        if ($statusFilter !== 'all') {
            $clientOrdersQuery->where('status', $statusFilter);
            $providerOrdersQuery->where('status', $statusFilter);
        }

        $clientOrders = $clientOrdersQuery->orderBy('created_at', 'desc')->get();
        $providerOrders = $providerOrdersQuery->orderBy('created_at', 'desc')->get();

        return view('dashboard.users.orders.index', compact('clientOrders', 'providerOrders', 'statusFilter', 'user'));
    }

    public function show(Order $order)
    {
        $user = auth()->user() ?? \App\Models\User::first();

        if ($order->client_id !== $user->id && $order->provider_id !== $user->id) {
            abort(403, 'غير مصرح لك بمشاهدة هذا الطلب');
        }

        $order->load([
            'client', 
            'provider', 
            'serviceRequest', 
            'deliveries.attachments', 
            'revisions', 
            'cancellationRequests', 
            'disputes'
        ]);

        return view('dashboard.users.orders.show', compact('order', 'user'));
    }
}
