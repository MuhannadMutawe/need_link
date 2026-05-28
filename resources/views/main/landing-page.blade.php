@extends('layout.master')
@section('title')
  NeedLink - الرئيسية
@endsection
@section('css')
  <link rel="stylesheet" href="{{ asset('css/landing-page.css') }}">
@endsection
@section('content')
  <!-- Hero -->
  <header class="hero-section py-5">
    <div class="container py-lg-5">
      <div class="row align-items-center g-5">

        <div class="col-lg-6">
          <span class="badge rounded-pill bg-blue-soft text-blue px-3 py-2 mb-3">
            منصة ذكية للطلبات والخدمات السريعة
          </span>

          <h1 class="display-5 fw-black text-dark lh-base mb-3">
            اطلب احتياجك الآن،
            <span class="d-block text-gradient">وخلي الشخص المناسب يوصل لك بسرعة</span>
          </h1>

          <p class="lead text-muted lh-lg mb-4">
            NeedLink يساعدك على نشر طلبك أو خدمتك، والتواصل مع أشخاص قادرين على المساعدة
            بطريقة واضحة، سهلة، وعصرية.
          </p>

          <div class="d-flex flex-wrap gap-3 mb-4">
            <a href="auth/user-register.html" class="btn btn-primary-custom btn-lg px-5 fw-bold">إنشاء حساب</a>
            <a href="auth/login.html" class="btn btn-outline-custom btn-lg px-5 fw-bold">تسجيل الدخول</a>
          </div>

          <div class="row g-3 text-center">
            <div class="col-4">
              <div class="stat-box bg-white border rounded-4 p-3 h-100">
                <h4 class="fw-black text-dark mb-1">+120</h4>
                <small class="text-muted fw-bold">طلب نشط</small>
              </div>
            </div>

            <div class="col-4">
              <div class="stat-box bg-white border rounded-4 p-3 h-100">
                <h4 class="fw-black text-dark mb-1">+80</h4>
                <small class="text-muted fw-bold">مزود خدمة</small>
              </div>
            </div>

            <div class="col-4">
              <div class="stat-box bg-white border rounded-4 p-3 h-100">
                <h4 class="fw-black text-dark mb-1">24/7</h4>
                <small class="text-muted fw-bold">طلبات مستمرة</small>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card border-0 shadow-lg rounded-5 overflow-hidden hero-card">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=900&q=80"
              class="card-img-top hero-img" alt="Students collaboration">

            <div class="card-body p-4">
              <div class="row g-3">

                <div class="col-sm-6">
                  <div class="p-3 rounded-4 bg-orange-soft h-100">
                    <strong class="text-dark d-block mb-1">طلب جديد</strong>
                    <small class="text-muted">أحتاج تصميم بوستر اليوم</small>
                  </div>
                </div>

                <div class="col-sm-6">
                  <div class="p-3 rounded-4 bg-blue-soft h-100">
                    <strong class="text-dark d-block mb-1">مزود مهتم</strong>
                    <small class="text-muted">متاح للتنفيذ خلال ساعتين</small>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </header>

  <!-- Services -->
  <section class="py-5" id="services">
    <div class="container py-lg-4">

      <div class="text-center mx-auto mb-5 section-heading">
        <span class="text-orange fw-black">الخدمات</span>
        <h2 class="fw-black text-dark mt-2">كل احتياج إله شخص مناسب</h2>
        <p class="text-muted lh-lg">اعرض احتياجك أو قدّم خدمتك بطريقة واضحة وسريعة.</p>
      </div>

      <div class="row g-4">

        <div class="col-lg-4 col-md-6">
          <div class="card service-card h-100 border rounded-4 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80"
              class="card-img-top service-img" alt="Digital tasks">

            <div class="card-body p-4">
              <span class="badge rounded-pill bg-blue-soft text-blue mb-3">خدمات رقمية</span>
              <h5 class="card-title fw-black text-dark lh-base">تصميم، ملفات، ومهام أونلاين</h5>
              <p class="card-text text-muted lh-lg">
                اطلب مساعدة في تصميم، كتابة، تجهيز ملف، أو تنفيذ مهمة رقمية بسرعة.
              </p>
              <a href="auth/user-register.html" class="fw-bold text-blue text-decoration-none">ابدأ الطلب</a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6">
          <div class="card service-card h-100 border rounded-4 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=900&q=80"
              class="card-img-top service-img" alt="Help and service">

            <div class="card-body p-4">
              <span class="badge rounded-pill bg-orange-soft text-orange mb-3">طلبات سريعة</span>
              <h5 class="card-title fw-black text-dark lh-base">احتياجات يومية ومساعدة قريبة</h5>
              <p class="card-text text-muted lh-lg">
                عندك طلب بسيط أو مستعجل؟ انشره وخلي المهتمين يتواصلوا معك.
              </p>
              <a href="auth/user-register.html" class="fw-bold text-blue text-decoration-none">انشر احتياجك</a>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 mx-md-auto">
          <div class="card service-card h-100 border rounded-4 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=900&q=80"
              class="card-img-top service-img" alt="Freelancer service">

            <div class="card-body p-4">
              <span class="badge rounded-pill bg-blue-soft text-blue mb-3">مزودين</span>
              <h5 class="card-title fw-black text-dark lh-base">قدّم مهاراتك للآخرين</h5>
              <p class="card-text text-muted lh-lg">
                لو عندك مهارة أو وقت، تقدر تتابع الطلبات وتعرض اهتمامك بالخدمات المناسبة.
              </p>
              <a href="auth/user-register.html" class="fw-bold text-blue text-decoration-none">انضم كمزود</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- How It Works -->
  <section class="py-5 bg-light" id="how">
    <div class="container py-lg-4">

      <div class="text-center mx-auto mb-5 section-heading">
        <span class="text-orange fw-black">طريقة العمل</span>
        <h2 class="fw-black text-dark mt-2">ثلاث خطوات بسيطة</h2>
        <p class="text-muted lh-lg">الفكرة بسيطة: اكتب طلبك، استقبل المهتمين، واختر الشخص المناسب.</p>
      </div>

      <div class="row g-4">

        <div class="col-md-4">
          <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
            <div class="step-number mb-3">01</div>
            <h5 class="fw-black text-dark">انشر الطلب</h5>
            <p class="text-muted lh-lg mb-0">
              اكتب عنوان الطلب، التفاصيل، التصنيف، ومدى الاستعجال.
            </p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
            <div class="step-number mb-3">02</div>
            <h5 class="fw-black text-dark">استقبل المهتمين</h5>
            <p class="text-muted lh-lg mb-0">
              المستخدمون المناسبون يظهر لهم طلبك ويقدروا يرسلوا اهتمامهم.
            </p>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
            <div class="step-number mb-3">03</div>
            <h5 class="fw-black text-dark">اختَر ونفّذ</h5>
            <p class="text-muted lh-lg mb-0">
              اختَر الشخص الأنسب وابدأ التواصل معه لتنفيذ المهمة.
            </p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Roles -->
  <section class="py-5" id="roles">
    <div class="container py-lg-4">

      <div class="text-center mx-auto mb-5 section-heading">
        <span class="text-orange fw-black">المستخدمون</span>
        <h2 class="fw-black text-dark mt-2">NeedLink مناسب لأكثر من نوع مستخدم</h2>
        <p class="text-muted lh-lg">سواء كنت صاحب طلب أو مزود خدمة، المنصة مصممة لتسهيل التجربة عليك.</p>
      </div>

      <div class="row g-4">

        <div class="col-lg-6">
          <div class="card h-100 border rounded-4 p-4 role-card">
            <div class="d-flex gap-3 align-items-start">
              <div class="role-icon bg-blue-soft text-blue">ط</div>
              <div>
                <h5 class="fw-black text-dark">طالب أو صاحب احتياج</h5>
                <p class="text-muted lh-lg mb-0">
                  انشر ما تحتاجه، سواء مساعدة دراسية، تصميم، ملف، خدمة بسيطة، أو مهمة مستعجلة.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="card h-100 border rounded-4 p-4 role-card">
            <div class="d-flex gap-3 align-items-start">
              <div class="role-icon bg-orange-soft text-orange">م</div>
              <div>
                <h5 class="fw-black text-dark">مزود خدمة</h5>
                <p class="text-muted lh-lg mb-0">
                  تابع الطلبات المناسبة لمهاراتك، واعرض اهتمامك بطريقة سهلة وواضحة.
                </p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="py-5">
    <div class="container">
      <div class="cta-box text-center rounded-5 p-5 text-white">
        <h2 class="fw-black mb-3">جاهز تبدأ مع NeedLink؟</h2>
        <p class="mb-4 opacity-75">
          سجّل الآن وابدأ بنشر احتياجك أو تقديم خدماتك للآخرين.
        </p>
        <a href="auth/user-register.html" class="btn btn-light btn-lg px-5 fw-bold text-blue">انضم الآن</a>
      </div>
    </div>
  </section>
@endsection