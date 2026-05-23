@extends('layout.dash')
@section('title')
    NeedLink - اللوحة الرئيسية
@endsection
@section('sub-title')
    الرئيسية
@endsection
@section('message')
    مرحبا بك في الرئيسية تابع اخر نشاطاتك
@endsection
@section('content')

        <section class="p-4">

                    <div class="row g-4">

                        <!-- Activities Table -->
                        <div class="col-lg-12">
                            <div class="card border-0 shadow-sm rounded-4 h-100">
                                <div class="card-header bg-white border-0 p-4">
                                    <div class="d-flex justify-content-between align-items-center gap-3">
                                        <div>
                                            <h5 class="fw-bold mb-1">آخر النشاطات</h5>
                                            <p class="text-muted mb-0 small">أحدث العمليات التي حدثت داخل المنصة</p>
                                        </div>
                                        <a href="#" class="btn btn-sm btn-outline-primary">عرض الكل</a>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>النشاط</th>
                                                <th>المستخدم</th>
                                                <th>التاريخ</th>
                                                <th>الحالة</th>
                                                <th>إجراء</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>
                                                    <i class="bi bi-flag-fill text-danger ms-2"></i>
                                                    خدمة مبلّغ عنها
                                                </td>
                                                <td>مستخدم #52</td>
                                                <td>اليوم</td>
                                                <td><span class="badge bg-danger-subtle text-danger">مراجعة</span></td>
                                                <td><button class="btn btn-sm btn-dark">فتح</button></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <i class="bi bi-person-plus-fill text-orange ms-2"></i>
                                                    مزود خدمة جديد
                                                </td>
                                                <td>مستخدم #87</td>
                                                <td>اليوم</td>
                                                <td><span class="badge bg-orange-soft text-orange">بانتظار
                                                        الموافقة</span></td>
                                                <td><button class="btn btn-sm btn-dark">مراجعة</button></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <i class="bi bi-check-circle-fill text-success ms-2"></i>
                                                    تم قبول طلب تحقق
                                                </td>
                                                <td>مستخدم #31</td>
                                                <td>أمس</td>
                                                <td><span class="badge bg-success-subtle text-success">مكتمل</span></td>
                                                <td><button class="btn btn-sm btn-dark">تفاصيل</button></td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <i class="bi bi-megaphone-fill text-blue ms-2"></i>
                                                    إشعار صيانة
                                                </td>
                                                <td>جميع المستخدمين</td>
                                                <td>غدًا</td>
                                                <td><span class="badge bg-blue-soft text-blue">مجدول</span></td>
                                                <td><button class="btn btn-sm btn-dark">تعديل</button></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Bottom Cards -->
                    <div class="row g-4 mt-1">

                        <div class="col-lg-12">
                            <div class="card border-0 shadow-sm rounded-4">
                                <div class="card-header bg-white border-0 p-4">
                                    <h5 class="fw-bold mb-1">أحدث المستخدمين</h5>
                                    <p class="text-muted mb-0 small">آخر حسابات تم إنشاؤها</p>
                                </div>

                                <div class="card-body pt-0">

                                    <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                                        <div class="admin-avatar bg-blue">م</div>
                                        <div>
                                            <h6 class="fw-bold mb-1">محمد أحمد</h6>
                                            <small class="text-muted">مزود خدمة - تصميم</small>
                                        </div>
                                        <span class="badge bg-success-subtle text-success me-auto">نشط</span>
                                    </div>

                                    <div class="d-flex align-items-center gap-3 py-3 border-bottom">
                                        <div class="admin-avatar bg-orange">س</div>
                                        <div>
                                            <h6 class="fw-bold mb-1">سارة خالد</h6>
                                            <small class="text-muted">طالبة - خدمات أكاديمية</small>
                                        </div>
                                        <span class="badge bg-orange-soft text-orange me-auto">مراجعة</span>
                                    </div>

                                    <div class="d-flex align-items-center gap-3 py-3">
                                        <div class="admin-avatar bg-dark">أ</div>
                                        <div>
                                            <h6 class="fw-bold mb-1">أحمد علي</h6>
                                            <small class="text-muted">مستخدم جديد</small>
                                        </div>
                                        <span class="badge bg-blue-soft text-blue me-auto">جديد</span>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                </section>

@endsection