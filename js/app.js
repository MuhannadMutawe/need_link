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

