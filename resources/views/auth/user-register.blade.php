@extends('layout.master')
@section('title')
  NeedLink - انشاء حساب
@endsection
@section('css')
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection
@section('content')
  <main class="register-page">
    <div class="container">
      <div class="register-shell">
        <div class="row g-0">
          <div class="col-lg-5">
            <section class="register-brand">
              <span class="brand-badge">NeedLink • إنشاء حساب</span>
              <h1>أنشئ حسابك بشكل سريع و أنيق </h1>
              <br>
              <div class="brand-step">
                <strong>1) معلومات الحساب</strong>
                <span>الاسم، اسم المستخدم، البريد الإلكتروني، رقم الجوال</span>
              </div>

              <div class="brand-step">
                <strong>2) تأمين الحساب</strong>
                <span>كلمة المرور وتأكيدها</span>
              </div>

              <div class="brand-step">
                <strong>3) مراجعة نهائية</strong>
                <span>تأكد من كل البيانات قبل إنشاء الحساب</span>
              </div>
            </section>
          </div>

          <div class="col-lg-7">
            <section class="form-side">
              <h2 class="section-title">إنشاء حساب جديد</h2>
              <p class="section-subtitle">اتبع الخطوات التالية</p>

              <div class="progress-wrap">
                <div class="progress-steps">
                  <div class="progress-step active" data-indicator="1">الخطوة الأولى</div>
                  <div class="progress-step" data-indicator="2">الخطوة الثانية</div>
                  <div class="progress-step" data-indicator="3">المراجعة</div>
                </div>

                <div class="progress">
                  <div class="progress-bar" id="progressBar" style="width:33%"></div>
                </div>
              </div>

              <form id="registerForm" action="{{ route('auth.register.submit') }}" method="post">
                <!-- Step 1 -->
                <div class="step-panel active" data-step="1">
                  <div class="row g-3">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="col-md-6">
                      <label class="form-label" for="name">الاسم الكامل</label>
                      <input type="text" class="form-control" name="name" id="name" placeholder="أدخل الاسم الكامل">
                    </div>

                    <div class="col-md-6">
                      <label class="form-label" for="username">اسم المستخدم</label>
                      <input type="text" class="form-control" name="username" id="username"
                        placeholder="مثال: abu_alhassan">
                    </div>

                    <div class="col-md-6">
                      <label class="form-label" for="email">البريد الإلكتروني</label>
                      <input type="email" class="form-control" name="email" id="email" placeholder="name@example.com">
                    </div>

                    <div class="col-md-6">
                      <label class="form-label" for="phone">رقم الجوال</label>
                      <input type="text" class="form-control" name="phone" id="phone" placeholder="0590000000">
                    </div>
                  </div>

                  <div class="text-center mt-4">
                    <a href="{{ route('auth.login') }}" class="text-decoration-underline text-primary"><i
                        class="bi bi-person" style="font-size: 20px;"></i>أنا أملك حساب بالفعل</a>
                  </div>

                </div>


                <!-- Step 2 -->
                <div class="step-panel" data-step="2">
                  <div class="row g-3">

                    <div class="col-md-6">
                      <label class="form-label" for="password">كلمة المرور</label>

                      <div class="input-group">
                        <input type="password" class="form-control" name="password" id="password">

                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                          <i class="bi bi-eye"></i>
                        </button>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <label class="form-label" for="password_confirmation">تأكيد كلمة المرور</label>

                      <div class="input-group">
                        <input type="password" class="form-control" name="password_confirmation"
                          id="password_confirmation">

                        <button class="btn btn-outline-secondary toggle-password" type="button"
                          data-target="password_confirmation">
                          <i class="bi bi-eye"></i>
                        </button>
                      </div>
                    </div>

                  </div>
                </div>

                <!-- Step 3: Review -->
                <div class="step-panel" data-step="3">
                  <div class="summary-box">
                    <div class="summary-row">
                      <span class="summary-label">الاسم</span>
                      <span class="summary-value" id="summary_name">-</span>
                    </div>

                    <div class="summary-row">
                      <span class="summary-label">اسم المستخدم</span>
                      <span class="summary-value" id="summary_username">-</span>
                    </div>

                    <div class="summary-row">
                      <span class="summary-label">البريد الإلكتروني</span>
                      <span class="summary-value" id="summary_email">-</span>
                    </div>

                    <div class="summary-row">
                      <span class="summary-label">رقم الجوال</span>
                      <span class="summary-value" id="summary_phone">-</span>
                    </div>
                  </div>
                </div>

                <div class="step-actions">
                  <button type="button" class="btn btn-light" id="prevBtn" style="display:none;">السابق</button>
                  <button type="button" class="btn btn-blue ms-auto" id="nextBtn">التالي</button>
                  <button type="submit" class="btn btn-main-custom" id="submitBtn" style="display:none;">إنشاء
                    الحساب</button>
                </div>
              </form>
            </section>
          </div>
        </div>
      </div>
    </div>
  </main>
@endsection
@section('script')
  <script>
    $(document).ready(function () {
      $('#registerForm').on('submit', function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
          url: $(this).attr('action'),
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function (response) {
            if (response.success) {
              window.location.href = response.redirect;
            } else {
              notyf.error(response.message || 'حدث خطأ غير متوقع');
            }
          },
          error: function (xhr) {
            if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
              let errors = xhr.responseJSON.errors;
              $.each(errors, function (key, value) {
                notyf.error(value[0]);
              });
            } else {
              notyf.error('حدث خطأ غير متوقع، يرجى المحاولة لاحقاً');
            }
          },
        });
      });
    });
  </script>
@endsection