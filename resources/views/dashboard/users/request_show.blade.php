@extends('layout.dash')
@section('content')

<style>
    /* ── Page ── */
    .req-page-header h4 { font-weight: 700; font-size: 1.4rem; color: #1e293b; margin-bottom: 4px; }
    .req-page-header p  { color: #64748b; margin: 0; font-size: 0.92rem; }

    /* ── Alerts ── */
    .alert-custom { border-radius: 12px; border: none; padding: 12px 16px; margin-bottom: 16px; font-size: 0.92rem; }
    .alert-success-custom { background: #dcfce7; color: #15803d; border-left: 4px solid #15803d; border-radius: 0 12px 12px 0; }
    .alert-danger-custom  { background: #fee2e2; color: #b91c1c; border-left: 4px solid #b91c1c; border-radius: 0 12px 12px 0; }

    /* ── Auto direction (Arabic / English) ── */
    [dir="auto"] { text-align: start; unicode-bidi: plaintext; }

    /* ── Request Details Card ── */
    .request-details-card {
        border-radius: 16px; border: 1px solid #e0e6ed; overflow: hidden; margin-bottom: 24px;
        max-width: 900px; margin: 0 auto;
    }
    @media (max-width: 768px) { .request-details-card { max-width: 100%; } }
    .request-details-card .card-header { background: #f8fafc; border-bottom: 1px solid #e0e6ed; padding: 16px 20px; }
    .request-details-card .card-header h5 { margin: 0; font-weight: 700; color: #1e293b; font-size: 1.1rem; }
    .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; padding: 20px; }
    .detail-item { display: flex; gap: 12px; }
    .detail-icon { font-size: 1.1rem; color: #4f46e5; flex-shrink: 0; margin-top: 1px; }
    .detail-content h6 { font-size: 0.75rem; color: #64748b; font-weight: 600; margin-bottom: 2px; text-transform: uppercase; letter-spacing: 0.5px; }
    .detail-content p { margin: 0; font-size: 0.9rem; color: #1e293b; font-weight: 600; }
    .badge-wrapper { display: flex; flex-wrap: wrap; gap: 6px; }

    /* ── Offer Field Cards (Modal) ── */
    .offer-field-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px;
        background: #fff;
    }
    .offer-field-card .form-control,
    .offer-field-card .form-select {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: 4px 0;
        font-size: 1.05rem;
        color: #1e293b;
    }
    .offer-field-card .form-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24'%3E%3Cpath fill='%236b7280' d='M7 10l5 5 5-5z'/%3E%3C/svg%3E") !important;
        background-repeat: no-repeat !important;
        background-position: right 4px center !important;
        padding-right: 20px !important;
        width: auto !important;
        cursor: pointer;
    }
    .offer-field-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .offer-field-helper {
        font-size: 0.75rem;
        color: #9ca3af;
        margin-top: 8px;
        margin-bottom: 0;
    }
    .offer-field-card .input-group {
        gap: 0;
        border-bottom: 1px solid #f3f4f6;
        padding-bottom: 4px;
    }

    /* ── Add Offer Btn ── */
    .btn-add-offer {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 240px;
        height: 65px;
        border-radius: 35px;
        background: linear-gradient(135deg, #6366f1 0%, #4338ca 100%);
        box-shadow: 0 10px 25px -4px rgba(79, 70, 229, 0.5), inset 0 2px 4px rgba(255, 255, 255, 0.3);
        color: #fff;
        border: 2px solid rgba(255, 255, 255, 0.1);
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        margin: 30px auto 50px auto;
        position: relative;
        overflow: hidden;
    }
    .btn-add-offer::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 50%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
        transform: skewX(-25deg);
        transition: all 0.6s ease;
    }
    .btn-add-offer:hover::before {
        left: 150%;
    }
    .btn-add-offer:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 18px 35px -5px rgba(79, 70, 229, 0.6), inset 0 2px 4px rgba(255, 255, 255, 0.4); 
        color: #fff; 
        background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);
    }
    .btn-add-offer i {
        font-size: 1.4rem;
        transition: transform 0.3s ease;
    }
    .btn-add-offer:hover i {
        transform: scale(1.2);
    }

    /* ── Offer Tabs ── */
    .offers-tabs { display: flex; gap: 0; border-bottom: 1.5px solid #e0e6ed; margin-bottom: 20px; }
    .offers-tab-btn {
        background: none; border: none; padding: 11px 18px;
        font-size: 0.88rem; font-weight: 600; color: #64748b; cursor: pointer;
        border-bottom: 2.5px solid transparent; margin-bottom: -1.5px;
        transition: color 0.2s, border-color 0.2s; display: flex; align-items: center; gap: 6px;
    }
    .offers-tab-btn:hover { color: #1e293b; }
    .offers-tab-btn.active { color: #4f46e5; border-bottom-color: #4f46e5; }
    .offers-tab-btn .tab-count { background: #f1f5f9; color: #475569; border-radius: 20px; padding: 1px 8px; font-size: 0.75rem; }
    .offers-tab-btn.active .tab-count { background: #eef2ff; color: #4f46e5; }

    /* ── Status Groups ── */
    .offers-status-group { display: none; margin-bottom: 28px; }
    .offers-status-group.active { display: block; }
    .status-group-title {
        font-size: 0.8rem; font-weight: 700; color: #94a3b8;
        margin-bottom: 12px; display: flex; align-items: center; gap: 6px;
        text-transform: uppercase; letter-spacing: 0.5px;
    }

    /*
     * OFFER CARD — two-column layout
     * ─────────────────────────────────────────────────────
     * Left col  (narrow, fixed 96px): avatar image + status pill
     * Right col (flex 1)            : name/price row, description, footer actions
     */
    .offer-card {
        display: flex;
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        overflow: hidden;
        margin-bottom: 14px;
        transition: box-shadow 0.2s ease;
    }
    .offer-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
    .offer-card.is-rejected { opacity: 0.62; }

    /* Left column */
    .offer-col-left {
        width: 96px;
        min-width: 96px;
        display: grid;
        align-content: start;
        justify-content: center;
        padding: 18px 0 14px;
        border-right: 1px solid #f1f5f9;
        background: #fafbfc;
    }
    .offer-avatar-img {
        grid-column: 1; grid-row: 1;
        width: 90px; height: 90px; border-radius: 50%;
        object-fit: cover; border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .offer-avatar-initials {
        grid-column: 1; grid-row: 1;
        width: 80px; height: 80px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.25rem;
        border: 2px solid #fff;
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .offer-card:hover .offer-avatar-img, .offer-card:hover .offer-avatar-initials {
        transform: scale(1.05);
        box-shadow: 0 6px 14px rgba(0,0,0,0.1);
    }
    
    .initials-pending  { background: #ede9fe; color: #4c1d95; }
    .initials-accepted { background: #dcfce7; color: #14532d; }
    .initials-rejected { background: #fee2e2; color: #9f1239; }

    .offer-status-pill {
        grid-column: 1; grid-row: 1;
        justify-self: right;
        align-self: end;
        margin-right: -12px;
        margin-bottom: -4px;
        z-index: 2;
        
        font-size: 0.65rem; font-weight: 700; padding: 4px 10px;
        border-radius: 20px; letter-spacing: 0.4px; white-space: nowrap;
        
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,0.4);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .offer-card:hover .offer-status-pill {
        transform: scale(1.05) translateY(-2px);
    }

    .pill-pending  { 
        background: rgba(254, 243, 199, 0.85); 
        color: #92400e; 
        border: 1px solid rgba(252, 211, 77, 0.5);
    }
    .pill-accepted { 
        background: rgba(220, 252, 231, 0.85); 
        color: #14532d; 
        border: 1px solid rgba(134, 239, 172, 0.5);
    }
    .pill-rejected { 
        background: rgba(254, 226, 226, 0.85); 
        color: #9f1239; 
        border: 1px solid rgba(252, 165, 165, 0.5);
    }
    .offer-card:hover .pill-pending { box-shadow: 0 6px 14px rgba(245, 158, 11, 0.25); }
    .offer-card:hover .pill-accepted { box-shadow: 0 6px 14px rgba(34, 197, 94, 0.25); }
    .offer-card:hover .pill-rejected { box-shadow: 0 6px 14px rgba(239, 68, 68, 0.25); }

    /* Right column */
    .offer-col-right { flex: 1; display: flex; flex-direction: column; min-width: 0; }

    /* Body */
    .offer-body { padding: 16px 20px 14px; flex: 1; }

    /* Name + price flex row */
    .offer-name-price-row {
        display: flex; align-items: flex-start;
        justify-content: space-between; gap: 12px; margin-bottom: 8px;
    }
    .offer-name { font-size: 1rem; font-weight: 700; color: #1e293b; margin: 0 0 4px; line-height: 1.2; }
    .offer-meta-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
    .badge-top-rated { font-size: 0.7rem; font-weight: 700; padding: 2px 8px; border-radius: 4px; background: #dbeafe; color: #1e40af; }
    .offer-rating-text { font-size: 0.78rem; color: #64748b; display: flex; align-items: center; gap: 3px; }
    .stars { color: #f59e0b; font-size: 0.78rem; }

    /* Price block */
    .offer-price-block { text-align: right; flex-shrink: 0; }
    .offer-price-value { font-size: 1.35rem; font-weight: 700; color: #1d4ed8; line-height: 1.1; white-space: nowrap; }
    .offer-delivery { font-size: 0.72rem; color: #94a3b8; margin-top: 3px; display: flex; align-items: center; gap: 3px; justify-content: flex-end; }

    /* Description */
    .offer-desc {
        font-size: 0.875rem; color: #475569; line-height: 1.65; margin: 10px 0 0;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
        cursor: pointer;
        transition: color 0.2s;
    }
    .offer-desc:hover { color: #1e293b; }
    .offer-desc.expanded {
        display: block;
    }

    /* Footer / actions */
    .offer-footer {
        border-top: 1px solid #f1f5f9;
        display: flex; align-items: center; gap: 8px; background: #fff;
        overflow: hidden;
        transition: all 0.3s ease;
        padding: 10px 20px 12px;
        max-height: 80px;
    }
    .offer-card:hover .offer-footer {
        opacity: 1;
    }
    .btn-accept {
        background: #34b162fe; color: #fff; border: none; border-radius: 6px;
        padding: 9px 22px; font-size: 0.84rem; font-weight: 700; cursor: pointer;
        display: flex; align-items: center; gap: 6px; transition: background 0.15s;
    }
    .btn-accept:hover { background: #169667ff; color: #fff; }
    .btn-reject {
        background: #fde8e8; color: #c01a1aff; border: 1px solid #fecdd3;
        border-radius: 6px; padding: 9px 20px; font-size: 0.84rem;
        font-weight: 600; cursor: pointer; transition: background 0.15s, border-color 0.15s;
        display: flex; align-items: center; gap: 6px;
    }
    .btn-reject:hover { background: #fcd3d3ff; border-color: #fda4af; color: #9f1239; }
    .btn-icon {
        background: #fff; color: #6b7280; border: 1px solid #d1d5db;
        border-radius: 6px; width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background 0.15s; font-size: 0.95rem;
    }
    .btn-icon:hover { background: #f3f4f6; color: #374151; }
    .btn-contract {
        background: #0f766e; color: #fff; border: none; border-radius: 6px;
        padding: 9px 20px; font-size: 0.84rem; font-weight: 700; cursor: pointer;
        display: flex; align-items: center; gap: 6px; transition: background 0.15s;
    }
    .btn-contract:hover { background: #0d6468; color: #fff; }
    .btn-archive {
        background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;
        border-radius: 6px; padding: 9px 18px; font-size: 0.84rem;
        font-weight: 600; cursor: pointer; display: flex; align-items: center;
        gap: 6px; transition: background 0.15s;
    }
    .btn-archive:hover { background: #e2e8f0; }

    /* Empty state */
    .empty-offers { text-align: center; padding: 48px 20px; background: #fff; border: 1px solid #e0e6ed; border-radius: 12px; }
    .empty-offers i { font-size: 2.5rem; color: #cbd5e1; display: block; margin-bottom: 14px; }
    .empty-offers h6 { font-size: 1rem; color: #1e293b; font-weight: 700; margin-bottom: 6px; }
    .empty-offers p { color: #64748b; font-size: 0.88rem; margin: 0; }

    /* Modal */
    .modal-content { border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
    .modal-header { background: #f8fafc !important; padding: 18px 20px !important; border-bottom: 1px solid #e0e6ed !important; border-radius: 16px 16px 0 0; }
    #offerMessage:focus { border-color: #4f46e5 !important; box-shadow: 0 0 0 3px rgba(79,70,229,0.1) !important; outline: none; }

    @media (max-width: 600px) {
        .offer-card { 
            display: grid; 
            grid-template-columns: min-content 1fr auto;
            grid-template-areas: 
                "avatar . price"
                "name name name"
                "divider divider divider"
                "desc desc desc"
                "footer footer footer";
            padding: 14px 20px 0;
            gap: 4px 12px;
        }
        .offer-card::before {
            content: "";
            grid-area: divider;
            border-bottom: 1px solid #eee;
            margin: 10px -20px 5px -20px;
        }

        .offer-col-left, .offer-col-right, .offer-body, .offer-name-price-row {
            display: contents;
        }

        .offer-avatar-img, .offer-avatar-initials {
            grid-area: avatar;
            width: 70px; height: 70px; font-size: 1rem;
        }
        
        .offer-status-pill {
            grid-area: avatar;
            justify-self: right;
            align-self: end;
            margin-right: -10px;
            margin-bottom: -5px;
            margin-top: 0;
            z-index: 2;
        }

        .offer-name-block {
            grid-area: name;
        }
        .offer-name { font-size: 0.95rem; margin-bottom: 2px; }

        .offer-price-block {
            grid-area: price;
            text-align: left;
            align-self: start;
            margin-top: 4px;
        }
        .offer-price-value { font-size: 1.15rem; }
        .offer-delivery { justify-content: flex-start; }
        
        .offer-desc { 
            grid-area: desc;
            -webkit-line-clamp: 4; 
            font-size: 0.9rem; 
            margin: 0;
        }
        
        .offer-footer {
            grid-area: footer;
            opacity: 1;
            max-height: 80px;
            padding: 10px 20px 12px;
            margin: 10px -20px 0 -20px;
            border-top-color: #f1f5f9;
            overflow: visible;
        }
    }

    @media (max-width: 470px) {
        .offer-footer button:not(.btn-icon) {
            font-size: 0 !important;
            gap: 0 !important;
            padding: 8px 16px !important;
            justify-content: center;
        }
        .offer-footer button:not(.btn-icon) i {
            font-size: 1.3rem !important;
            -webkit-text-stroke: 0.8px;
        }
    }
</style>

<div class="px-4 pb-5">

    {{-- Alerts --}}
    @if($errors->any())
        <div class="alert-custom alert-danger-custom mt-3">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif
    @if(session('success'))
        <div class="alert-custom alert-success-custom mt-3">
            <i class="bi bi-check-circle ms-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="req-page-header pt-4 mb-4">
        <h4 dir="auto"><i class="bi bi-file-earmark-text-fill text-primary ms-2"></i>{{ $serviceRequest->title }}</h4>
    </div>

    {{-- Request Details Card --}}
    
    <div class="mb-4">
        <div class="d-flex flex-wrap gap-4 align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-person-fill fs-5"></i>
                </div>
                <div>
                    <h6 class="mb-0 text-muted" style="font-size: 0.8rem;">صاحب الطلب</h6>
                    <span class="fw-bold text-dark">{{ $serviceRequest->user->name ?? 'غير معروف' }}</span>
                </div>
            </div>
            
            <div class="d-flex gap-3 ms-auto bg-light rounded-pill px-3 py-2">
                <div class="d-flex align-items-center gap-1 text-muted">
                    <i class="bi bi-wallet2 text-primary"></i>
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                        @if($serviceRequest->budget)
                            @php $currencySymbols = ['USD' => '$', 'EUR' => '€', 'JOD' => 'دينار', 'ILS' => '₪']; @endphp
                            {{ number_format($serviceRequest->budget, 0) }} {{ $currencySymbols[$serviceRequest->currency_code] ?? $serviceRequest->currency_code }}
                        @else
                            غير محدد
                        @endif
                    </span>
                </div>
                
                <div class="border-start ps-3 d-flex align-items-center gap-1 text-muted">
                    <i class="bi bi-tag-fill text-primary"></i>
                    <span class="fw-bold text-dark" style="font-size: 0.9rem;">
                        @php
                            $pricingText = ['fixed'=>'سعر ثابت', 'hourly'=>'بالساعة', 'negotiable'=>'قابل للتفاوض'];
                        @endphp
                        {{ $pricingText[$serviceRequest->pricing_type] ?? $serviceRequest->pricing_type }}
                    </span>
                </div>
            </div>
        </div>
        <div class="bg-white p-4 rounded-4 shadow-sm border border-light">
            <p dir="auto" class="mb-0 text-dark" style="line-height: 1.8; font-size: 1.05rem; white-space: pre-line;">{{ $serviceRequest->description }}</p>
            
            <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top border-light">
                @foreach($serviceRequest->categories as $cat)
                    <span class="badge bg-light text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill fw-normal">
                        {{ $cat->name }}
                    </span>
                @endforeach
            </div>
        </div>
        @if($serviceRequest->image)
            <div class="mb-3 rounded-4 overflow-hidden shadow-sm border border-light bg-white" style="max-height: 400px; display: flex; justify-content: center; align-items: center;">
                <img src="{{ filter_var($serviceRequest->image, FILTER_VALIDATE_URL) ? $serviceRequest->image : Storage::url($serviceRequest->image) }}" 
                     class="img-fluid" 
                     style="object-fit: contain; width: 100%; max-height: 400px;" 
                     alt="صورة الطلب">
            </div>
        @else
            <div class="mb-3 rounded-4 overflow-hidden shadow-sm border border-light bg-light d-flex align-items-center justify-content-center" style="height: 200px; border: 2px dashed #cbd5e1 !important;">
                <div class="text-center text-muted">
                    <i class="bi bi-image fs-1 d-block mb-2 opacity-50"></i>
                    <span style="font-size: 0.95rem; font-weight: 600;">لا توجد صورة مرفقة</span>
                </div>
            </div>
        @endif
    </div>

    @php
        $currentUser = auth()->user() ?? \App\Models\User::first();
        $isRequester = $serviceRequest->user_id === ($currentUser->id ?? null);
        $hasOffer = $serviceRequest->offers->where('user_id', $currentUser->id ?? null)->count() > 0;
    @endphp

    {{-- Add Offer Button --}}
    @if(!$isRequester && !$hasOffer)
    <button class="btn-add-offer" onclick="openOfferModal()">
        <i class="bi bi-plus-circle"></i> إضافة عرضك
    </button>
    @endif

    {{-- ═══════ OFFERS ═══════ --}}
    <div id="offersSectionWrapper">
    @if($serviceRequest->offers->count() > 0)
        @php
            $statusCounts  = $serviceRequest->offers->countBy('status');
            $pendingCount  = $statusCounts->get('pending', 0);
            $acceptedCount = $statusCounts->get('accepted', 0);
            $rejectedCount = $statusCounts->get('rejected', 0);
        @endphp

        <div class="offers-tabs">
            <button class="offers-tab-btn active" onclick="filterOffers('all')" data-status="all">
                جميع العروض <span class="tab-count">{{ $serviceRequest->offers->count() }}</span>
            </button>
            @if($pendingCount > 0)
            <button class="offers-tab-btn" onclick="filterOffers('pending')" data-status="pending">
                <i class="bi bi-clock-history text-warning"></i> قيد الانتظار <span class="tab-count">{{ $pendingCount }}</span>
            </button>
            @endif
            @if($acceptedCount > 0)
            <button class="offers-tab-btn" onclick="filterOffers('accepted')" data-status="accepted">
                <i class="bi bi-check-circle text-success"></i> مقبول <span class="tab-count">{{ $acceptedCount }}</span>
            </button>
            @endif
            @if($rejectedCount > 0)
            <button class="offers-tab-btn" onclick="filterOffers('rejected')" data-status="rejected">
                <i class="bi bi-x-circle text-danger"></i> مرفوض <span class="tab-count">{{ $rejectedCount }}</span>
            </button>
            @endif
        </div>

        <div id="offersContainer">

            {{-- PENDING --}}
            
            @if($pendingCount > 0)
            <div class="offers-status-group" data-status-filter="pending">
                <p class="status-group-title"><i class="bi bi-clock-history text-warning"></i> قيد الانتظار</p>
                @foreach($serviceRequest->offers->where('status','pending') as $offer)
                @php $initials = strtoupper(substr($offer->user->name ?? 'U', 0, 2)); @endphp
                <div class="offer-card">

                    {{-- Left: avatar + status pill --}}
                    <div class="offer-col-left">
                        @if($offer->user->avatar ?? false)
                            <img src="{{ $offer->user->avatar }}" alt="{{ $offer->user->name }}" class="offer-avatar-img">
                        @else
                            <div class="offer-avatar-initials initials-pending">{{ $initials }}</div>
                        @endif
                        <span class="offer-status-pill pill-pending">Pending</span>
                    </div>

                    {{-- Right: name/price + desc + footer --}}
                    <div class="offer-col-right">
                        <div class="offer-body">
                            {{-- flex row: name block (left) | price block (right) --}}
                            <div class="offer-name-price-row">
                                <div class="offer-name-block">
                                    <p class="offer-name">{{ $offer->user->name ?? 'Unknown' }}</p></p>
                                    <div class="offer-meta-row">
                                        <span class="badge-top-rated">Top Rated</span>
                                        <span class="offer-rating-text">
                                            <span class="stars">★</span> 4.9/5
                                            <span style="color:#94a3b8;">(128 تقييم)</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="offer-price-block">
                                    <div class="offer-price-value">
                                         {{ number_format($offer->proposed_price, 2) }} {{ $currencySymbols[$offer->currency_code] ?? $offer->currency_code }}
                                    </div>
                                    @if($offer->estimated_time)
                                    <div class="offer-delivery">
                                        <i class="bi bi-calendar3" style="font-size:0.7rem;"></i>
                                        وقت الانجاز: {{ $offer->estimated_time }} {{ $offer->time_unit ?? 'Days' }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @if($offer->message)
                            <p class="offer-desc" dir="auto" onclick="this.classList.toggle('expanded')" title="انقر لعرض النص كاملاً">{{ $offer->message }}</p>
                            @endif
                        </div>

                        {{-- Card footer: action buttons --}}
                        <div class="offer-footer">
                            @if($isRequester)
                                <button type="button" class="btn-accept" onclick="handleOfferAction('{{ route('dashboard.requests.offers.accept', [$serviceRequest->id, $offer->id]) }}', 'POST', this)">
                                    <i class="bi bi-check-lg"></i> Accept Offer
                                </button>
                                <button type="button" class="btn-reject" onclick="handleOfferAction('{{ route('dashboard.requests.offers.reject', [$serviceRequest->id, $offer->id]) }}', 'POST', this)">
                                    <i class="bi bi-x-lg"></i> Reject
                                </button>
                            @endif
                            @if($offer->user_id === ($currentUser->id ?? null))
                                <button type="button" class="btn-archive" onclick="openOfferModal(true, { id: '{{ $offer->id }}', price: {{ $offer->proposed_price }}, time: {{ $offer->estimated_time ?? 0 }}, unit: '{{ $offer->time_unit ?? 'days' }}', currency: '{{ $offer->currency_code ?? 'USD' }}', message: '{{ e($offer->message) }}' })">
                                    <i class="bi bi-pencil"></i> تعديل
                                </button>
                                <button type="button" class="btn-reject" onclick="handleOfferAction('{{ route('dashboard.offers.destroy', $offer->id) }}', 'DELETE', this, 'هل أنت متأكد من حذف العرض؟')">
                                    <i class="bi bi-trash"></i> حذف
                                </button>
                            @endif
                            <button class="btn-icon" title="مراسلة">
                                <i class="bi bi-chat"></i>
                            </button>
                        </div>
                    </div>

                </div>
                @endforeach
            </div>
            @endif

            {{-- ACCEPTED --}}
            @if($acceptedCount > 0)
            <div class="offers-status-group" data-status-filter="accepted">
                <p class="status-group-title"><i class="bi bi-check-circle text-success"></i> مقبول</p>
                @foreach($serviceRequest->offers->where('status','accepted') as $offer)
                @php $initials = strtoupper(substr($offer->user->name ?? 'U', 0, 2)); @endphp
                <div class="offer-card">
                    <div class="offer-col-left">
                        @if($offer->user->avatar ?? false)
                            <img src="{{ $offer->user->avatar }}" alt="{{ $offer->user->name }}" class="offer-avatar-img">
                        @else
                            <div class="offer-avatar-initials initials-accepted">{{ $initials }}</div>
                        @endif
                        <span class="offer-status-pill pill-accepted">Accepted</span>
                    </div>
                    <div class="offer-col-right">
                        <div class="offer-body">
                            <div class="offer-name-price-row">
                                <div class="offer-name-block">
                                    <p class="offer-name" dir="auto">{{ $offer->user->name ?? 'Unknown' }}</p></p>
                                    <div class="offer-meta-row">
                                        <span class="badge-top-rated" style="background:#dcfce7;color:#14532d;">Accepted</span>
                                        <span class="offer-rating-text">
                                            <span class="stars">★</span> 5.0/5
                                            <span style="color:#94a3b8;">(98 تقييم)</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="offer-price-block">
                                    <div class="offer-price-value">
                                        {{ number_format($offer->proposed_price, 2) }} {{ $currencySymbols[$offer->currency_code] ?? $offer->currency_code }}
                                    </div>
                                    @if($offer->estimated_time)
                                    <div class="offer-delivery">
                                        <i class="bi bi-calendar3" style="font-size:0.7rem;"></i>
                                        وقت الانجاز:  {{ $offer->estimated_time }} {{ $offer->time_unit ?? 'Days' }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @if($offer->message)
                            <p class="offer-desc" dir="auto" onclick="this.classList.toggle('expanded')" title="انقر لعرض النص كاملاً">{{ $offer->message }}</p>
                            @endif
                        </div>
                        <div class="offer-footer">
                            @php
                                $linkedOrder = \App\Models\Order::where('offer_id', $offer->id)->first();
                                $isOfferOwner = $offer->user_id === ($currentUser->id ?? null);
                            @endphp

                            @if($isRequester || $isOfferOwner)
                                @if($linkedOrder)
                                    <a href="{{ route('dashboard.orders.show', $linkedOrder->id) }}" class="btn-contract text-decoration-none">
                                        <i class="bi bi-briefcase-fill"></i> عرض الطلب
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 0.85rem;">
                                        <i class="bi bi-hourglass-split"></i> في انتظار إنشاء الطلب
                                    </span>
                                @endif
                            @endif
                            <button class="btn-icon" title="مراسلة"><i class="bi bi-chat"></i></button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            {{-- REJECTED --}}
            @if($rejectedCount > 0)
            <div class="offers-status-group" data-status-filter="rejected">
                <p class="status-group-title"><i class="bi bi-x-circle text-danger"></i> مرفوض</p>
                @foreach($serviceRequest->offers->where('status','rejected') as $offer)
                @php $initials = strtoupper(substr($offer->user->name ?? 'U', 0, 2)); @endphp
                <div class="offer-card is-rejected">
                    <div class="offer-col-left">
                        @if($offer->user->avatar ?? false)
                            <img src="{{ $offer->user->avatar }}" alt="{{ $offer->user->name }}" class="offer-avatar-img">
                        @else
                            <div class="offer-avatar-initials initials-rejected">{{ $initials }}</div>
                        @endif
                        <span class="offer-status-pill pill-rejected">Rejected</span>
                    </div>
                    <div class="offer-col-right">
                        <div class="offer-body">
                            <div class="offer-name-price-row">
                                <div class="offer-name-block">
                                    <p class="offer-name" dir="auto">{{ $offer->user->name ?? 'Unknown' }}</p></p>
                                    <div class="offer-meta-row">
                                        <span class="badge-top-rated" style="background:#fee2e2;color:#9f1239;">Rejected</span>
                                        <span class="offer-rating-text">
                                            <span class="stars">★</span> 4.5/5
                                            <span style="color:#94a3b8;">(64 تقييم)</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="offer-price-block">
                                    <div class="offer-price-value" style="color:#94a3b8;">
                                       {{ number_format($offer->proposed_price, 2) }} {{ $currencySymbols[$offer->currency_code] ?? $offer->currency_code }} 
                                    </div>
                                    @if($offer->estimated_time)
                                    <div class="offer-delivery">
                                        <i class="bi bi-calendar3" style="font-size:0.7rem;"></i>
                                        وقت الانجاز:  {{ $offer->estimated_time }} {{ $offer->time_unit ?? 'Days' }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @if($offer->message)
                            <p class="offer-desc" dir="auto" onclick="this.classList.toggle('expanded')" title="انقر لعرض النص كاملاً">{{ $offer->message }}</p>
                            @endif
                        </div>
                        <div class="offer-footer">
                            @if($isRequester)
                                <button type="button" class="btn-archive" onclick="handleOfferAction('{{ route('dashboard.requests.offers.reset', [$serviceRequest->id, $offer->id]) }}', 'POST', this)">
                                    <i class="bi bi-arrow-counterclockwise"></i> Reset
                                </button>
                                <button class="btn-archive"><i class="bi bi-archive"></i> أرشفة</button>
                            @endif
                            <button class="btn-icon" title="مراسلة"><i class="bi bi-chat"></i></button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>

    @else
        <div class="empty-offers">
            <i class="bi bi-inbox"></i>
            <h6>لا توجد عروض حتى الآن</h6>
            <p>لم يتلقَّ الطلب أي عروض بعد. شارِك طلبك وانتظر العروض من المستقلين.</p>
        </div>
    @endif
    </div>

</div>

{{-- OFFER MODAL --}}
<div class="modal fade" id="offerModal" tabindex="-1" aria-labelledby="offerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="max-width:720px !important;">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-primary" id="offerModalLabel">
                    <i class="bi bi-pencil-square ms-2"></i> إضافة عرضك
                </h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="createOfferForm" action="{{ route('dashboard.offers.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="request_id" value="{{ $serviceRequest->id }}">
                    <input type="hidden" name="status" id="offerStatus" value="pending">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="offer-field-card">
                                <p class="offer-field-label">
                                    <i class="bi bi-cash-stack text-primary"></i> السعر المقترح <span class="text-danger">*</span>
                                </p>
                                <div class="input-group">
                                    <input type="number" step="5.00" name="proposed_price" class="form-control"
                                           placeholder="0.00" required>
                                    <select name="currency_code" class="form-select">
                                        <option value="USD">USD</option>
                                        <option value="ILS">ILS</option>
                                        <option value="JOD">JOD</option>
                                        <option value="EUR">EUR</option>
                                    </select>
                                </div>
                                <p class="offer-field-helper">أدخل السعر الإجمالي بالعملة المختارة</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="offer-field-card">
                                <p class="offer-field-label">
                                    <i class="bi bi-calendar-event text-primary"></i> الوقت المقدر للتسليم
                                </p>
                                <div class="input-group">
                                    <input type="number" name="estimated_time" min="1" class="form-control"
                                           placeholder="0">
                                    <select name="time_unit" class="form-select">
                                        <option value="hours">ساعات</option>
                                        <option value="days" selected>أيام</option>
                                        <option value="weeks">أسابيع</option>
                                    </select>
                                </div>
                                <p class="offer-field-helper">المدة الزمنية اللازمة لإنجاز المشروع</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="offer-field-card">
                                <p class="offer-field-label d-flex justify-content-between">
                                    <span><i class="bi bi-chat-dots text-primary"></i> رسالتك الاحترافية <span class="text-danger">*</span></span>
                                    <span class="text-muted fw-normal" style="font-size:0.75rem;"><span id="charCount">0</span> / 2000</span>
                                </p>
                                <textarea name="message" id="offerMessage" class="form-control" rows="5"
                                          dir="auto"
                                          placeholder="اشرح عرضك، خبرتك، المشاريع السابقة، وسبب تناسبك لهذا المشروع..."
                                          maxlength="2000" style="resize:none;" required></textarea>
                                <p class="offer-field-helper">اكتب عرضاً مقنعاً يُبرز مهاراتك وخبرتك</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="offer-field-card">
                                <p class="offer-field-label">
                                    <i class="bi bi-calendar-check text-primary"></i> انتهاء الصلاحية
                                    <span class="text-muted fw-normal" style="font-weight:400;">(اختياري)</span>
                                </p>
                                <input type="date" name="expires_at" class="form-control">
                                <p class="offer-field-helper">آخر موعد لقبول هذا العرض من قِبل العميل</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-3 justify-content-end mt-4">
                        <button type="button" class="btn btn-outline-secondary fw-bold px-4"
                                style="border-radius:999px;" onclick="submitOffer('draft')">
                            <i class="bi bi-file-earmark ms-2"></i>حفظ كمسودة
                        </button>
                        <button type="button" class="btn btn-primary fw-bold px-4"
                                style="border-radius:999px;background:#4f46e5;border:none;"
                                onclick="submitOffer('pending')">
                            <i class="bi bi-send-fill ms-2"></i>إرسال العرض
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let offerModalInstance;

    document.addEventListener('DOMContentLoaded', function () {
        offerModalInstance = new bootstrap.Modal(document.getElementById('offerModal'));
        const msg = document.getElementById('offerMessage');
        if (msg) msg.addEventListener('input', function () {
            document.getElementById('charCount').textContent = this.value.length;
        });
        document.querySelectorAll('.offers-status-group').forEach(g => g.classList.add('active'));
    });

    document.getElementById('offerModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('createOfferForm').reset();
        document.getElementById('charCount').textContent = '0';
    });

    function openOfferModal(isEdit = false, offerData = null) {
        const form = document.getElementById('createOfferForm');
        const title = document.getElementById('offerModalLabel');
        if (isEdit && offerData) {
            title.innerHTML = '<i class="bi bi-pencil-square ms-2"></i> تعديل العرض';
            form.action = `/dashboard/offers/${offerData.id}`;
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            }
            form.querySelector('input[name="proposed_price"]').value = offerData.price;
            form.querySelector('input[name="estimated_time"]').value = offerData.time;
            form.querySelector('select[name="time_unit"]').value = offerData.unit;
            const currencySelect = form.querySelector('select[name="currency_code"]');
            if (currencySelect) currencySelect.value = offerData.currency || 'USD';
            form.querySelector('textarea[name="message"]').value = offerData.message;
            document.getElementById('charCount').textContent = offerData.message ? offerData.message.length : 0;
        } else {
            title.innerHTML = '<i class="bi bi-pencil-square ms-2"></i> إضافة عرضك';
            form.action = "{{ route('dashboard.offers.store') }}";
            let methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
            form.reset();
            document.getElementById('charCount').textContent = '0';
        }
        offerModalInstance.show();
    }

    async function submitOffer(status) {
        document.getElementById('offerStatus').value = status;
        const form = document.getElementById('createOfferForm');
        
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const url = form.action;
        const method = 'POST'; 

        const btnDraft = form.querySelector('.btn-outline-secondary');
        const btnSubmit = form.querySelector('.btn-primary');
        const originalDraftText = btnDraft.innerHTML;
        const originalSubmitText = btnSubmit.innerHTML;

        btnDraft.disabled = true;
        btnSubmit.disabled = true;
        if(status === 'draft') btnDraft.innerHTML = 'جاري الحفظ...';
        else btnSubmit.innerHTML = 'جاري الإرسال...';
        
        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });
            
            let data;
            try { data = await response.json(); } catch(e) { data = {}; }
            
            if (response.ok) {
                offerModalInstance.hide();
                refreshOffers();
                const newOfferBtn = document.querySelector(".btn-add-offer");
                if (newOfferBtn){ newOfferBtn.style.display = "none"; }
            } else {
                if (data.errors) {
                    alert(Object.values(data.errors).flat().join('\n'));
                } else {
                    alert(data.error || data.message || 'حدث خطأ');
                }
            }
        } catch(error) {
            console.error(error);
            alert('حدث خطأ في الاتصال');
        } finally {
            btnDraft.disabled = false;
            btnSubmit.disabled = false;
            btnDraft.innerHTML = originalDraftText;
            btnSubmit.innerHTML = originalSubmitText;
        }
    }

    async function handleOfferAction(url, method, button, confirmMessage = null) {
        if (confirmMessage && !confirm(confirmMessage)) return;

        const originalHtml = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="width: 1rem; height: 1rem; margin-right: 4px;"></span> جاري...';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (response.ok) {
                refreshOffers();
            } else {
                alert(data.error || data.message || 'حدث خطأ ما');
                button.disabled = false;
                button.innerHTML = originalHtml;
            }
        } catch (error) {
            console.error('Error:', error);
            alert('حدث خطأ في الاتصال بالخادم');
            button.disabled = false;
            button.innerHTML = originalHtml;
        }
    }

    async function refreshOffers() {
        try {
            const response = await fetch(window.location.href, {
                headers: { 'Accept': 'text/html' }
            });
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const newWrapper = doc.getElementById('offersSectionWrapper');
            const currentWrapper = document.getElementById('offersSectionWrapper');

            if (newWrapper && currentWrapper) {
                currentWrapper.innerHTML = newWrapper.innerHTML;
                const activeTab = currentWrapper.querySelector('.offers-tab-btn.active');
                if (activeTab) {
                    filterOffers(activeTab.dataset.status);
                } else {
                    document.querySelectorAll('.offers-status-group').forEach(g => g.classList.add('active'));
                }
            }
        } catch (error) {
            console.error('Error refreshing offers:', error);
        }
    }

    function filterOffers(status) {
        document.querySelectorAll('.offers-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelector('[data-status="' + status + '"]')?.classList.add('active');
        document.querySelectorAll('.offers-status-group').forEach(g => {
            g.classList.toggle('active', status === 'all' || g.dataset.statusFilter === status);
        });
    }
</script>

@endsection