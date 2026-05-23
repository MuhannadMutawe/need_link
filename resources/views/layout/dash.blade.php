<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <link rel="icon" href="{{ asset('assets/logo/logo.png') }}" type="image/png" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body class="admin-body">

    <!-- Mobile Navbar -->
    <nav class="navbar bg-white border-bottom sticky-top d-lg-none">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="#">
                <img src="{{ asset('assets/logo/logo.png') }}" class="admin-logo-img" alt="NeedLink Logo">
                <span><span class="text-orange">Need</span><span class="text-blue">Link</span></span>
            </a>

            <button class="btn btn-outline-primary" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#mobileSidebar">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </nav>

    @include('layout.sideMobile')

    <div class="container-fluid">
        <div class="row min-vh-100">

            @include('layout.sideDesktop')
            <main class="col-lg-9 col-xl-10 admin-main-simple p-0">

                <!-- Topbar -->
                <header class="bg-white border-bottom px-4 py-3">
                    <div class="d-flex  flex-md-row justify-content-between align-items-center gap-3">

                        <div>
                            <h4 class="fw-bold text-dark mb-1">@yield('sub-title')</h4>
                        </div>

                        <div class="d-flex align-items-center gap-2">

                            <button class="btn btn-light position-relative">
                                <i class="bi bi-bell"></i>
                                <span
                                    class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-orange">9</span>
                            </button>

                            <div class="p-2">
                                <img src="{{ asset('assets/logo/logo.png') }}" alt="الصورة الشخصية"
                                    style="width:40px;border-radius:50%;">
                            </div>
                        </div>

                    </div>
                </header>

               @yield('content')
           

            </main>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    @yield('script')
</body>

</html>