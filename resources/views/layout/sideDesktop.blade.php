  <!-- Desktop Sidebar -->
      <aside class="col-lg-3 col-xl-2 d-none d-lg-block admin-sidebar-simple p-0">
        <div class="p-4 border-bottom border-light border-opacity-10">
          <div class="d-flex align-items-center gap-2">
            <img src="../assets/logo/logo.png" class="admin-logo-img" alt="NeedLink Logo">
            <div>
              <h5 class="mb-0 fw-bold">
                <span class="text-orange">Need</span><span class="text-blue">Link</span>
              </h5>
              <small class="text-white-50">لوحة التحكم</small>
            </div>
          </div>
        </div>

        <div class="p-3">
          <div class="list-group admin-side-links">
            <a href="{{ route('dashboard.main') }}" class="list-group-item list-group-item-action active">
              <i class="bi bi-grid-1x2-fill ms-2"></i> الرئيسية
            </a>

            <a href="{{ route('dashboard.requests.index') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-people-fill ms-2"></i> طلباتي
            </a>

            <a href="{{ route('dashboard.offers.myOffers') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
              <span><i class="bi bi-person-check-fill ms-2"></i>عروضي</span>
            </a>

            <a href="#" class="list-group-item list-group-item-action">
              <i class="bi bi-briefcase-fill ms-2"></i> الخدمات
            </a>

            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
              <span><i class="bi bi-flag-fill ms-2"></i> البلاغات</span>
              <span class="badge bg-danger">7</span>
            </a>

            <a href="#" class="list-group-item list-group-item-action">
              <i class="bi bi-chat-dots-fill ms-2"></i> الرسائل
            </a>

            <a href="#" class="list-group-item list-group-item-action">
              <i class="bi bi-bar-chart-fill ms-2"></i> الإحصائيات
            </a>

            <a href="#" class="list-group-item list-group-item-action">
              <i class="bi bi-gear-fill ms-2"></i> الإعدادات
            </a>
          </div>
        </div>
      </aside>
