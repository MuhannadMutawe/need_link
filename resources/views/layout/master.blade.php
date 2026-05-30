<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <link rel="icon" href="{{ asset('assets/logo/logo.png') }}" type="image/png" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title')</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Main CSS -->
    @yield('css')
</head>

<body>

    @include('layout.navbar')
    
    @yield('content')


    <!-- JS Files -->
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script> 
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script> 
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
                    className: 'custom-error-toast',
                    icon: {
                        className: 'bi bi-x-circle-fill',
                        tagName: 'i',
                        color: 'white',
                    }
                },
                {
                    type: 'success',
                    background: '#089331ff',
                    icon: {
                        className: 'bi bi-check-circle-fill',
                        tagName: 'i',
                        color: 'white',
                    }
                },
                {
                    type: 'warning',
                    background: '#ffe600ff',
                    icon: {
                        className: 'bi bi-exclamation-triangle-fill',
                        tagName: 'i',
                        color: 'white',
                    }
                }
            ]
        });
    </script>
    @yield('script')

</body>

</html>


    <!-- Footer -->
    <!-- <footer class="bg-dark text-white-50 py-4">
        <div class="container">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 text-center text-md-start">

                <div class="d-flex align-items-center gap-2 fw-bold fs-5">
                    <img src="{{ asset('assets/logo/logo.png') }}" class="footer-logo" alt="NeedLink Logo">
                    <span><span class="text-orange">Need</span><span class="text-blue">Link</span></span>
                </div>

                <p class="mb-0 fw-bold">&copy; NeedLink 2026 - All Rights Reserved</p>

            </div>
        </div>
    </footer> -->