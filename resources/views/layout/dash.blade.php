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

    <!-- Notyf CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
</head>

<body class="admin-body">

    @include('layout.navbar')


    <div class="container-fluid">
        <div class="row min-vh-100">

            @include('layout.sideDesktop')
            <main class="col-lg-9 col-xl-10 admin-main-simple p-0">

               @yield('content')
           
            </main>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
    
    <!-- Notyf JS -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script>
        const notyf = new Notyf({
            duration: 4000,
            ripple: true,
            position: { x: 'left', y: 'top' },
            types: [
                {
                    type: 'error',
                    background: '#FF4C4C',
                    icon: { className: 'bi bi-x-circle-fill', tagName: 'i', color: 'white' }
                },
                {
                    type: 'success',
                    background: '#089331ff',
                    icon: { className: 'bi bi-check-circle-fill', tagName: 'i', color: 'white' }
                }
            ]
        });
    </script>

    @yield('script')
</body>

</html>