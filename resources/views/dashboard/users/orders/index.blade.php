@extends('layout.dash')

@section('title', 'طلباتي | NeedLink')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold">طلباتي</h4>
    </div>

    <!-- Filters -->
    <div class="d-flex gap-2 mb-4 overflow-auto pb-2">
        <a href="{{ route('dashboard.orders.index') }}" class="btn btn-sm rounded-pill {{ $statusFilter === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">الكل</a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'in_progress']) }}" class="btn btn-sm rounded-pill {{ $statusFilter === 'in_progress' ? 'btn-primary' : 'btn-outline-secondary' }}">قيد التنفيذ</a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'completed_pending_confirmation']) }}" class="btn btn-sm rounded-pill {{ $statusFilter === 'completed_pending_confirmation' ? 'btn-primary' : 'btn-outline-secondary' }}">بانتظار التأكيد</a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'completed']) }}" class="btn btn-sm rounded-pill {{ $statusFilter === 'completed' ? 'btn-primary' : 'btn-outline-secondary' }}">مكتملة</a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'cancelled']) }}" class="btn btn-sm rounded-pill {{ $statusFilter === 'cancelled' ? 'btn-primary' : 'btn-outline-secondary' }}">ملغاة</a>
        <a href="{{ route('dashboard.orders.index', ['status' => 'disputed']) }}" class="btn btn-sm rounded-pill {{ $statusFilter === 'disputed' ? 'btn-primary' : 'btn-outline-secondary' }}">متنازع عليها</a>
    </div>

    <!-- Client Orders Section -->
    <div class="mb-5">
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-person-fill text-primary"></i> كعميل <span class="badge bg-secondary rounded-pill fs-6">{{ $clientOrders->count() }}</span>
        </h5>
        
        @if($clientOrders->isEmpty())
            <div class="text-center p-5 text-muted bg-white rounded border shadow-sm">
                <i class="bi bi-inbox fs-1 mb-3 d-block text-secondary"></i>
                <p class="mb-0">لم تقم بتوظيف أي شخص بعد. اقبل عرضاً على أحد طلباتك للبدء.</p>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach($clientOrders as $order)
                    @include('dashboard.users.orders.partials.order_card', ['order' => $order, 'role' => 'client'])
                @endforeach
            </div>
        @endif
    </div>

    <hr class="my-5">

    <!-- Provider Orders Section -->
    <div>
        <h5 class="fw-bold mb-3 d-flex align-items-center gap-2">
            <i class="bi bi-briefcase-fill text-primary"></i> كمقدم خدمة <span class="badge bg-secondary rounded-pill fs-6">{{ $providerOrders->count() }}</span>
        </h5>

        @if($providerOrders->isEmpty())
            <div class="text-center p-5 text-muted bg-white rounded border shadow-sm">
                <i class="bi bi-inbox fs-1 mb-3 d-block text-secondary"></i>
                <p class="mb-0">لم يتم توظيفك بعد. قدم عروضاً على الطلبات المفتوحة للبدء.</p>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 g-4">
                @foreach($providerOrders as $order)
                    @include('dashboard.users.orders.partials.order_card', ['order' => $order, 'role' => 'provider'])
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
