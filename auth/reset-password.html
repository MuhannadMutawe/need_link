<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>إعادة تعيين كلمة المرور - NeedLink</title>
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
      <span class="badge badge-orange" style="background:rgba(255,255,255,.16);color:#fff">كلمة مرور جديدة</span>
      <h1 style="font-size:38px;margin:18px 0 10px">اختر كلمة مرور جديدة وآمنة</h1>
      <p style="color:#dbeafe">هذه الصفحة مستقلة بصريًا عن التسجيل والدخول حتى لا تبدو كلها مكررة.</p>
    </section>
    <section class="auth-card">
      <div class="auth-body" id="reset-password-form">
        <h2 style="margin:0">إعادة تعيين كلمة المرور</h2>
        <div>
          <label>كلمة المرور الجديدة</label>
          <input type="password" id="new-password" placeholder="••••••••" onkeyup="validatePassword()">
          <ul id="password-reqs" style="list-style: none; padding: 0; margin-top: 10px; font-size: 13px; color: #6b7280; line-height: 1.6;">
            <li id="req-length">✗ 8 أحرف على الأقل</li>
            <li id="req-upper">✗ حرف كبير (Uppercase)</li>
            <li id="req-lower">✗ حرف صغير (Lowercase)</li>
            <li id="req-number">✗ رقم واحد على الأقل</li>
            <li id="req-special">✗ رمز مميز (مثل !@#$%)</li>
          </ul>
        </div>
        <div>
          <label>تأكيد كلمة المرور</label>
          <input type="password" id="confirm-password" placeholder="••••••••" onkeyup="validatePassword()">
          <p id="match-error" style="color: #ef4444; font-size: 14px; margin-top: 5px; display: none;">كلمتا المرور غير متطابقتين.</p>
        </div>
        <button class="btn-blue" id="reset-btn" onclick="handleResetPassword()" disabled style="opacity: 0.5; cursor: not-allowed;">حفظ كلمة المرور</button>
      </div>
      <div class="auth-body" id="success-message" style="display: none; text-align: center;">
        <h2 style="margin:0; color: #10b981;">تم بنجاح!</h2>
        <p style="margin-top: 15px; line-height: 1.6;">تم إعادة تعيين كلمة المرور الخاصة بك بنجاح. يمكنك الآن تسجيل الدخول باستخدام كلمة المرور الجديدة.</p>
        <a href="login.html" class="btn" style="margin-top: 20px; display: block; text-align: center; text-decoration: none;">الانتقال لتسجيل الدخول</a>
      </div>
    </section>
  </div>
</div>

<footer class="footer"><div class="container">نسخة عربية كاملة لواجهات NeedLink — بسيطة، أوضح، وأقرب لفكرة المشروع الأصلية.</div></footer>
<script>
/**
 * التحقق من قوة كلمة المرور وتحديث واجهة المستخدم
 * يتحقق من: الطول 8+, حرف كبير, حرف صغير, رقم, رمز خاص
 */
function validatePassword() {
  // الحصول على قيم الحقول
  const pwd = document.getElementById('new-password').value;
  const confirmPwd = document.getElementById('confirm-password').value;
  const btn = document.getElementById('reset-btn');
  const matchError = document.getElementById('match-error');

  // تحديد متطلبات كلمة المرور القوية
  const reqs = {
    length: pwd.length >= 8,
    upper: /[A-Z]/.test(pwd),      // يحتوي على حرف كبير
    lower: /[a-z]/.test(pwd),      // يحتوي على حرف صغير
    number: /[0-9]/.test(pwd),     // يحتوي على رقم
    special: /[^A-Za-z0-9]/.test(pwd)  // يحتوي على رمز خاص
  };

  // تحديث واجهة المستخدم لكل متطلب
  const updateReqUI = (id, isValid) => {
    const el = document.getElementById(id);
    if (isValid) {
      el.style.color = '#10b981';    // أخضر للنجاح
      el.innerText = el.innerText.replace('✗', '✓');
    } else {
      el.style.color = '#6b7280';    // رمادي للفشل
      el.innerText = el.innerText.replace('✓', '✗');
    }
  };

  // تحديث حالة كل متطلب
  updateReqUI('req-length', reqs.length);
  updateReqUI('req-upper', reqs.upper);
  updateReqUI('req-lower', reqs.lower);
  updateReqUI('req-number', reqs.number);
  updateReqUI('req-special', reqs.special);

  // التحقق من صحة كلمة المرور وتطابقها
  const isPasswordValid = Object.values(reqs).every(Boolean);
  const isMatching = pwd === confirmPwd && pwd !== '';

  // إظهار خطأ عدم التطابق إذا لزم الأمر
  if (confirmPwd.length > 0 && !isMatching) {
    matchError.style.display = 'block';
  } else {
    matchError.style.display = 'none';
  }

  // تمكين/تعطيل زر الحفظ حسب الصحة
  if (isPasswordValid && isMatching) {
    btn.disabled = false;
    btn.style.opacity = '1';
    btn.style.cursor = 'pointer';
  } else {
    btn.disabled = true;
    btn.style.opacity = '0.5';
    btn.style.cursor = 'not-allowed';
  }
}

/**
 * معالجة إعادة تعيين كلمة المرور
 * إخفاء النموذج وإظهار رسالة النجاح
 */
function handleResetPassword() {
  const formArea = document.getElementById('reset-password-form');
  const successArea = document.getElementById('success-message');
  formArea.style.display = 'none';
  successArea.style.display = 'block';
}
</script>
</body>
</html>