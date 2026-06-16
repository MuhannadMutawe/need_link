<div class="col">
    <div class="card h-100 border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-end align-items-start mb-3">
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

                    $hasActionRequired = false;
                    if ($role === 'client') {
                        if ($order->status === 'completed_pending_confirmation') $hasActionRequired = true;
                        if ($order->status === 'in_progress' && $order->order_type === 'product' && !$order->is_paid) $hasActionRequired = true;
                    } else {
                        if ($order->status === 'in_progress' && $order->order_type === 'service') $hasActionRequired = true;
                        if ($order->status === 'in_progress' && $order->order_type === 'product' && $order->is_paid && !$order->is_shipped) $hasActionRequired = true;
                    }
                    if ($order->cancellationRequests()->where('status', 'pending')->where('requested_by', '!=', auth()->id() ?? 1)->exists()) {
                        $hasActionRequired = true;
                    }
                @endphp

                <div class="d-flex flex-column align-items-end gap-1">
                    <span class="badge bg-{{ $statusColor }}">{{ $statusText }}</span>
                    @if($hasActionRequired)
                        <span class="badge rounded-pill bg-danger"><i class="bi bi-exclamation-circle me-1"></i> إجراء مطلوب</span>
                    @endif
                </div>
            </div>

            <h5 class="card-title fw-bold mb-3 text-truncate" title="{{ $order->serviceRequest->title }}">
                <a href="{{ route('dashboard.orders.show', $order->id) }}" class="text-decoration-none text-dark">
                    {{ $order->serviceRequest->title }}
                </a>
            </h5>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    @php
                        $otherParty = $role === 'client' ? $order->provider : $order->client;
                    @endphp
                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 14px;">
                        {{ mb_substr($otherParty->name, 0, 1) }}
                    </div>
                    <div>
                        <small class="text-muted d-block" style="font-size: 11px;">{{ $role === 'client' ? 'مقدم الخدمة' : 'العميل' }}</small>
                        <span class="fw-bold" style="font-size: 13px;">{{ $otherParty->name }}</span>
                    </div>
                </div>
                
                <div class="text-end">
                    <small class="text-muted d-block" style="font-size: 11px;">السعر المتفق عليه</small>
                    <span class="fw-bold text-success">{{ $order->agreed_price }} {{ $order->currency_code }}</span>
                </div>
            </div>

        </div>
        <div class="card-footer bg-white border-top-0 pt-0">
            <div class="text-muted" style="font-size: 12px;">
                <span><i class="bi bi-clock me-1"></i> {{ $order->created_at->format('Y-m-d') }}</span>
            </div>
        </div>
    </div>
</div>
