<!-- Request Modal -->
<div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
    
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg"
         style="max-width: 750px !important;">

        <div class="modal-content"
             style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-primary" id="requestModalLabel">
                    <i class="bi bi-pencil-square ms-2"></i>
                    <span id="modalTitleText">إنشاء طلب جديد</span>
                </h5>

                <button type="button" class="btn-close m-0"
                        data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 d-flex justify-content-center">

                <form id="createRequestForm"
                      class="w-100"
                      style="max-width: 750px;"
                      action="{{ route('dashboard.requests.store') }}"
                      method="POST"
                      enctype="multipart/form-data">

                    @csrf
                    <input type="hidden" name="_method" id="requestMethod" value="POST">
                    <input type="hidden" name="status" id="requestStatus" value="open">

                    <div class="row g-4">

                        {{-- Title --}}
                        <div class="col-12">
                            <label class="form-label">عنوان الطلب <span class="text-danger">*</span></label>
                            <input class="form-control title-input" type="text" name="title" 
                                   placeholder="اكتب عنواناً يصف طلبك بدقة" dir="auto"
                                   style="font-size: 1.2rem !important;" required>
                        </div>

                        {{-- Description --}}
                        <div class="col-12">
                            <label class="form-label">تفاصيل الطلب <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="4" dir="auto"
                                      placeholder="اشرح تفاصيل ومتطلبات الخدمة التي تحتاجها"
                                      required></textarea>
                        </div>

                        {{-- Image Upload --}}
                        <div class="col-12">
                            <label class="form-label">صورة توضيحية <span class="text-muted">(اختياري)</span></label>
                            <input class="form-control" type="file" name="image" accept="image/*"
                                   style="font-size: 1rem !important;">
                            <small class="text-muted d-block mt-1">
                                يمكنك إرفاق صورة توضح فكرة طلبك بشكل أفضل.
                            </small>
                        </div>

                        {{-- Categories --}}
                        <div class="col-12">
                            <label class="form-label">الفئات <span class="text-danger">*</span></label>

                            <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">

                            <style>
                                
                                .ts-control {
                                    padding: 10px 15px !important;
                                    min-height: 45px;
                                    display: flex;
                                    align-items: center;
                                    flex-wrap: wrap;
                                    gap: 5px;
                                }
                                .cat-icon-wrap, .cat-icon-wrap-sm {
                                    margin-left: 8px; /* RTL spacing */
                                    color: #6c757d;
                                }
                            </style>

                            <select id="category-select"
                                    class="form-control"
                                    name="categories[]"
                                    multiple
                                    autocomplete="off">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" data-icon="{{ $category->icon ?? 'bi-tag' }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pricing Type --}}
                        <div class="col-12">
                            <label class="form-label">نوع التسعير <span class="text-danger">*</span></label>

                            <div class="pricing-pills">
                                <input type="radio" name="pricing_type" id="price_fixed"
                                       value="fixed" checked onchange="toggleBudget()">
                                <label for="price_fixed">سعر ثابت</label>

                                <input type="radio" name="pricing_type" id="price_hourly"
                                       value="hourly" onchange="toggleBudget()">
                                <label for="price_hourly">بالساعة</label>

                                <input type="radio" name="pricing_type" id="price_negotiable"
                                       value="negotiable" onchange="toggleBudget()">
                                <label for="price_negotiable">قابل للتفاوض</label>
                            </div>
                        </div>

                        {{-- Budget --}}
                        <div class="col-md-6 budget-group">
                            <label class="form-label">الميزانية المقترحة</label>

                            <div class="input-group">
                                <input type="number" step="0.01" name="budget" style="font-size: 1.2rem !important;"
                                       class="form-control" placeholder="أدخل المبلغ">

                                <span class="input-group-text bg-light">
                                    <input type="text" name="currency_code"
                                           value="USD"
                                           class="form-control form-control-sm border-0 bg-transparent text-center"
                                           style="width: 50px; padding: 0; font-weight: 700;"
                                           maxlength="3">
                                </span>
                            </div>

                            <small class="text-muted d-block mt-1">
                                اترك الحقل فارغاً إذا لم تكن متأكداً.
                            </small>
                        </div>

                        {{-- Expiry --}}
                        <div class="col-md-6">
                            <label class="form-label">تاريخ الانتهاء <span class="text-muted">(اختياري)</span></label>
                            <input type="date" name="expires_at" class="form-control">
                            <small class="text-muted d-block mt-1">
                                تاريخ إغلاق استقبال العروض.
                            </small>
                        </div>

                    </div>

                    <hr class="my-4">

                    {{-- Buttons --}}
                    <div class="d-flex gap-3 justify-content-end">

                        <button type="button"
                                class="btn btn-primary px-4 fw-bold"
                                style="border-radius: 999px;"
                                onclick="submitForm('open')">
                            نشر
                        </button>

                        <button type="button"
                                class="btn btn-outline-secondary px-4 fw-bold"
                                style="border-radius: 999px;"
                                onclick="submitForm('draft')">
                            حفظ كمسودة
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
    let tomSelectInstance;
    let requestModalInstance;

    document.addEventListener('DOMContentLoaded', function() {
        toggleBudget();
        
        tomSelectInstance = new TomSelect("#category-select", {
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

        requestModalInstance = new bootstrap.Modal(document.getElementById('requestModal'));
    });

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

    function openRequestModal(requestData = null) {
        const form = document.getElementById('createRequestForm');
        const modalTitleText = document.getElementById('modalTitleText');
        const methodInput = document.getElementById('requestMethod');
        
        // Reset form
        form.reset();
        tomSelectInstance.clear();
        
        if (requestData) {
            // Update mode
            modalTitleText.textContent = 'تحديث الطلب';
            form.action = `/dashboard/requests/${requestData.id}`;
            methodInput.value = 'PUT';
            
            // Populate fields
            form.title.value = requestData.title || '';
            form.description.value = requestData.description || '';
            
            if (requestData.expires_at) {
                form.expires_at.value = requestData.expires_at.split('T')[0];
            }
            
            if (requestData.budget) form.budget.value = requestData.budget;
            if (requestData.currency_code) form.currency_code.value = requestData.currency_code;
            
            // Pricing type
            if (requestData.pricing_type) {
                const radio = document.getElementById(`price_${requestData.pricing_type}`);
                if(radio) radio.checked = true;
            }
            
            // Categories
            if (requestData.categories) {
                const catIds = requestData.categories.map(c => c.id.toString());
                tomSelectInstance.setValue(catIds);
            }
            
        } else {
            // Create mode
            modalTitleText.textContent = 'إنشاء طلب جديد';
            form.action = "{{ route('dashboard.requests.store') }}";
            methodInput.value = 'POST';
            document.getElementById('price_fixed').checked = true;
        }
        
        toggleBudget();
        requestModalInstance.show();
        document.getElementById('requestModal').addEventListener('shown.bs.modal', function handler() {
            document.querySelector('.title-input').focus();
            this.removeEventListener('shown.bs.modal', handler);
        }, { once: true });
    }

    async function submitForm(status) {
        const form = document.getElementById('createRequestForm');
        document.getElementById('requestStatus').value = status;

        const formData = new FormData(form);
        const submitBtns = document.querySelectorAll('.submit-buttons-container button');

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
                method: 'POST', // always POST for fetch, _method handles PUT
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (response.ok) {
                notyf.success(data.message || 'تم حفظ الطلب بنجاح');
                
                requestModalInstance.hide();
                setTimeout(() => {
                    window.location.reload();
                }, 800);
            } else {
                let errorMsg = data.message || 'حدث خطأ أثناء الحفظ';
                if (data.errors) {
                    errorMsg = Object.values(data.errors)[0][0];
                }
                notyf.error(errorMsg);
                
                submitBtns.forEach(btn => {
                    btn.innerHTML = btn.dataset.originalText;
                    btn.disabled = false;
                });
            }
        } catch (error) {
            console.error('Error during fetch:', error);
            notyf.error('حدث خطأ في الاتصال بالخادم.');
            
            submitBtns.forEach(btn => {
                btn.innerHTML = btn.dataset.originalText;
                btn.disabled = false;
            });
        }
    }
</script>
