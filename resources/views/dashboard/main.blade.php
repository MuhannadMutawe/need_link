@extends('layouts.app')
@section('title', 'لوحة التحكم | NeedLink')

@section('content')
<section class="p-4">

    {{-- ── Page Header ── --}}
    <div class="mb-4">
        <h4 class="fw-bold mb-1">مرحباً، {{ $user->name }}</h4>
        <p class="text-muted mb-0">
            @if($totalActions > 0)
                لديك <span class="fw-bold text-primary">{{ $totalActions }}</span> إجراء بانتظارك
            @else
                لا توجد إجراءات معلقة حالياً 🎉
            @endif
        </p>
    </div>

    @if($totalActions === 0)
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <div class="mb-3"><i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i></div>
            <h5 class="fw-bold">أنت على اطلاع بكل شيء!</h5>
            <p class="text-muted mb-0">لا توجد إجراءات تحتاج انتباهك حالياً. سيتم إعلامك فور ورود أي طلب جديد.</p>
        </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════
         1. COMPLETION REQUESTS AWAITING RESPONSE
    ══════════════════════════════════════════════════════════════════════ --}}
    @if($pendingCompletions->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 p-4 pb-2">
            <div class="d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-success bg-opacity-10" style="width:36px;height:36px;">
                    <i class="bi bi-check2-all text-success"></i>
                </span>
                <div>
                    <h6 class="fw-bold mb-0">طلبات تأكيد إنجاز بانتظار ردك</h6>
                    <small class="text-muted">الطرف الآخر يطلب تأكيد اكتمال الطلب</small>
                </div>
                <span class="badge bg-success ms-auto">{{ $pendingCompletions->count() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($pendingCompletions as $cr)
                <a href="{{ route('dashboard.orders.show', $cr->order_id) }}" class="list-group-item list-group-item-action p-4 border-0 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $cr->order->serviceRequest->title ?? 'طلب' }}</h6>
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i>{{ $cr->requester->name }}
                                <span class="mx-2">·</span>
                                <i class="bi bi-clock me-1"></i>{{ $cr->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success-subtle text-success">بانتظار ردك</span>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════
         2. CANCELLATION REQUESTS AWAITING RESPONSE
    ══════════════════════════════════════════════════════════════════════ --}}
    @if($pendingCancellations->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 p-4 pb-2">
            <div class="d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-warning bg-opacity-10" style="width:36px;height:36px;">
                    <i class="bi bi-x-circle text-warning"></i>
                </span>
                <div>
                    <h6 class="fw-bold mb-0">طلبات إلغاء بانتظار ردك</h6>
                    <small class="text-muted">الطرف الآخر يريد إلغاء الطلب ويحتاج موافقتك</small>
                </div>
                <span class="badge bg-warning text-dark ms-auto">{{ $pendingCancellations->count() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($pendingCancellations as $cancel)
                <a href="{{ route('dashboard.orders.show', $cancel->order_id) }}" class="list-group-item list-group-item-action p-4 border-0 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $cancel->order->serviceRequest->title ?? 'طلب' }}</h6>
                            <p class="mb-1 small text-muted">السبب: {{ Str::limit($cancel->reason, 80) }}</p>
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i>{{ $cancel->requester->name }}
                                <span class="mx-2">·</span>
                                <i class="bi bi-clock me-1"></i>{{ $cancel->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning-subtle text-warning">بانتظار ردك</span>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════
         3. DISPUTES AWAITING YOUR COUNTER-REASON
    ══════════════════════════════════════════════════════════════════════ --}}
    @if($pendingDisputes->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 p-4 pb-2">
            <div class="d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger bg-opacity-10" style="width:36px;height:36px;">
                    <i class="bi bi-exclamation-triangle text-danger"></i>
                </span>
                <div>
                    <h6 class="fw-bold mb-0">نزاعات تحتاج ردك</h6>
                    <small class="text-muted">تم رفع نزاع ضدك ولم تقدم ردك بعد — مهم جداً</small>
                </div>
                <span class="badge bg-danger ms-auto">{{ $pendingDisputes->count() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($pendingDisputes as $dispute)
                <a href="{{ route('dashboard.orders.show', $dispute->order_id) }}" class="list-group-item list-group-item-action p-4 border-0 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $dispute->order->serviceRequest->title ?? 'طلب' }}</h6>
                            <p class="mb-1 small text-muted">سبب النزاع: {{ Str::limit($dispute->reason, 80) }}</p>
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i>{{ $dispute->opener->name }}
                                <span class="mx-2">·</span>
                                <i class="bi bi-clock me-1"></i>{{ $dispute->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-danger-subtle text-danger">يتطلب رد فوري</span>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════
         4. DELIVERIES TO REVIEW
    ══════════════════════════════════════════════════════════════════════ --}}
    @if($deliveriesToReview->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 p-4 pb-2">
            <div class="d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width:36px;height:36px;">
                    <i class="bi bi-cloud-check text-primary"></i>
                </span>
                <div>
                    <h6 class="fw-bold mb-0">تسليمات بانتظار مراجعتك</h6>
                    <small class="text-muted">مقدم الخدمة سلّم العمل ويحتاج مراجعتك</small>
                </div>
                <span class="badge bg-primary ms-auto">{{ $deliveriesToReview->count() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($deliveriesToReview as $order)
                <a href="{{ route('dashboard.orders.show', $order->id) }}" class="list-group-item list-group-item-action p-4 border-0 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $order->serviceRequest->title ?? 'طلب' }}</h6>
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i>{{ $order->provider->name }}
                                <span class="mx-2">·</span>
                                {{ $order->agreed_price }} {{ $order->currency_code }}
                                <span class="mx-2">·</span>
                                <i class="bi bi-clock me-1"></i>{{ $order->updated_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary-subtle text-primary">مراجعة التسليم</span>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ══════════════════════════════════════════════════════════════════════
         5. NEW OFFERS ON YOUR REQUESTS
    ══════════════════════════════════════════════════════════════════════ --}}
    @if($newOffers->count() > 0)
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 p-4 pb-2">
            <div class="d-flex align-items-center gap-2">
                <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10" style="width:36px;height:36px;">
                    <i class="bi bi-tag text-info"></i>
                </span>
                <div>
                    <h6 class="fw-bold mb-0">عروض جديدة على طلباتك</h6>
                    <small class="text-muted">مقدمو خدمات تقدموا بعروض على طلباتك</small>
                </div>
                <span class="badge bg-info text-white ms-auto">{{ $newOffers->count() }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="list-group list-group-flush">
                @foreach($newOffers as $offer)
                <a href="{{ route('dashboard.requests.show', $offer->request_id) }}" class="list-group-item list-group-item-action p-4 border-0 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $offer->serviceRequest->title ?? 'طلب' }}</h6>
                            <small class="text-muted">
                                <i class="bi bi-person me-1"></i>{{ $offer->user->name }}
                                <span class="mx-2">·</span>
                                {{ $offer->proposed_price }} {{ $offer->currency_code }}
                                <span class="mx-2">·</span>
                                <i class="bi bi-clock me-1"></i>{{ $offer->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-info-subtle text-info">عرض جديد</span>
                            <i class="bi bi-chevron-left text-muted"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

</section>
@endsection