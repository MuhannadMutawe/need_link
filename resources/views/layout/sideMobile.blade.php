

<!-- Mobile Sidebar -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header border-bottom">
      <h5 class="offcanvas-title fw-bold">لوحة التحكم</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">
      <div class="list-group admin-side-links">
        <a href="{{ route('dashbaord.main') }}" class="list-group-item list-group-item-action active">
          <i class="bi bi-grid-1x2-fill ms-2"></i> الرئيسية
        </a>
        <a href="{{ route('dashbaord.requests.index') }}" class="list-group-item list-group-item-action">
          <i class="bi bi-people-fill ms-2"></i> المستخدمون
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <i class="bi bi-person-check-fill ms-2"></i> طلبات التحقق
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <i class="bi bi-briefcase-fill ms-2"></i> الخدمات
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <i class="bi bi-flag-fill ms-2"></i> البلاغات
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <i class="bi bi-gear-fill ms-2"></i> الإعدادات
        </a>
      </div>
    </div>
  </div>
