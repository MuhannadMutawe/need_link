@extends('layout.dash')
@section('title')
    NeedLink - طلباتي
@endsection
@section('content')

    {{-- Page Header --}}
    <div class="req-page-header">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <h4><i class="bi bi-file-earmark-text-fill text-blue ms-2"></i>طلباتي</h4>
                <p>تصفّح جميع طلبات الخدمة التي أنشأتها وتابع حالتها</p>
            </div>

            <button type="button" class="btn btn-sm btn-primary" style="border-radius:999px; padding:8px 20px; font-weight:700;" onclick="openRequestModal()">
                <i class="bi bi-plus-lg ms-1"></i> طلب جديد
            </button>
        </div>

        {{-- Sort Pills --}}
        <div class="sort-pills mb-4">
            <a href="{{ route('dashboard.requests.index', ['sort' => 'created_at']) }}"
               class="sort-pill {{ $sortField === 'created_at' ? 'active' : '' }}">
                <i class="bi bi-clock ms-1"></i> الأحدث إنشاءً
            </a>
            <a href="{{ route('dashboard.requests.index', ['sort' => 'updated_at']) }}"
               class="sort-pill {{ $sortField === 'updated_at' ? 'active' : '' }}">
                <i class="bi bi-arrow-repeat ms-1"></i> آخر تحديث
            </a>
        </div>
    </div>

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
        .req-action-btn {
            opacity: 0.25;
            transition: opacity 0.2s ease;
        }
        .request-card-wrapper:hover .req-action-btn {
            opacity: 1;
        }
    </style>

    {{-- Requests List --}}
    <section class="px-4 pb-4">
        @if($requests->count() > 0)
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
                                    {{-- Action buttons --}}
                                    <div class="d-flex gap-1 ms-2" style="flex-shrink: 0; position: relative; z-index: 10;">
                                        <button class="btn btn-sm btn-light border-0 p-1 rounded-2 req-action-btn"
                                                type="button" title="تعديل"
                                                onclick="event.preventDefault(); event.stopPropagation(); fetchAndOpenModal('{{ $req->id }}')">
                                            <i class="bi bi-pencil text-primary" style="font-size: 0.95rem;"></i>
                                        </button>
                                        <form action="{{ route('dashboard.requests.destroy', $req->id) }}" method="POST"
                                              onsubmit="event.stopPropagation(); return confirm('هل أنت متأكد من حذف هذا الطلب؟');"
                                              style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-light border-0 p-1 rounded-2 req-action-btn"
                                                    type="submit" title="حذف"
                                                    onclick="event.stopPropagation()">
                                                <i class="bi bi-trash text-danger" style="font-size: 0.95rem;"></i>
                                            </button>
                                        </form>
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
                                    if($req->status == 'open') { $statusBg = 'bg-success text-white'; $statusText = 'نشط'; }
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
                                    <i class="bi bi-people"></i> {{ $req->offers_count }} عروض
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
            
            {{-- Load More / Paginator --}}
            @if($requests->hasMorePages())
            <div class="text-center mt-5 mb-3" id="loadMoreContainer" data-next-page="{{ $requests->nextPageUrl() }}">
                <button id="loadMoreRequestsBtn" class="btn btn-outline-primary" style="border-radius: 20px; font-weight: 600; padding: 10px 35px; font-size: 1.05rem;">
                    عرض المزيد من الطلبات
                </button>
            </div>
            @endif
            
        @else
            {{-- Empty State --}}
            <div class="req-card shadow-sm p-5 text-center" style="background: #fff; border-radius: 16px;">
                <div class="empty-state">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">لا توجد طلبات بعد</h5>
                    <p class="text-muted">ابدأ بإنشاء أول طلب خدمة لك وسيظهر هنا</p>
                    <button type="button" class="btn btn-primary mt-3" style="border-radius:999px; padding:8px 24px; font-weight:700;" onclick="openRequestModal()">
                        <i class="bi bi-plus-lg ms-1"></i> أنشئ طلبك الأول
                    </button>
                </div>
            </div>
        @endif
    </section>

    {{-- Include the request form modal --}}
    @include('dashboard.users.partials._request_form_modal')

@endsection

@section('script')
<script>
    async function fetchAndOpenModal(id) {
        try {
            let res = await fetch(`/dashboard/requests/${id}`, {
                headers: { 'Accept': 'application/json' }
            });
            if(res.ok) {
                let data = await res.json();
                openRequestModal(data);
            }
        } catch(e) {
            console.error('Error fetching request data:', e);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const loadMoreBtn = document.getElementById('loadMoreRequestsBtn');
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        const requestsGrid = document.getElementById('requestsGrid');

        // AJAX Load More Logic
        if (loadMoreBtn && loadMoreContainer) {
            loadMoreBtn.addEventListener('click', async function() {
                const originalText = loadMoreBtn.innerHTML;
                loadMoreBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> جاري التحميل...';
                loadMoreBtn.disabled = true;

                try {
                    let url = loadMoreContainer.getAttribute('data-next-page');
                    if (!url) return;
                    
                    let res = await fetch(url);
                    let html = await res.text();
                    
                    let parser = new DOMParser();
                    let doc = parser.parseFromString(html, 'text/html');
                    
                    let newItems = doc.querySelectorAll('.request-grid-item');
                    newItems.forEach(item => {
                        requestsGrid.appendChild(item);
                    });

                    let newContainer = doc.getElementById('loadMoreContainer');
                    if (newContainer && newContainer.getAttribute('data-next-page')) {
                        loadMoreContainer.setAttribute('data-next-page', newContainer.getAttribute('data-next-page'));
                        loadMoreBtn.innerHTML = originalText;
                        loadMoreBtn.disabled = false;
                    } else {
                        loadMoreContainer.style.display = 'none';
                    }

                } catch(e) {
                    console.error('Failed to load more', e);
                    loadMoreBtn.innerHTML = originalText;
                    loadMoreBtn.disabled = false;
                }
            });
        }

        // Delegated Card Click Navigation
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