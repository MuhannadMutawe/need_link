@extends('layout.master')

@section('title', 'NeedLink - تصفح الطلبات')

@section('content')
<style>
    .request-card-wrapper {
        border-radius: 16px;
        border: 1px solid #e0e6ed;
        overflow: hidden;
        transition: all 0.2s ease;
        position: relative;
        background-color: #fff;
        cursor: pointer;
        max-width: 380px;
        margin-left: auto;
        margin-right: auto;
    }
    .request-card-wrapper:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }
</style>

<div class="container py-5">
    
    <div class="text-center mb-5">
        <h2 class="fw-bold"><i class="bi bi-briefcase-fill text-primary me-2"></i>الطلبات المتاحة</h2>
        <p class="text-muted">تصفح أحدث طلبات الخدمات وقدم عروضك للعملاء</p>
    </div>
    
    @if(isset($requests) && $requests->count() > 0)
        <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-4" id="requestsGrid">
            @foreach($requests as $req)
                <div class="col request-grid-item">
                    <div class="card h-100 shadow-sm request-card-wrapper"
                         data-href="{{ route('dashboard.requests.show', $req->id) }}">
                        
                        {{-- Title bar --}}
                        <div class="card-body px-3 pt-3 pb-2" style="border-bottom: 1px solid #f1f5f9;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div style="flex: 1; min-width: 0;">
                                    <h6 class="mb-1 text-dark fw-bold text-truncate" style="font-size: 1.1rem;" dir="auto">
                                        {{ $req->title }}
                                    </h6>
                                    <div class="text-muted d-flex justify-content-end align-items-center gap-1" style="font-size: 0.78rem;">
                                        <span>{{ $req->created_at->translatedFormat('d M Y') }}</span>
                                        <i class="bi bi-calendar3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Image Area --}}
                        <div class="position-relative">
                            {{-- Use request image if exists, fallback to placeholder --}}
                            <img src="{{ $req->image ? (filter_var($req->image, FILTER_VALIDATE_URL) ? $req->image : Storage::url($req->image)) : 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=600&auto=format&fit=crop' }}" 
                                 alt="{{ $req->title }}" 
                                 style="width: 100%; height: 200px; object-fit: cover;">
                            
                            {{-- Status Badge --}}
                            @php
                                $statusBg = 'bg-secondary text-white';
                                $statusText = $req->status;
                                if($req->status == 'open') { $statusBg = 'bg-success text-white'; $statusText = 'متاح للتقديم'; }
                                elseif($req->status == 'draft') { $statusBg = 'bg-light text-dark'; $statusText = 'مسودة'; }
                                elseif($req->status == 'assigned') { $statusBg = 'bg-warning text-dark'; $statusText = 'قيد المراجعة'; }
                            @endphp
                            <span class="badge {{ $statusBg }} position-absolute shadow-sm" 
                                  style="top: 10px; right: 10px; border-radius: 20px; padding: 6px 16px; font-weight: 600; font-size: 0.8rem; z-index: 2;">
                                {{ $statusText }}
                            </span>
                        </div>

                        {{-- Categories --}}
                        <div class="card-body p-3 text-end" style="background-color: #fff; position: relative; z-index: 2;">
                            <div class="d-flex flex-wrap justify-content-end gap-2">
                                @foreach($req->categories->take(2) as $cat)
                                    <span class="badge" style="background: linear-gradient(135deg, #6366f1, #4f46e5); color: #fff; border-radius: 8px; padding: 6px 14px; font-weight: 600; font-size: 0.85rem; box-shadow: 0 2px 4px rgba(79, 70, 229, 0.2);">
                                        <i class="bi bi-tag-fill ms-1" style="font-size: 0.75rem; opacity: 0.8;"></i>{{ $cat->name }}
                                    </span>
                                @endforeach
                                @if($req->categories->count() > 2)
                                    <span class="badge bg-light text-secondary border" style="border-radius: 8px; padding: 6px 12px; font-weight: 600; font-size: 0.85rem;">
                                        +{{ $req->categories->count() - 2 }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Card Footer --}}
                        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center px-3 py-2" style="border-color: #f1f5f9 !important;">
                            {{-- Offers Count --}}
                            <span class="text-muted d-flex align-items-center gap-1" style="font-size: 0.85rem; font-weight: 600;">
                                <i class="bi bi-people"></i> {{ $req->offers_count ?? 0 }} عروض
                            </span>
                            {{-- Pricing Type --}}
                            @php
                                $pricingBg = '#f1f5f9';
                                $pricingColor = '#475569';
                                $pricingIcon = 'bi-cash';
                                switch($req->pricing_type) {
                                    case 'fixed': 
                                        $pricingBg = '#dcfce7'; $pricingColor = '#15803d'; $pricingIcon = 'bi-cash-stack'; 
                                        break;
                                    case 'hourly': 
                                        $pricingBg = '#e0e7ff'; $pricingColor = '#4338ca'; $pricingIcon = 'bi-clock-history'; 
                                        break;
                                    case 'negotiable': 
                                        $pricingBg = '#fef3c7'; $pricingColor = '#b45309'; $pricingIcon = 'bi-chat-dots'; 
                                        break;
                                }
                            @endphp
                            <span class="badge d-inline-flex align-items-center gap-1" style="background-color: {{ $pricingBg }}; color: {{ $pricingColor }}; border-radius: 8px; padding: 6px 12px; font-weight: 600; font-size: 0.85rem;">
                                <i class="bi {{ $pricingIcon }}"></i>
                                @switch($req->pricing_type)
                                    @case('fixed')      ثابت @break
                                    @case('hourly')     بالساعة @break
                                    @case('negotiable') قابل للتفاوض @break
                                    @default {{ $req->pricing_type }}
                                @endswitch
                            </span>
                            {{-- Budget (hidden when null) --}}
                            @if($req->budget)
                            <span class="fw-bold" style="font-size: 1rem; color: #1e3a8a;">
                                {{ $req->currency_code ?? 'USD' }}
                                {{ number_format($req->budget, 0) }}
                            </span>
                            @endif
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-5 d-flex justify-content-center">
            {{ $requests->links() }}
        </div>

    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
            <h4 class="mt-3 text-muted">لا توجد طلبات متاحة حالياً</h4>
            <p class="text-muted">يرجى العودة لاحقاً لاستكشاف الطلبات الجديدة.</p>
        </div>
    @endif
    
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const requestsGrid = document.getElementById('requestsGrid');
        if (requestsGrid) {
            requestsGrid.addEventListener('click', function(e) {
                const card = e.target.closest('.request-card-wrapper[data-href]');
                if (!card) return;
                
                // Ignore clicks from buttons, links, forms inside the card
                if (e.target.closest('button, a, form')) return;
                
                window.location.href = card.getAttribute('data-href');
            });
        }
    });
</script>
@endsection
