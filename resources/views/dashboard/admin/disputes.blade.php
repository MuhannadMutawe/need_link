@extends('layout.adminDash')
@section('content')

<style>
    .dispute-card {
        border-radius: 14px;
        border: 1px solid #e0e6ed;
        background: #fff;
        margin-bottom: 16px;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .dispute-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.07); }
    .dispute-header {
        padding: 14px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }
    .dispute-body { padding: 16px 20px; }
    .status-badge {
        font-size: 0.78rem; font-weight: 700; padding: 4px 14px;
        border-radius: 999px; letter-spacing: 0.3px;
    }
    .badge-open     { background: #fee2e2; color: #b91c1c; }
    .badge-resolved { background: #dcfce7; color: #15803d; }
    .party-chip {
        display: inline-flex; align-items: center; gap: 6px;
        background: #f1f5f9; border-radius: 999px;
        padding: 4px 12px 4px 6px; font-size: 0.82rem; font-weight: 600; color: #1e293b;
    }
    .party-avatar {
        width: 26px; height: 26px; border-radius: 50%;
        background: #4f46e5; color: #fff;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.7rem; font-weight: 700;
    }
</style>

<div class="px-4 pb-5 pt-4">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-shield-exclamation text-danger ms-2"></i>إدارة النزاعات</h4>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">مراجعة وحل النزاعات المفتوحة بين المستخدمين</p>
        </div>
        <div class="d-flex gap-3">
            <div class="text-center px-4 py-2 rounded-3" style="background:#fee2e2;">
                <div class="fw-bold text-danger" style="font-size: 1.4rem;">{{ $openCount }}</div>
                <small class="text-danger">مفتوح</small>
            </div>
            <div class="text-center px-4 py-2 rounded-3" style="background:#dcfce7;">
                <div class="fw-bold text-success" style="font-size: 1.4rem;">{{ $resolvedCount }}</div>
                <small class="text-success">محلول</small>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success rounded-3 border-0 mb-4" style="background:#dcfce7;color:#15803d;">
            <i class="bi bi-check-circle ms-2"></i> {{ session('success') }}
        </div>
    @endif

    @forelse($disputes as $dispute)
        <div class="dispute-card">
            <div class="dispute-header">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <span class="status-badge {{ $dispute->status === 'open' ? 'badge-open' : 'badge-resolved' }}">
                        <i class="bi {{ $dispute->status === 'open' ? 'bi-exclamation-circle' : 'bi-check-circle' }} me-1"></i>
                        {{ $dispute->status === 'open' ? 'مفتوح' : 'محلول' }}
                    </span>
                    <span class="text-muted" style="font-size: 0.82rem;">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ $dispute->created_at->diffForHumans() }}
                    </span>
                    @if($dispute->order->serviceRequest)
                        <a href="{{ route('dashboard.requests.show', $dispute->order->serviceRequest->id) }}"
                           class="text-primary text-decoration-none" style="font-size: 0.85rem;">
                            <i class="bi bi-box-arrow-up-left me-1"></i>
                            {{ Str::limit($dispute->order->serviceRequest->title, 40) }}
                        </a>
                    @endif
                </div>

                {{-- Parties --}}
                <div class="d-flex gap-2 flex-wrap">
                    <span class="party-chip">
                        <span class="party-avatar" style="background:#4f46e5;">
                            {{ strtoupper(substr($dispute->order->client->name ?? 'C', 0, 2)) }}
                        </span>
                        {{ $dispute->order->client->name ?? 'عميل' }}
                        <small class="text-muted">(عميل)</small>
                    </span>
                    <span class="party-chip">
                        <span class="party-avatar" style="background:#0f766e;">
                            {{ strtoupper(substr($dispute->order->provider->name ?? 'P', 0, 2)) }}
                        </span>
                        {{ $dispute->order->provider->name ?? 'مزود' }}
                        <small class="text-muted">(مزود)</small>
                    </span>
                </div>
            </div>

            <div class="dispute-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border-start border-danger border-3 h-100">
                            <span class="fw-bold text-danger d-block mb-1"><i class="bi bi-chat-quote-fill me-1"></i>سبب النزاع:</span>
                            <span style="font-size: 0.95rem; color: #334155; line-height: 1.7;" dir="auto">{{ $dispute->reason }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 bg-light rounded border-start border-warning border-3 h-100">
                            <span class="fw-bold text-dark d-block mb-1"><i class="bi bi-chat-left-quote-fill me-1 text-warning"></i>رد الطرف الآخر:</span>
                            @if($dispute->counter_reason)
                                <span style="font-size: 0.95rem; color: #334155; line-height: 1.7;" dir="auto">{{ $dispute->counter_reason }}</span>
                            @else
                                <span class="text-muted fst-italic" style="font-size: 0.9rem;">لم يتم تقديم رد حتى الآن.</span>
                            @endif
                        </div>
                    </div>
                </div>

                @if($dispute->resolution_note)
                    <div class="p-3 rounded-3 mb-3" style="background:#f0fdf4; border-left: 4px solid #22c55e;">
                        <p class="mb-0 text-success" style="font-size: 0.9rem;" dir="auto">
                            <i class="bi bi-check2-circle me-1"></i>
                            <strong>القرار:</strong> {{ $dispute->resolution_note }}
                        </p>
                        @if($dispute->resolver)
                            <small class="text-muted mt-1 d-block">
                                <i class="bi bi-person-check me-1"></i>بواسطة: {{ $dispute->resolver->name }}
                                &nbsp;·&nbsp; {{ $dispute->resolved_at?->diffForHumans() }}
                            </small>
                        @endif
                    </div>
                @endif

                @if($dispute->status === 'open')
                    <form action="{{ route('dashboard.disputes.resolve', $dispute->id) }}" method="POST" class="d-flex gap-2 align-items-end flex-wrap">
                        @csrf
                        <div style="flex: 1; min-width: 220px;">
                            <label class="form-label text-muted" style="font-size: 0.82rem;">قرار الحل <span class="text-danger">*</span></label>
                            <textarea name="resolution_note" class="form-control form-control-sm" rows="2"
                                      placeholder="اكتب قرار الحل (10 أحرف على الأقل)..." dir="auto" required minlength="10"></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-success px-4 fw-bold rounded-pill mb-1">
                            <i class="bi bi-check-lg me-1"></i> حل النزاع
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-shield-check" style="font-size: 3rem; color: #22c55e;"></i>
            <h5 class="mt-3 text-muted">لا توجد نزاعات مفتوحة</h5>
            <p class="text-muted">جميع الطلبات تسير بسلاسة.</p>
        </div>
    @endforelse

    <div class="mt-4">
        {{ $disputes->links() }}
    </div>

</div>

@endsection
