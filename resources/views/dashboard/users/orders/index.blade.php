@extends('layout.dash')

@section('title', 'طلباتي | NeedLink')

@section('content')
<style>
    .filter-pill {
        font-size: 0.9rem;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .filter-pill.active {
        background-color: #6366f1; /* Soft indigo */
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        border: 1px solid #6366f1;
    }
    .filter-pill.inactive {
        background-color: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }
    .filter-pill.inactive:hover {
        background-color: #f1f5f9;
        color: #334155;
    }
    .section-title {
        color: #334155;
        letter-spacing: -0.01em;
    }
    .empty-state {
        background-color: #f8fafc;
        border-radius: 20px;
        border: 1px dashed #cbd5e1;
    }
</style>

<div class="container-fluid p-4" style="max-width: 1100px;">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h3 class="mb-0 fw-bold" style="color: #0f172a;">طلباتي</h3>
        <div class="btn-group shadow-sm bg-white" role="group">
            <button type="button" class="btn btn-primary" id="btn-grid-view" onclick="toggleOrdersView('grid')"><i class="bi bi-grid me-1"></i> شبكة</button>
            <button type="button" class="btn btn-outline-primary bg-white" id="btn-compact-view" onclick="toggleOrdersView('compact')"><i class="bi bi-list me-1"></i> قائمة</button>
        </div>
    </div>

    <!-- Filters -->
    <div class="d-flex gap-2 mb-5 overflow-auto pb-2" style="scrollbar-width: none;">
        <a href="{{ route('dashboard.orders.index') }}" class="text-decoration-none px-4 py-2 rounded-pill filter-pill {{ $statusFilter === 'all' ? 'active' : 'inactive' }}">الكل</a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'in_progress']) }}" class="text-decoration-none px-4 py-2 rounded-pill filter-pill {{ $statusFilter === 'in_progress' ? 'active' : 'inactive' }}">قيد التنفيذ</a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'completed_pending_confirmation']) }}" class="text-decoration-none px-4 py-2 rounded-pill filter-pill {{ $statusFilter === 'completed_pending_confirmation' ? 'active' : 'inactive' }}">بانتظار التأكيد</a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'completed']) }}" class="text-decoration-none px-4 py-2 rounded-pill filter-pill {{ $statusFilter === 'completed' ? 'active' : 'inactive' }}">مكتملة</a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'cancelled']) }}" class="text-decoration-none px-4 py-2 rounded-pill filter-pill {{ $statusFilter === 'cancelled' ? 'active' : 'inactive' }}">ملغاة</a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'disputed']) }}" class="text-decoration-none px-4 py-2 rounded-pill filter-pill {{ $statusFilter === 'disputed' ? 'active' : 'inactive' }}">متنازع عليها</a>
    </div>

    <!-- Client Orders Section -->
    <div class="mb-5">
        <div class="d-flex align-items-center gap-3 mb-4">
            <h5 class="fw-bold mb-0 section-title">كعميل</h5>
            <span class="badge rounded-pill fw-normal" style="background-color: #f1f5f9; color: #64748b; font-size: 0.85rem;">{{ $clientOrders->count() }} طلبات</span>
        </div>
        
        @if($clientOrders->isEmpty())
            <div class="text-center p-5 empty-state">
                <i class="bi bi-inbox fs-1 mb-3 d-block" style="color: #cbd5e1;"></i>
                <p class="mb-0 text-muted" style="font-size: 0.95rem;">لم تقم بتوظيف أي شخص بعد. اقبل عرضاً على أحد طلباتك للبدء.</p>
            </div>
        @else
            <div class="orders-grid-view row row-cols-1 row-cols-lg-2 g-4">
                @foreach($clientOrders as $order)
                    @include('dashboard.users.orders.partials.order_card', ['order' => $order, 'role' => 'client'])
                @endforeach
            </div>
            
            <div class="orders-compact-view d-none">
                <div class="table-responsive bg-white rounded-4 border shadow-sm">
                    <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted">رقم الطلب / العنوان</th>
                                <th class="py-3 text-muted">مقدم الخدمة</th>
                                <th class="py-3 text-muted">السعر</th>
                                <th class="py-3 text-muted">الحالة</th>
                                <th class="pe-4 py-3 text-muted text-end">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($clientOrders as $order)
                                @php
                                    $statusColor = 'secondary';
                                    $statusText = 'قيد الانتظار';
                                    switch($order->status) {
                                        case 'in_progress': $statusColor = 'primary'; $statusText = 'قيد التنفيذ'; break;
                                        case 'completed_pending_confirmation': $statusColor = 'warning text-dark'; $statusText = 'بانتظار التأكيد'; break;
                                        case 'completed': $statusColor = 'success'; $statusText = 'مكتمل'; break;
                                        case 'cancelled': $statusColor = 'secondary'; $statusText = 'ملغى'; break;
                                        case 'disputed': $statusColor = 'danger'; $statusText = 'متنازع عليه'; break;
                                    }
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('dashboard.orders.show', $order->id) }}" class="fw-bold text-dark text-decoration-none d-block text-truncate" style="max-width: 250px;">
                                            {{ $order->serviceRequest->title }}
                                        </a>
                                        <small class="text-muted">#{{ $order->id }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 12px;">
                                                {{ mb_substr($order->provider->name, 0, 1) }}
                                            </div>
                                            <span style="font-size: 13px;">{{ $order->provider->name }}</span>
                                        </div>
                                    </td>
                                    <td><span class="fw-bold text-success">{{ $order->agreed_price }} {{ $order->currency_code }}</span></td>
                                    <td><span class="badge bg-{{ $statusColor }}">{{ $statusText }}</span></td>
                                    <td class="pe-4 text-end text-muted" style="font-size: 13px;">{{ $order->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

    <div class="mb-5 border-top" style="border-color: #e2e8f0 !important;"></div>

    <!-- Provider Orders Section -->
    <div class="mb-5">
        <div class="d-flex align-items-center gap-3 mb-4">
            <h5 class="fw-bold mb-0 section-title">كمقدم خدمة</h5>
            <span class="badge rounded-pill fw-normal" style="background-color: #f1f5f9; color: #64748b; font-size: 0.85rem;">{{ $providerOrders->count() }} طلبات</span>
        </div>

        @if($providerOrders->isEmpty())
            <div class="text-center p-5 empty-state">
                <i class="bi bi-inbox fs-1 mb-3 d-block" style="color: #cbd5e1;"></i>
                <p class="mb-0 text-muted" style="font-size: 0.95rem;">لم يتم توظيفك بعد. قدم عروضاً على الطلبات المفتوحة للبدء.</p>
            </div>
        @else
            <div class="orders-grid-view row row-cols-1 row-cols-lg-2 g-4">
                @foreach($providerOrders as $order)
                    @include('dashboard.users.orders.partials.order_card', ['order' => $order, 'role' => 'provider'])
                @endforeach
            </div>
            
            <div class="orders-compact-view d-none">
                <div class="table-responsive bg-white rounded-4 border shadow-sm">
                    <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted">رقم الطلب / العنوان</th>
                                <th class="py-3 text-muted">العميل</th>
                                <th class="py-3 text-muted">السعر</th>
                                <th class="py-3 text-muted">الحالة</th>
                                <th class="pe-4 py-3 text-muted text-end">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($providerOrders as $order)
                                @php
                                    $statusColor = 'secondary';
                                    $statusText = 'قيد الانتظار';
                                    switch($order->status) {
                                        case 'in_progress': $statusColor = 'primary'; $statusText = 'قيد التنفيذ'; break;
                                        case 'completed_pending_confirmation': $statusColor = 'warning text-dark'; $statusText = 'بانتظار التأكيد'; break;
                                        case 'completed': $statusColor = 'success'; $statusText = 'مكتمل'; break;
                                        case 'cancelled': $statusColor = 'secondary'; $statusText = 'ملغى'; break;
                                        case 'disputed': $statusColor = 'danger'; $statusText = 'متنازع عليه'; break;
                                    }
                                @endphp
                                <tr>
                                    <td class="ps-4">
                                        <a href="{{ route('dashboard.orders.show', $order->id) }}" class="fw-bold text-dark text-decoration-none d-block text-truncate" style="max-width: 250px;">
                                            {{ $order->serviceRequest->title }}
                                        </a>
                                        <small class="text-muted">#{{ $order->id }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 12px;">
                                                {{ mb_substr($order->client->name, 0, 1) }}
                                            </div>
                                            <span style="font-size: 13px;">{{ $order->client->name }}</span>
                                        </div>
                                    </td>
                                    <td><span class="fw-bold text-success">{{ $order->agreed_price }} {{ $order->currency_code }}</span></td>
                                    <td><span class="badge bg-{{ $statusColor }}">{{ $statusText }}</span></td>
                                    <td class="pe-4 text-end text-muted" style="font-size: 13px;">{{ $order->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Restore view preference
        const savedView = localStorage.getItem('ordersViewPreference');
        if (savedView === 'compact') {
            toggleOrdersView('compact');
        }
    });

    function toggleOrdersView(view) {
        localStorage.setItem('ordersViewPreference', view);
        
        const gridViews = document.querySelectorAll('.orders-grid-view');
        const compactViews = document.querySelectorAll('.orders-compact-view');
        const btnGrid = document.getElementById('btn-grid-view');
        const btnCompact = document.getElementById('btn-compact-view');

        if (view === 'compact') {
            gridViews.forEach(el => el.classList.add('d-none'));
            compactViews.forEach(el => el.classList.remove('d-none'));
            
            btnCompact.classList.add('btn-primary');
            btnCompact.classList.remove('btn-outline-primary', 'bg-white');
            
            btnGrid.classList.remove('btn-primary');
            btnGrid.classList.add('btn-outline-primary', 'bg-white');
        } else {
            compactViews.forEach(el => el.classList.add('d-none'));
            gridViews.forEach(el => el.classList.remove('d-none'));
            
            btnGrid.classList.add('btn-primary');
            btnGrid.classList.remove('btn-outline-primary', 'bg-white');
            
            btnCompact.classList.remove('btn-primary');
            btnCompact.classList.add('btn-outline-primary', 'bg-white');
        }
    }
</script>
@endsection
