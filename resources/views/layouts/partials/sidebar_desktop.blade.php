  <!-- Desktop Sidebar -->
      <aside class="col-lg-3 col-xl-2 d-none d-lg-block admin-sidebar-simple p-0">


        @auth
        <div class="p-3 border-bottom border-light border-opacity-10">
          <div class="d-flex align-items-center gap-3">
            @if(auth()->user()->avatar)
              <img src="{{ auth()->user()->avatar }}" alt="{{ auth()->user()->name }}"
                   class="rounded-circle border border-white border-opacity-25"
                   style="width: 42px; height: 42px; object-fit: cover; flex-shrink: 0;">
            @else
              <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0"
                   style="width: 42px; height: 42px; font-weight: 700; font-size: 1rem; color: #fff; background: linear-gradient(135deg, #6366f1, #4338ca) !important;">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
              </div>
            @endif
            <div style="min-width: 0;">
              <p class="mb-0 fw-bold text-white" dir="auto" style="font-size: 0.92rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                {{ auth()->user()->name }}
              </p>
              <small class="text-white-50" style="font-size: 0.75rem;">مستخدم نشط</small>
            </div>
          </div>
        </div>
        @endauth

        <div class="p-3">
          <div class="list-group admin-side-links">
            <a href="{{ route('dashboard.main') }}" class="list-group-item list-group-item-action active">
              <i class="bi bi-grid-1x2-fill ms-2"></i> الرئيسية
            </a>

            <a href="{{ route('dashboard.requests.index') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-people-fill ms-2"></i> طلباتي
            </a>

            <a href="{{ route('dashboard.offers.myOffers') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-person-check-fill ms-2"></i> عروضي
            </a>

            <a href="{{ route('dashboard.orders.index') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-briefcase-fill ms-2"></i> الخدمات
            </a>

            <a href="#" class="list-group-item list-group-item-action">
              <i class="bi bi-chat-dots-fill ms-2"></i> الرسائل
            </a>

            <a href="#" class="list-group-item list-group-item-action">
              <i class="bi bi-gear-fill ms-2"></i> الإعدادات
            </a>
            
            <hr class="text-white-50 my-3">
            
            <a href="{{ route('dashboard.categories.index') }}" class="list-group-item list-group-item-action text-warning fw-bold">
              <i class="bi bi-shield-lock-fill ms-2"></i> لوحة الإدارة
            </a>
          </div>
        </div>
      </aside>
