@extends('layout.dash')
@section('title')
    NeedLink - إنشاء طلب جديد
@endsection
@section('content')

    {{-- Page Header --}}
    <div class="req-page-header mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('dashboard.requests.index') }}" class="btn btn-sm btn-light" style="border-radius: 999px;">
                <i class="bi bi-arrow-right"></i> عودة
            </a>
            <div>
                <h4 class="mb-1"><i class="bi bi-plus-circle-fill text-primary ms-2"></i>إنشاء طلب جديد</h4>
                <p class="text-muted mb-0">أدخل تفاصيل طلبك ليتمكن المستقلون من تقديم عروضهم</p>
            </div>
        </div>
    </div>

    {{-- Form Section --}}
    <section class="px-4 pb-5">
        <div class="create-form-card">
            
            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius: 12px;">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="createRequestForm" action="{{ route('dashboard.requests.store') }}" method="POST">
                @csrf
                <input type="hidden" name="status" id="requestStatus" value="open">

                <div class="row g-4">
                    {{-- Title --}}
                    <div class="col-12">
                        <label class="form-label">عنوان الطلب <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="اكتب عنواناً يصف طلبك بدقة" required>
                    </div>

                    {{-- Description --}}
                    <div class="col-12">
                        <label class="form-label">تفاصيل الطلب <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="4" placeholder="اشرح تفاصيل ومتطلبات الخدمة التي تحتاجها" required>{{ old('description') }}</textarea>
                    </div>

                    {{-- Categories --}}
                    <div class="col-12">
                        <label class="form-label">الفئات <span class="text-danger">*</span></label>
                        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
                        <select id="category-select" name="categories[]" multiple placeholder="اختر الفئات المناسبة..." autocomplete="off">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-icon="{{ $category->icon ?? 'bi-tag' }}" {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pricing Type --}}
                    <div class="col-md-12">
                        <label class="form-label">نوع التسعير <span class="text-danger">*</span></label>
                        <div class="pricing-pills">
                            <input type="radio" name="pricing_type" id="price_fixed" value="fixed" {{ old('pricing_type', 'fixed') == 'fixed' ? 'checked' : '' }} onchange="toggleBudget()">
                            <label for="price_fixed">سعر ثابت</label>

                            <input type="radio" name="pricing_type" id="price_hourly" value="hourly" {{ old('pricing_type') == 'hourly' ? 'checked' : '' }} onchange="toggleBudget()">
                            <label for="price_hourly">بالساعة</label>

                            <input type="radio" name="pricing_type" id="price_negotiable" value="negotiable" {{ old('pricing_type') == 'negotiable' ? 'checked' : '' }} onchange="toggleBudget()">
                            <label for="price_negotiable">قابل للتفاوض</label>
                        </div>
                    </div>

                    {{-- Budget & Currency (Hidden if negotiable) --}}
                    <div class="col-md-6 budget-group">
                        <label class="form-label">الميزانية المقترحة</label>
                        <div class="input-group">
                            <input type="number" step="0.01" name="budget" class="form-control" value="{{ old('budget') }}" placeholder="أدخل المبلغ">
                            <span class="input-group-text bg-light">
                                <input type="text" name="currency_code" value="{{ old('currency_code', 'USD') }}" class="form-control form-control-sm border-0 bg-transparent text-center" style="width: 50px; padding:0; font-weight:700;" maxlength="3">
                            </span>
                        </div>
                        <small class="text-muted mt-1 d-block">اترك الحقل فارغاً إذا لم تكن متأكداً.</small>
                    </div>

                    {{-- Expires At --}}
                    <div class="col-md-6">
                        <label class="form-label">تاريخ الانتهاء <span class="text-muted">(اختياري)</span></label>
                        <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at') }}">
                        <small class="text-muted mt-1 d-block">تاريخ إغلاق استقبال العروض.</small>
                    </div>

                </div>

                <hr class="my-4">

                {{-- Submit Buttons --}}
                <div class="d-flex align-items-center gap-3">
                    <button type="button" class="btn btn-primary px-4 py-2 fw-bold" style="border-radius: 999px;" onclick="submitForm('open')">
                        <i class="bi bi-check2-circle ms-1"></i> نشر
                    </button>
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 fw-bold" style="border-radius: 999px;" onclick="submitForm('draft')">
                        <i class="bi bi-save ms-1"></i> حفظ كمسودة
                    </button>
                </div>
            </form>
        </div>
    </section>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    function toggleBudget() {
        const isNegotiable = document.getElementById('price_negotiable').checked;
        const budgetGroup = document.querySelector('.budget-group');
        
        if (isNegotiable) {
            budgetGroup.style.opacity = '0.5';
            budgetGroup.querySelector('input[name="budget"]').disabled = true;
            budgetGroup.querySelector('input[name="currency_code"]').disabled = true;
        } else {
            budgetGroup.style.opacity = '1';
            budgetGroup.querySelector('input[name="budget"]').disabled = false;
            budgetGroup.querySelector('input[name="currency_code"]').disabled = false;
        }
    }

    async function submitForm(status) {
        const form = document.getElementById('createRequestForm');
        document.getElementById('requestStatus').value = status;
        
        const formData = new FormData(form);
        const submitBtns = document.querySelectorAll('.d-flex.align-items-center.gap-3 button');
        
        // Disable buttons and show loading state
        submitBtns.forEach(btn => {
            if (!btn.dataset.originalText) {
                btn.dataset.originalText = btn.innerHTML;
            }
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> جاري الحفظ...';
            btn.disabled = true;
        });

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (response.ok) {
                notyf.success(data.message || 'تم حفظ الطلب بنجاح');
                
                // Redirect to the requests list after a short delay
                setTimeout(() => {
                    window.location.href = "{{ route('dashboard.requests.index') }}";
                }, 1000);
            } else {
                // Handle validation errors (422) or server errors
                let errorMsg = data.message || 'حدث خطأ أثناء الحفظ';
                if (data.errors) {
                    errorMsg = Object.values(data.errors)[0][0]; // Get the first validation error
                }
                
                notyf.error(errorMsg);
                
                // Restore buttons
                submitBtns.forEach(btn => {
                    btn.innerHTML = btn.dataset.originalText;
                    btn.disabled = false;
                });
            }
        } catch (error) {
            console.error('Error during fetch:', error);
            notyf.error('حدث خطأ في الاتصال بالخادم.');
            
            // Restore buttons
            submitBtns.forEach(btn => {
                btn.innerHTML = btn.dataset.originalText;
                btn.disabled = false;
            });
        }
    }

    // Run on load to set initial state and initialize TomSelect
    document.addEventListener('DOMContentLoaded', function() {
        toggleBudget();
        
        new TomSelect("#category-select", {
            plugins: ['remove_button'],
            create: false,
            sortField: { field: "text", direction: "asc" },
            render: {
                item: function(data, escape) {
                    return '<div class="d-flex align-items-center"><div class="cat-icon-wrap-sm"><i class="bi ' + escape(data.icon) + '"></i></div><span>' + escape(data.text) + '</span></div>';
                },
                option: function(data, escape) {
                    return '<div class="d-flex align-items-center"><div class="cat-icon-wrap"><i class="bi ' + escape(data.icon) + '"></i></div><span class="fw-bold" style="font-size: 0.95rem;">' + escape(data.text) + '</span></div>';
                }
            }
        });
    });
</script>
@endsection
