document.addEventListener('DOMContentLoaded', () => {
  const currentPage = window.location.pathname.split('/').pop() || 'home.html';

  document.querySelectorAll('.menu a').forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage) link.classList.add('active');
  });

  const revealTargets = document.querySelectorAll(
    '.panel, .service-card, .stat-card, .feed-card, .mini, .task, .review, .notif, .category-pill, .table-card, .auth-card, .sidebar'
  );

  revealTargets.forEach((el, index) => {
    el.classList.add('reveal');
    el.style.transitionDelay = `${Math.min(index * 40, 280)}ms`;
  });

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12 });

    revealTargets.forEach(el => observer.observe(el));
  } else {
    revealTargets.forEach(el => el.classList.add('is-visible'));
  }
}
);

document.addEventListener('DOMContentLoaded', () => {
  const editBtn = document.querySelector('.btn-light');
  const modal = document.getElementById('editModal');
  const closeBtn = document.getElementById('closeModal');
  const cancelBtn = document.getElementById('cancelBtn');


  editBtn.addEventListener('click', () => {
    modal.classList.add('active');
  });


  const closeModal = () => {
    modal.classList.remove('active');
  };

  closeBtn.addEventListener('click', closeModal);
  cancelBtn.addEventListener('click', closeModal);


  window.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });
});

document.addEventListener("DOMContentLoaded", function () {
  let currentStep = 1;
  const totalSteps = 3;

  const panels = document.querySelectorAll(".step-panel");
  const indicators = document.querySelectorAll(".progress-step");
  const progressBar = document.getElementById("progressBar");

  const nextBtn = document.getElementById("nextBtn");
  const prevBtn = document.getElementById("prevBtn");
  const submitBtn = document.getElementById("submitBtn");
  const form = document.getElementById("registerForm");

  const providerModeBox = document.getElementById("providerModeBox");

  function getCheckedValue(name) {
    const checked = document.querySelector(`input[name="${name}"]:checked`);
    return checked ? checked.value : "";
  }

  function showStep(step) {
    panels.forEach(panel => {
      panel.classList.remove("active");
      if (Number(panel.dataset.step) === step) {
        panel.classList.add("active");
      }
    });

    indicators.forEach(indicator => {
      indicator.classList.remove("active");
      if (Number(indicator.dataset.indicator) === step) {
        indicator.classList.add("active");
      }
    });

    progressBar.style.width = `${(step / totalSteps) * 100}%`;

    prevBtn.style.display = step > 1 ? "inline-block" : "none";
    nextBtn.style.display = step < totalSteps ? "inline-block" : "none";
    submitBtn.style.display = step === totalSteps ? "inline-block" : "inline-block";
    if (step !== totalSteps) {
      submitBtn.style.display = "none";
    }

    if (step === 3) {
      fillSummary();
    }
  }

  function fillSummary() {
    document.getElementById("summary_name").textContent =
      document.getElementById("name").value.trim() || "-";

    document.getElementById("summary_username").textContent =
      document.getElementById("username").value.trim() || "-";

    document.getElementById("summary_email").textContent =
      document.getElementById("email").value.trim() || "-";

    document.getElementById("summary_phone").textContent =
      document.getElementById("phone").value.trim() || "-";
  }

  function validateStep(step) {
    // الخطوة 1: التحقق من البيانات الشخصية الأساسية
    if (step === 1) {
      const name = document.getElementById("name").value.trim();
      const username = document.getElementById("username").value.trim();
      const email = document.getElementById("email").value.trim();
      const phone = document.getElementById("phone").value.trim();

      if (!name || !username || !email || !phone) {
        alert("يرجى تعبئة جميع الحقول في الخطوة الأولى");
        return false;
      }
      // Validation 

      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const phonePattern = /^[0-9]{10}$/;

      // Email Validation 
      if (!emailPattern.test(email)) {
        alert("يرجى إدخال بريد إلكتروني صحيح");
        return false;
      }

      // Phone Validation 
      if (!phonePattern.test(phone)) {
        alert("رقم الجوال يجب أن يكون 10 أرقام");
        return false;
      }

    }

    // الخطوة 2 : التحقق من كلمة المرور
    if (step === 2) {
      const password = document.getElementById("password").value;
      const confirm = document.getElementById("password_confirmation").value;

      if (!password || !confirm) {
        alert("يرجى إدخال كلمة المرور وتأكيدها");
        return false;
      }

      if (password.length < 8) {
        alert("كلمة المرور يجب أن تكون 8 أحرف على الأقل");
        return false;
      }

      if (password !== confirm) {
        alert("كلمة المرور وتأكيدها غير متطابقين");
        return false;
      }
    }

    return true;
  }


  nextBtn.addEventListener("click", function () {
    if (!validateStep(currentStep)) return;

    if (currentStep < totalSteps) {
      currentStep++;
      showStep(currentStep);
    }
  });

  prevBtn.addEventListener("click", function () {
    if (currentStep > 1) {
      currentStep--;
      showStep(currentStep);
    }
  });



  showStep(currentStep);
});

// Show or hide password
document.querySelectorAll(".toggle-password").forEach(function (button) {
  button.addEventListener("click", function () {
    const inputId = this.getAttribute("data-target");
    const input = document.getElementById(inputId);
    const icon = this.querySelector("i");

    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("bi-eye");
      icon.classList.add("bi-eye-slash");
    } else {
      input.type = "password";
      icon.classList.remove("bi-eye-slash");
      icon.classList.add("bi-eye");
    }
  });
});

