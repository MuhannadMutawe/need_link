<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>نسيت كلمة المرور - NeedLink</title>
<link rel="stylesheet" href="css\styles.css">
<script src="js\app.js" defer></script>
</head>
<body>
<nav class="nav">
  <div class="container nav-inner">
    <div class="logo">
      <div class="logo-mark">N</div>
      <div><span class="need">Need</span><span class="link">Link</span></div>
    </div>
    <div class="menu">
      <a href="home.html">الرئيسية</a>
      <a href="explore.html">استكشاف الخدمات</a>
      <a href="request-service.html">طلب خدمة</a>
      <a href="service-details.html">تفاصيل الخدمة</a>
      <a href="messages.html">الرسائل</a>
      <a href="profile.html">الملف الشخصي</a>
      <a href="login.html">تسجيل الدخول</a>
    </div>
    <div class="actions">
      <button class="btn-outline">دخول</button>
      <button class="btn">ابدأ الآن</button>
    </div>
  </div>
</nav>

<div class="auth-wrap">
  <div class="auth-split">
    <section class="auth-brand">
      <span class="badge badge-orange" style="background:rgba(255,255,255,.16);color:#fff">استعادة الحساب</span>
      <h1 style="font-size:38px;margin:18px 0 10px">لا تقلق، سنرسل لك رابط الاستعادة</h1>
      <p style="color:#dbeafe">صفحة مخصصة مختصرة وواضحة، بدون تفاصيل زائدة.</p>
    </section>
    <section class="auth-card">
      <div class="auth-body" id="forgot-password-form">
        <h2 style="margin:0">نسيت كلمة المرور</h2>
        <div>
          <label>البريد الإلكتروني</label>
          <input type="email" id="email-input" placeholder="name@example.com">
          <p id="email-error" style="color: #ef4444; font-size: 14px; margin-top: 5px; display: none;">الرجاء إدخال بريد إلكتروني صحيح.</p>
        </div>
        <button class="btn" id="send-link-btn" onclick="handleForgotPassword()">إرسال رابط الاستعادة</button>
      </div>
      <div class="auth-body" id="success-message" style="display: none; text-align: center;">
        <h2 style="margin:0; color: #10b981;">تم الإرسال بنجاح!</h2>
        <p style="margin-top: 15px; line-height: 1.6;">تم إرسال رابط استعادة كلمة المرور إلى بريدك الإلكتروني. الرابط صالح لمدة 15 دقيقة.</p>
        <a href="login.html" class="btn" style="margin-top: 20px; display: block; text-align: center; text-decoration: none;">العودة لتسجيل الدخول</a>
      </div>
    </section>
  </div>
</div>

<footer class="footer"><div class="container">نسخة عربية كاملة لواجهات NeedLink — بسيطة، أوضح، وأقرب لفكرة المشروع الأصلية.</div></footer>
<script>
/**
 * معالجة نسيان كلمة المرور
 * يتحقق من صحة البريد الإلكتروني ويرسل رابط الاستعادة
 */
function handleForgotPassword() {
  // الحصول على قيمة البريد الإلكتروني
  const emailInput = document.getElementById('email-input').value;
  const emailError = document.getElementById('email-error');
  const formArea = document.getElementById('forgot-password-form');
  const successArea = document.getElementById('success-message');

  // تعبير منتظم للتحقق من صحة البريد الإلكتروني
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // التحقق من صحة البريد الإلكتروني
  if (!emailRegex.test(emailInput)) {
    emailError.style.display = 'block';  // إظهار خطأ
  } else {
    emailError.style.display = 'none';   // إخفاء خطأ
    formArea.style.display = 'none';     // إخفاء النموذج
    successArea.style.display = 'block';  // إظهار رسالة النجاح
  }
}
</script>
</body>
</html>