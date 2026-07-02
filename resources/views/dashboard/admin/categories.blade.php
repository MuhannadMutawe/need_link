@extends('layouts.admin')
@section('content')

<style>
    .category-card {
        border-radius: 14px;
        border: 1px solid #e0e6ed;
        background: #fff;
        transition: all 0.2s;
    }
    .category-card:hover {
        box-shadow: 0 6px 20px rgba(0,0,0,0.07);
        transform: translateY(-2px);
    }
    .category-header {
        padding: 16px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .category-body {
        padding: 20px;
    }
    .icon-preview {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #eef2ff;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }
    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
    }
</style>

<div class="px-4 pb-5 pt-4">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-tags-fill text-primary ms-2"></i>إدارة التصنيفات</h4>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">إضافة وتعديل وحذف تصنيفات الطلبات</p>
        </div>
        <div>
            <button class="btn btn-primary fw-bold rounded-pill px-4 py-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="bi bi-plus-lg ms-1"></i> تصنيف جديد
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if (session('success'))
        <div class="alert alert-success border-0 rounded-3 mb-4 shadow-sm" style="background:#dcfce7;color:#15803d;">
            <i class="bi bi-check-circle-fill ms-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(isset($categories) && $categories->count() > 0)
        <!-- Search Box -->
        <div class="mb-4">
            <div class="input-group shadow-sm rounded-pill overflow-hidden" style="max-width: 400px; border: 1px solid #e2e8f0;">
                <span class="input-group-text bg-white border-0 text-muted ps-4 pe-3"><i class="bi bi-search"></i></span>
                <input type="text" id="categorySearch" class="form-control border-0 bg-white py-2" placeholder="ابحث عن تصنيف..." style="box-shadow: none;">
            </div>
        </div>

        <!-- Categories Table -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="categoriesTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 text-muted fw-bold" style="width: 80px;">#</th>
                            <th class="py-3 px-4 text-muted fw-bold">الأيقونة</th>
                            <th class="py-3 px-4 text-muted fw-bold">اسم التصنيف</th>
                            <th class="py-3 px-4 text-muted fw-bold text-end">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr class="category-row">
                                <td class="px-4 text-muted fw-bold">{{ $category->id }}</td>
                                <td class="px-4">
                                    <div class="icon-preview shadow-sm d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; font-size: 1.1rem;">
                                        @if($category->icon && str_starts_with(trim($category->icon), '<svg'))
                                            {!! $category->icon !!}
                                        @else
                                            <i class="bi {{ $category->icon ?: 'bi-tag-fill' }}"></i>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 fw-bold text-dark category-name">{{ $category->name }}</td>
                                <td class="px-4 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <!-- Edit Modal Trigger -->
                                        <button class="btn btn-sm btn-light border text-warning fw-bold rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                            <i class="bi bi-pencil-square ms-1"></i> تعديل
                                        </button>
                                        
                                        <!-- Delete Form -->
                                        <form action="{{ route('dashboard.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف التصنيف ({{ $category->name }})؟ سيتم حذفه نهائياً.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border-danger text-danger fw-bold rounded-pill px-3">
                                                <i class="bi bi-trash-fill ms-1"></i> حذف
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Edit Modal for this Category -->
                            <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg rounded-4">
                                        <div class="modal-header border-bottom-0 bg-light rounded-top-4 pb-2 pt-4 px-4">
                                            <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square text-warning ms-2"></i>تعديل التصنيف</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('dashboard.categories.update', $category->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold">اسم التصنيف <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control form-control-lg" value="{{ $category->name }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label fw-bold">الأيقونة</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-light text-muted"><i class="bi bi-code-slash"></i></span>
                                                        <input type="text" name="icon" class="form-control" value="{{ $category->icon }}" dir="ltr">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                                                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                                                <button type="submit" class="btn btn-warning text-dark fw-bold rounded-pill px-4 shadow-sm">
                                                    <i class="bi bi-check-lg ms-1"></i> حفظ التعديلات
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Simple Search Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('categorySearch');
                const rows = document.querySelectorAll('.category-row');
                
                if (searchInput) {
                    searchInput.addEventListener('keyup', function(e) {
                        const term = e.target.value.toLowerCase().trim();
                        rows.forEach(row => {
                            const name = row.querySelector('.category-name').textContent.toLowerCase();
                            if (name.includes(term)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    });
                }
            });
        </script>
    @else
        <div class="text-center py-5 my-5 bg-white rounded-4 shadow-sm border border-light">
            <i class="bi bi-tags text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
            <h5 class="mt-4 text-muted fw-bold">لا توجد تصنيفات مضافة</h5>
            <p class="text-muted">ابدأ بإضافة تصنيف جديد لتنظيم طلبات منصة NeedLink.</p>
            <button class="btn btn-primary fw-bold rounded-pill px-4 mt-2 shadow-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="bi bi-plus-lg ms-1"></i> إنشاء أول تصنيف
            </button>
        </div>
    @endif
</div>

<!-- Create Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-bottom-0 bg-light rounded-top-4 pb-2 pt-4 px-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-tag-fill text-primary ms-2"></i>إضافة تصنيف جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('dashboard.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-bold">اسم التصنيف <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control form-control-lg" required placeholder="مثال: تصميم جرافيك، برمجة، ترجمة...">
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">الأيقونة <span class="text-muted fw-normal">(اختياري)</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary"><i class="bi bi-star-fill"></i></span>
                            <input type="text" name="icon" class="form-control" placeholder="مثال: bi-palette" dir="ltr">
                        </div>
                        <div class="form-text mt-2 small text-muted">
                            يمكنك استخدام أيقونات <a href="https://icons.getbootstrap.com/" target="_blank" class="text-decoration-none fw-bold">Bootstrap Icons</a>. اكتب اسم الأيقونة (مثال: <code>bi-laptop</code>).
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0 px-4 pb-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm">
                        <i class="bi bi-plus-lg ms-1"></i> إضافة التصنيف
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
