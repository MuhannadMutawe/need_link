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
  const totalSteps = 4;

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

    if (step === 4) {
      fillSummary();
    }
  }

  function toggleProviderMode() {
    const role = getCheckedValue("user_role");

    if (role === "provider" || role === "both") {
      providerModeBox.style.display = "block";
    } else {
      providerModeBox.style.display = "none";

      document.querySelectorAll('input[name="provider_mode"]').forEach(input => {
        input.checked = false;
      });
    }
  }

  function mapUserRole(value) {
    switch (value) {
      case "needer":
        return "محتاج خدمة";
      case "provider":
        return "مقدم خدمة";
      case "both":
        return "الاثنان";
      default:
        return "-";
    }
  }

  function mapProviderMode(value) {
    switch (value) {
      case "quick_help":
        return "مساعدة سريعة";
      case "freelancer":
        return "مستقل";
      case "both":
        return "الاثنان";
      default:
        return "-";
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

    const role = getCheckedValue("user_role");
    const providerMode = getCheckedValue("provider_mode");

    document.getElementById("summary_user_role").textContent = mapUserRole(role);

    const row = document.getElementById("summary_provider_mode_row");
    const value = document.getElementById("summary_provider_mode");

    if (role === "provider" || role === "both") {
      row.style.display = "flex";
      value.textContent = mapProviderMode(providerMode);
    } else {
      row.style.display = "none";
      value.textContent = "-";
    }
  }

  function validateStep(step) {
    if (step === 1) {
      const name = document.getElementById("name").value.trim();
      const username = document.getElementById("username").value.trim();
      const email = document.getElementById("email").value.trim();
      const phone = document.getElementById("phone").value.trim();

      if (!name || !username || !email || !phone) {
        alert("يرجى تعبئة جميع الحقول في الخطوة الأولى");
        return false;
      }
    }

    if (step === 2) {
      const role = getCheckedValue("user_role");

      if (!role) {
        alert("يرجى اختيار دورك داخل النظام");
        return false;
      }

      if ((role === "provider" || role === "both") && !getCheckedValue("provider_mode")) {
        alert("يرجى اختيار نوع التقديم");
        return false;
      }
    }

    if (step === 3) {
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

  document.querySelectorAll('input[name="user_role"]').forEach(input => {
    input.addEventListener("change", toggleProviderMode);
  });

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

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    alert("هان يا شباب راح تجي مهمة الباك اند في انشاءالحساب و اضافته لداتا بيز");
  });

  showStep(currentStep);
});