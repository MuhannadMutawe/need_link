  <!-- Admin Desktop Sidebar -->
      <aside class="col-lg-3 col-xl-2 d-none d-lg-block admin-sidebar-simple p-0">
        <div class="p-4 border-bottom border-light border-opacity-10">
          <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('assets/logo/logo.png') }}" class="admin-logo-img" alt="NeedLink Logo">
            <div>
              <h5 class="mb-0 fw-bold">
                <span class="text-orange">Need</span><span class="text-blue">Link</span>
              </h5>
              <small class="text-white-50">لوحة تحكم الإدارة</small>
            </div>
          </div>
        </div>

        <div class="p-3">
          <div class="list-group admin-side-links">
            
            <div class="mb-2">
                <small class="text-white-50 px-3 fw-bold">إدارة الموقع</small>
            </div>
            
            <a href="{{ route('dashboard.categories.index') }}" class="list-group-item list-group-item-action {{ request()->routeIs('dashboard.categories.*') ? 'active' : '' }}">
              <i class="bi bi-tags-fill ms-2"></i> إدارة الفئات
            </a>

            <a href="{{ route('dashboard.disputes.index') }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between {{ request()->routeIs('dashboard.disputes.*') ? 'active' : '' }}">
              <span><i class="bi bi-shield-exclamation ms-2"></i> إدارة النزاعات</span>
              @php $openDisputes = \App\Models\OrderDispute::where('status','open')->count(); @endphp
              @if($openDisputes > 0)
                <span class="badge bg-danger rounded-pill">{{ $openDisputes }}</span>
              @endif
            </a>

            <div class="mt-4 mb-2">
                <small class="text-white-50 px-3 fw-bold">العودة</small>
            </div>
            
            <a href="{{ route('dashboard.main') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-arrow-right-circle-fill ms-2"></i> لوحة المستخدم
            </a>

          </div>
        </div>
      </aside>
