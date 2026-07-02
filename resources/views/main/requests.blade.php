@extends('layouts.web')

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
    .searchable-select-container {
        position: relative;
        max-width: 450px;
        margin: 0 auto;
        font-family: inherit;
    }
    .searchable-select-trigger {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 22px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 50px;
        cursor: pointer;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        font-weight: 500;
        color: #475569;
    }
    .searchable-select-trigger:hover {
        border-color: #cbd5e1;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    .searchable-select-trigger.open {
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
    }
    .searchable-select-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        margin-top: 10px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        z-index: 100;
        display: none;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .searchable-select-dropdown.show {
        display: block;
        animation: slideDown 0.2s ease-out;
    }
    .searchable-select-search {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
    }
    .searchable-select-search input {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.2s;
    }
    .searchable-select-search input:focus {
        border-color: #6366f1;
    }
    .searchable-select-options {
        max-height: 280px;
        overflow-y: auto;
        padding: 8px;
    }
    .searchable-option {
        padding: 10px 16px;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        color: #334155;
        font-weight: 500;
        display: flex;
        align-items: center;
        margin-bottom: 2px;
    }
    .searchable-option:hover {
        background: #f1f5f9;
        color: #0f172a;
        transform: translateX(-4px); /* RTL */
    }
    .searchable-option.selected {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: #fff;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="container py-5">
    
    <div class="text-center mb-5">
        <h2 class="fw-bold"><i class="bi bi-briefcase-fill text-primary me-2"></i>الطلبات المتاحة</h2>
        <p class="text-muted">تصفح أحدث طلبات الخدمات وقدم عروضك للعملاء</p>
    </div>

    {{-- Category Filters --}}
    @if(isset($categories) && $categories->count() > 0)
    <div class="mb-5">
        <div class="searchable-select-container" id="categoryFilterContainer">
            <div class="searchable-select-trigger" id="categorySelectTrigger">
                <span>
                    <i class="bi bi-funnel text-primary me-2 ms-1"></i> 
                    <span id="categorySelectedText">{{ request('category_name') ? request('category_name') : 'تصفية جميع التصنيفات...' }}</span>
                </span>
                <div class="d-flex align-items-center">
                    @if(request('category_name'))
                    <a href="{{ route('main.requests') }}" class="text-danger me-2 ms-2" title="مسح التصفية" style="font-size: 1.15rem; line-height: 0;">
                        <i class="bi bi-x-circle-fill"></i>
                    </a>
                    @endif
                    <i class="bi bi-chevron-down text-muted" id="categorySelectIcon" style="transition: transform 0.3s;"></i>
                </div>
            </div>
            
            <div class="searchable-select-dropdown" id="categorySelectDropdown">
                <div class="searchable-select-search">
                    <input type="text" id="categorySearchBox" placeholder="ابحث عن تصنيف (مثال: تصميم، برمجة)..." autocomplete="off">
                </div>
                <div class="searchable-select-options" id="categoryOptionsList">
                    <div class="searchable-option {{ !request('category_name') ? 'selected' : '' }}" data-value="">
                        <i class="bi bi-grid-fill me-2 ms-2"></i> جميع التصنيفات
                    </div>
                    @foreach($categories as $category)
                    <div class="searchable-option {{ request('category_name') == $category->name ? 'selected' : '' }}" data-value="{{ $category->name }}">
                        {{ $category->name }}
                    </div>
                    @endforeach
                </div>
            </div>
            
            <form action="{{ route('main.requests') }}" method="GET" id="categoryFilterForm" class="d-none">
                <input type="hidden" name="category_name" id="hiddenCategoryInput" value="{{ request('category_name') }}">
            </form>
        </div>
    </div>
    @endif
    
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
                                        @if($cat->icon && str_starts_with(trim($cat->icon), '<svg'))
                                            <span class="ms-1 d-inline-flex align-items-center" style="opacity: 0.8; width: 12px; height: 12px; margin-top: -2px;">
                                                {!! $cat->icon !!}
                                            </span>
                                        @else
                                            <i class="bi {{ $cat->icon ?: 'bi-tag-fill' }} ms-1" style="font-size: 0.75rem; opacity: 0.8;"></i>
                                        @endif
                                        {{ $cat->name }}
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

        // Custom Searchable Select Logic
        const trigger = document.getElementById('categorySelectTrigger');
        const dropdown = document.getElementById('categorySelectDropdown');
        const icon = document.getElementById('categorySelectIcon');
        const searchBox = document.getElementById('categorySearchBox');
        const optionsList = document.getElementById('categoryOptionsList');
        const options = optionsList ? optionsList.querySelectorAll('.searchable-option') : [];
        const hiddenInput = document.getElementById('hiddenCategoryInput');
        const form = document.getElementById('categoryFilterForm');

        if(trigger && dropdown) {
            // Toggle dropdown
            trigger.addEventListener('click', function(e) {
                if(e.target.closest('a')) return; // ignore clear button click
                const isOpen = dropdown.classList.contains('show');
                closeDropdowns();
                if (!isOpen) {
                    dropdown.classList.add('show');
                    trigger.classList.add('open');
                    icon.style.transform = 'rotate(180deg)';
                    setTimeout(() => searchBox.focus(), 50);
                }
            });

            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!trigger.contains(e.target) && !dropdown.contains(e.target)) {
                    closeDropdowns();
                }
            });

            function closeDropdowns() {
                dropdown.classList.remove('show');
                trigger.classList.remove('open');
                icon.style.transform = 'rotate(0deg)';
                searchBox.value = '';
                filterOptions('');
            }

            // Filter options on search
            searchBox.addEventListener('input', function() {
                filterOptions(this.value.toLowerCase());
            });

            function filterOptions(term) {
                options.forEach(opt => {
                    const text = opt.textContent.trim().toLowerCase();
                    if (text.includes(term)) {
                        opt.style.display = 'flex';
                    } else {
                        opt.style.display = 'none';
                    }
                });
            }

            // Handle option selection
            options.forEach(opt => {
                opt.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    hiddenInput.value = value;
                    form.submit();
                });
            });
        }
    });
</script>
@endsection
