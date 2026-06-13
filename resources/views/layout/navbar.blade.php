{{-- Floating Minimal Navbar --}}
<style>
  .floating-nav {
    position: fixed;
    top: 16px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1050;
    width: calc(100% - 48px);
    max-width: 960px;
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    border-radius: 999px;
    padding: 10px 24px;
    box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08), 0 1px 4px rgba(0,0,0,0.06);
    transition: box-shadow 0.3s ease, background 0.3s ease;
  }
  .floating-nav:hover {
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.13), 0 2px 8px rgba(0,0,0,0.07);
  }
  .floating-nav .navbar-brand span {
    font-size: 1.15rem;
    font-weight: 800;
    letter-spacing: -0.5px;
  }
  .floating-nav .nav-link {
    font-size: 0.875rem;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
    transition: background 0.2s ease, color 0.2s ease;
  }
  .floating-nav .nav-link:hover {
    background: rgba(0,0,0,0.05);
  }
  .floating-nav .btn {
    font-size: 0.82rem;
    font-weight: 600;
    padding: 6px 18px;
    border-radius: 999px;
  }
  .floating-nav .navbar-toggler {
    border: none;
    padding: 4px 8px;
  }
  .floating-nav .navbar-toggler:focus {
    box-shadow: none;
  }
  /* Push page content below the floating bar */
  body { padding-top: 80px; }

  /* ── Mobile Offcanvas Sidebar ── */
  #mobileSidebar .mob-nav-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 10px;
    font-size: 0.9rem;
    font-weight: 600;
    color: #333;
    text-decoration: none;
    transition: background 0.18s ease, color 0.18s ease;
  }
  #mobileSidebar .mob-nav-link:hover,
  #mobileSidebar .mob-nav-link.active {
    background: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
  }
  #mobileSidebar .mob-nav-link i {
    font-size: 1rem;
    width: 20px;
    text-align: center;
  }
  #mobileSidebar .mob-section-label {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #aaa;
    padding: 14px 14px 4px;
    margin: 0;
  }
  #mobileSidebar .mob-auth-area {
    padding: 16px 14px;
    border-top: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  #mobileSidebar .mob-auth-area .btn {
    border-radius: 999px;
    font-weight: 600;
    font-size: 0.88rem;
  }
</style>

{{-- ═══════════════════════════════════════════════
     Floating Navbar
════════════════════════════════════════════════ --}}
<nav class="navbar navbar-expand-lg floating-nav" id="floatingNav">

  {{-- Brand --}}
  <a class="navbar-brand d-flex align-items-center gap-2 me-3" href="{{ route('main.showLanding') }}">
    <img src="{{ asset('assets/logo/logo.png') }}" style="height: 26px;" alt="NeedLink Logo">
    <span>
      <span class="text-orange">Need</span><span class="text-primary">Link</span>
    </span>
  </a>

  {{-- Mobile: hamburger → opens offcanvas --}}
  <button class="navbar-toggler ms-auto d-lg-none" type="button"
    data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar"
    aria-controls="mobileSidebar" aria-label="Toggle navigation">
    <i class="bi bi-list fs-4"></i>
  </button>

  {{-- Desktop: inline links --}}
  <div class="collapse navbar-collapse d-none d-lg-flex" id="mainNavbar">
    <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-1">
      <li class="nav-item">
        <a class="nav-link text-success" href="{{ route('main.requests') }}">الطلبات المتاحة</a>
      </li>
      @auth
        <li class="nav-item">
          <a class="nav-link text-primary" href="{{ route('dashboard.main') }}">لوحة التحكم</a>
        </li>
      @endauth
    </ul>

    <div class="d-flex gap-2 align-items-center">
      @auth
        <form action="{{ route('auth.logout') }}" method="POST" class="d-inline">
          @csrf
          <button type="submit" class="btn btn-outline-danger">تسجيل الخروج</button>
        </form>
      @else
        <a href="{{ route('auth.login') }}" class="btn btn-light">دخول</a>
        <a href="{{ route('auth.register') }}" class="btn btn-primary">ابدأ الآن</a>
      @endauth
    </div>
  </div>

</nav>

{{-- 
     Mobile Offcanvas Sidebar
 --}}
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileSidebar"
  aria-labelledby="mobileSidebarLabel">

  <div class="offcanvas-header border-bottom">
    <div class="d-flex align-items-center gap-2">
      <img src="{{ asset('assets/logo/logo.png') }}" style="height: 22px;" alt="NeedLink Logo">
      <span id="mobileSidebarLabel" style="font-size:1rem;font-weight:800;">
        <span class="text-orange">Need</span><span class="text-primary">Link</span>
      </span>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body d-flex flex-column p-2">

    {{-- Main navigation --}}
    <p class="mob-section-label">القائمة الرئيسية</p>
    <a href="{{ route('main.showLanding') }}" class="mob-nav-link">
      <i class="bi bi-house-fill"></i> الرئيسية
    </a>
    <a href="{{ route('main.requests') }}" class="mob-nav-link">
      <i class="bi bi-search"></i> الطلبات المتاحة
    </a>

    @auth
      {{-- Dashboard links --}}
      <p class="mob-section-label mt-2">لوحة التحكم</p>
      <a href="{{ route('dashboard.main') }}" class="mob-nav-link">
        <i class="bi bi-grid-1x2-fill"></i> الرئيسية
      </a>
      <a href="{{ route('dashboard.requests.index') }}" class="mob-nav-link">
        <i class="bi bi-file-earmark-text-fill"></i> طلباتي
      </a>
      <a href="{{ route('dashboard.offers.myOffers') }}" class="mob-nav-link">
        <i class="bi bi-tag-fill"></i> عروضي
      </a>
      <a href="#" class="mob-nav-link">
        <i class="bi bi-person-check-fill"></i> طلبات التحقق
      </a>
      <a href="{{ route('dashboard.orders.index') }}" class="mob-nav-link">
        <i class="bi bi-briefcase-fill"></i> الخدمات
      </a>
      <a href="#" class="mob-nav-link">
        <i class="bi bi-flag-fill"></i> البلاغات
      </a>
      <a href="#" class="mob-nav-link">
        <i class="bi bi-gear-fill"></i> الإعدادات
      </a>
    @endauth

    {{-- Auth buttons pinned to bottom --}}
    <div class="mob-auth-area mt-auto">
      @auth
        <form action="{{ route('auth.logout') }}" method="POST">
          <button type="submit" class="btn btn-outline-danger w-100">تسجيل الخروج</button>
        </form>
      @else
        <a href="{{ route('auth.login') }}" class="btn btn-light w-100">دخول</a>
        <a href="{{ route('auth.register') }}" class="btn btn-primary w-100">ابدأ الآن</a>
      @endauth
    </div>

  </div>
</div>

<script>
  (function () {
    var sidebar = document.getElementById('mobileSidebar');
    var floatingNav = document.getElementById('floatingNav');
    if (!sidebar || !floatingNav) return;

    sidebar.addEventListener('show.bs.offcanvas', function () {
      floatingNav.style.transition = 'opacity 0.2s ease, transform 0.2s ease';
      floatingNav.style.opacity = '0';
      floatingNav.style.transform = 'translateX(-50%) translateY(-12px)';
      floatingNav.style.pointerEvents = 'none';
    });

    sidebar.addEventListener('hidden.bs.offcanvas', function () {
      floatingNav.style.opacity = '1';
      floatingNav.style.transform = 'translateX(-50%) translateY(0)';
      floatingNav.style.pointerEvents = '';
    });
  })();
</script>
