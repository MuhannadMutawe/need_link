@extends('layout.master')
@section('title')
    NeedLink - تسجيل الدخول
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection
@section('content')

    <div class="container">
        <div class="brand-section">
            <div class="badge">أهلاً بك مجدداً في NeedLink</div>
            <h2>سجل دخولك بسهولة</h2>
            <p>واجهة الدخول هنا سريعة، سهلة، بسيطة لتتمكن من الوصول لخدماتنا فوراً.</p>
        </div>
        <div class="login-section">
            <h1 class="title">تسجيل الدخول</h1>

            <form id="loginForm" action="{{ route('auth.login.submit') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label>البريد الإلكتروني</label>
                    <input type="email" name="email" placeholder="example@gmail.com" required>
                </div>

                <div class="form-group">
                    <label>كلمة المرور</label>
                    <input type="password" name="password" placeholder="ادخل كلمة المرور">
                </div>

                <button type="submit" class="login-btn">دخول</button>
            </form>

            <div class="footer-links">
                <span>ليس لديك حساب؟ </span>
                <a href="{{ route('auth.register') }}">إنشاء حساب جديد</a>
            </div>
        </div>


    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#loginForm').on('submit', function (e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '{{ route('auth.login.submit') }}', // we can write $(this).attr('action') alse :)
                    method: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            window.location.href = response.redirect; // هان علشان نعمل اعادة توجيه لصفحة لرابط المناسب لما تأتي النتيجة صحيحة من السيرفر
                        } else {
                            notyf.error(response.message);

                        }
                    },
                    error: function (xhr) {
                        $('.error-text').remove();
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                notyf.error(value[0]); // في حال كان هناك اخطاء عند محاولة تسجيل الدخول سوف يخرج رسالة الخطا للمستخدم طبعا الرسائل تخزن ك array
                            });
                        }
                    },
                });
            })
        });

    </script>


@endsection