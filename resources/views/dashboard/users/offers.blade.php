@extends('layout.dash')

@section('title', 'عروضي | NeedLink')

@section('content')
<style>
    .offer-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        overflow: hidden;
    }
    .offer-card:hover {
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
    }
    .empty-state {
        background-color: #f8fafc;
        border-radius: 20px;
        border: 1px dashed #cbd5e1;
    }
    .icon-box {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: #f1f5f9;
        color: #6366f1;
    }
</style>

<div class="container-fluid p-4" style="max-width: 1200px;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold mb-1" style="color: #0f172a;">عروضي</h3>
            <p class="text-muted mb-0">إدارة العروض التي قدمتها على طلبات الخدمة</p>
        </div>
        <div class="btn-group shadow-sm bg-white" role="group">
            <button type="button" class="btn btn-primary" id="btn-grid-view" onclick="toggleOffersView('grid')"><i class="bi bi-grid me-1"></i> شبكة</button>
            <button type="button" class="btn btn-outline-primary bg-white" id="btn-compact-view" onclick="toggleOffersView('compact')"><i class="bi bi-list me-1"></i> قائمة</button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm rounded-3 mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Offers List -->
    @if(isset($offers) && $offers->count() > 0)
        <!-- Grid View -->
        <div id="offers-grid-view">
            <div class="row row-cols-1 row-cols-lg-2 g-4">
            @foreach($offers as $offer)
                <div class="col">
                    <div class="offer-card h-100 d-flex flex-column">
                        <div class="p-4 border-bottom border-light flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex gap-3">
                                    <div class="icon-box">
                                        <i class="bi bi-file-earmark-text fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-truncate" style="max-width: 250px;">
                                            {{ $offer->serviceRequest ? $offer->serviceRequest->title : 'طلب محذوف' }}
                                        </h6>
                                        <small class="text-muted"><i class="bi bi-clock me-1"></i> {{ $offer->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                
                                @php
                                    $statusClass = 'bg-secondary text-white';
                                    $statusText = 'مسودة';
                                    switch($offer->status) {
                                        case 'pending': $statusClass = 'bg-warning text-dark'; $statusText = 'قيد الانتظار'; break;
                                        case 'accepted': $statusClass = 'bg-success text-white'; $statusText = 'مقبول'; break;
                                        case 'rejected': $statusClass = 'bg-danger text-white'; $statusText = 'مرفوض'; break;
                                        case 'withdrawn': $statusClass = 'bg-secondary text-white'; $statusText = 'مسحوب'; break;
                                    }
                                @endphp
                                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                            </div>

                            <p class="text-muted small mb-4" style="line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $offer->message }}
                            </p>

                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block mb-1">السعر المقترح</small>
                                        <span class="fw-bold text-success fs-5">{{ $offer->proposed_price }} {{ $offer->currency_code }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light rounded-3">
                                        <small class="text-muted d-block mb-1">مدة التنفيذ</small>
                                        <span class="fw-bold text-dark fs-5">
                                            {{ $offer->estimated_time }} 
                                            {{ $offer->time_unit === 'hours' ? 'ساعات' : ($offer->time_unit === 'days' ? 'أيام' : 'أسابيع') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-3 bg-white d-flex justify-content-between align-items-center">
                            @if($offer->expires_at)
                                <small class="text-muted"><i class="bi bi-hourglass-split me-1"></i> ينتهي في: {{ \Carbon\Carbon::parse($offer->expires_at)->format('Y-m-d') }}</small>
                            @else
                                <small class="text-muted">لا يوجد تاريخ انتهاء</small>
                            @endif
                            
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-light text-primary fw-bold" data-bs-toggle="modal" data-bs-target="#editOfferModal{{ $offer->id }}">
                                    <i class="bi bi-pencil-square"></i> تعديل
                                </button>
                                <form action="{{ route('dashboard.offers.destroy', $offer->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light text-danger fw-bold" onclick="return confirm('هل أنت متأكد من حذف هذا العرض؟');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


            @endforeach
        </div>
        </div>

        <!-- Compact View -->
        <div id="offers-compact-view" class="d-none">
            <div class="table-responsive bg-white rounded-4 border shadow-sm">
                <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 text-muted">الطلب</th>
                            <th class="py-3 text-muted">الحالة</th>
                            <th class="py-3 text-muted">السعر</th>
                            <th class="py-3 text-muted">المدة</th>
                            <th class="py-3 text-muted">الانتهاء</th>
                            <th class="pe-4 py-3 text-muted text-end">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offers as $offer)
                            @php
                                $statusClass = 'bg-secondary text-white';
                                $statusText = 'مسودة';
                                switch($offer->status) {
                                    case 'pending': $statusClass = 'bg-warning text-dark'; $statusText = 'قيد الانتظار'; break;
                                    case 'accepted': $statusClass = 'bg-success text-white'; $statusText = 'مقبول'; break;
                                    case 'rejected': $statusClass = 'bg-danger text-white'; $statusText = 'مرفوض'; break;
                                    case 'withdrawn': $statusClass = 'bg-secondary text-white'; $statusText = 'مسحوب'; break;
                                }
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="icon-box" style="width: 35px; height: 35px;"><i class="bi bi-file-earmark-text"></i></div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-truncate" style="max-width: 250px;">{{ $offer->serviceRequest ? $offer->serviceRequest->title : 'طلب محذوف' }}</h6>
                                            <small class="text-muted">{{ $offer->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="status-badge {{ $statusClass }} py-1 px-2" style="font-size: 0.75rem;">{{ $statusText }}</span></td>
                                <td><span class="fw-bold text-success">{{ $offer->proposed_price }} {{ $offer->currency_code }}</span></td>
                                <td>{{ $offer->estimated_time }} {{ $offer->time_unit === 'hours' ? 'ساعات' : ($offer->time_unit === 'days' ? 'أيام' : 'أسابيع') }}</td>
                                <td>
                                    @if($offer->expires_at)
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($offer->expires_at)->format('Y-m-d') }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button type="button" class="btn btn-sm btn-light text-primary" data-bs-toggle="modal" data-bs-target="#editOfferModal{{ $offer->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('dashboard.offers.destroy', $offer->id) }}" method="POST" class="m-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light text-danger" onclick="return confirm('هل أنت متأكد من حذف هذا العرض؟');">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Render Modals for Both Views -->
        @foreach($offers as $offer)
            <!-- Edit Offer Modal -->
            <div class="modal fade" id="editOfferModal{{ $offer->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                        <div class="modal-header border-0 pb-0 pt-4 px-4">
                            <h5 class="fw-bold mb-0">تعديل العرض</h5>
                            <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-4">
                            <form action="{{ route('dashboard.offers.update', $offer->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold">رسالة العرض</label>
                                    <textarea name="message" class="form-control" rows="4" required>{{ $offer->message }}</textarea>
                                </div>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">السعر المقترح</label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" name="proposed_price" class="form-control" value="{{ $offer->proposed_price }}" required>
                                            <input type="text" name="currency_code" class="form-control" style="max-width: 80px;" value="{{ $offer->currency_code }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted small fw-bold">مدة التنفيذ</label>
                                        <div class="input-group">
                                            <input type="number" name="estimated_time" min="1" class="form-control" value="{{ $offer->estimated_time }}" required>
                                            <select name="time_unit" class="form-select" style="max-width: 120px;" required>
                                                <option value="hours" {{ $offer->time_unit == 'hours' ? 'selected' : '' }}>ساعات</option>
                                                <option value="days" {{ $offer->time_unit == 'days' ? 'selected' : '' }}>أيام</option>
                                                <option value="weeks" {{ $offer->time_unit == 'weeks' ? 'selected' : '' }}>أسابيع</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label text-muted small fw-bold">تاريخ الانتهاء (اختياري)</label>
                                    <input type="datetime-local" name="expires_at" class="form-control" value="{{ $offer->expires_at ? \Carbon\Carbon::parse($offer->expires_at)->format('Y-m-d\TH:i') : '' }}">
                                </div>
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4">حفظ التعديلات</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    @else
        <div class="text-center p-5 empty-state mt-4">
            <i class="bi bi-journal-x fs-1 mb-3 d-block text-muted" style="opacity: 0.5;"></i>
            <h5 class="fw-bold mb-2">لا توجد عروض</h5>
            <p class="text-muted mb-4">لم تقم بتقديم أي عروض حتى الآن. ابدأ بتصفح الطلبات وتقديم عروضك.</p>
            <a href="{{ route('dashboard.requests.index') }}" class="btn btn-primary rounded-pill px-4">تصفح الطلبات</a>
        </div>
    @endif
</div>

<!-- Create Offer Modal -->
<div class="modal fade" id="createOfferModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">تقديم عرض جديد</h5>
                <button type="button" class="btn-close m-0" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('dashboard.offers.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">اختر الطلب</label>
                        <select name="request_id" class="form-select request-select" required>
                            <option value="">-- اختر طلب --</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">رسالة العرض</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="اشرح للمشتري لماذا أنت الأنسب لهذا العمل..." required></textarea>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">السعر المقترح</label>
                            <div class="input-group">
                                <input type="number" step="0.01" name="proposed_price" class="form-control" placeholder="0.00" required>
                                <input type="text" name="currency_code" class="form-control" style="max-width: 80px;" placeholder="USD" value="USD" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold">مدة التنفيذ</label>
                            <div class="input-group">
                                <input type="number" name="estimated_time" min="1" class="form-control" placeholder="مثال: 3" required>
                                <select name="time_unit" class="form-select" style="max-width: 120px;" required>
                                    <option value="hours">ساعات</option>
                                    <option value="days" selected>أيام</option>
                                    <option value="weeks">أسابيع</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">تاريخ الانتهاء (اختياري)</label>
                        <input type="datetime-local" name="expires_at" class="form-control">
                        <small class="text-muted d-block mt-1">متى تنتهي صلاحية هذا العرض</small>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">إرسال العرض</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", async () => {
        // Restore view preference
        const savedView = localStorage.getItem('offersViewPreference');
        if (savedView === 'compact') {
            toggleOffersView('compact');
        }

        try {
            const response = await fetch('{{ route("dashboard.requests.index") }}', {
                headers: { Accept: 'application/json' }
            });
            const requests = await response.json();
            const selects = document.querySelectorAll('.request-select');

            selects.forEach(select => {
                requests.forEach(req => {
                    if(req.status === 'open') {
                        const option = document.createElement('option');
                        option.value = req.id;
                        option.textContent = req.title;
                        select.appendChild(option);
                    }
                });
            });
        } catch (error) {
            console.error('Error fetching requests:', error);
        }
    });

    function toggleOffersView(view) {
        localStorage.setItem('offersViewPreference', view);
        
        const gridView = document.getElementById('offers-grid-view');
        const compactView = document.getElementById('offers-compact-view');
        const btnGrid = document.getElementById('btn-grid-view');
        const btnCompact = document.getElementById('btn-compact-view');

        if (view === 'compact') {
            gridView.classList.add('d-none');
            compactView.classList.remove('d-none');
            
            btnCompact.classList.add('btn-primary');
            btnCompact.classList.remove('btn-outline-primary', 'bg-white');
            
            btnGrid.classList.remove('btn-primary');
            btnGrid.classList.add('btn-outline-primary', 'bg-white');
        } else {
            compactView.classList.add('d-none');
            gridView.classList.remove('d-none');
            
            btnGrid.classList.add('btn-primary');
            btnGrid.classList.remove('btn-outline-primary', 'bg-white');
            
            btnCompact.classList.remove('btn-primary');
            btnCompact.classList.add('btn-outline-primary', 'bg-white');
        }
    }
</script>
@endsection
