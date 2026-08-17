<!DOCTYPE html>
<html lang="en">
  <head>
     <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    @include('partials.pwa-head')

    <!-- Bootstrap 5 & FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    @stack('styles')

    <style>
        /* Modern Admin Layout Styles */
        body {
            background-color: #f4f6f9;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container-scroller {
            display: flex;
            min-height: 100vh;
        }
        .admin-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        .main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background-color: #f4f6f9;
            min-height: 100vh;
            padding-top: 0 !important;
        }
        .content-wrapper {
            background-color: #f4f6f9 !important;
            padding: 1.8rem 2.2rem !important;
            flex-grow: 1;
        }
        @media (max-width: 767.98px) {
            .content-wrapper {
                padding: 1rem 0.75rem !important;
            }
        }
        .page-body-wrapper {
            padding-top: 0 !important;
            min-height: 100vh;
            display: flex;
            width: 100%;
        }

        /* Mobile top header bar */
        .mobile-admin-header {
            background-color: #14161a;
            color: #ffffff;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1020;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .mobile-admin-header .brand-title {
            font-size: 16px;
            font-weight: 700;
            color: #ffffff;
            margin: 0;
            text-decoration: none;
        }
        .mobile-toggle-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: #ffffff;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
        }

        /* Responsive sidebar drawer backdrop for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1030;
            backdrop-filter: blur(2px);
        }
        .sidebar-overlay.show {
            display: block;
        }
    </style>
  </head>
  <body>
 
    <div class="container-scroller">
      <div class="admin-wrapper flex-column flex-lg-row w-100">

        <!-- Mobile Header Bar (Visible on mobile only) -->
        <div class="mobile-admin-header d-lg-none">
          <a href="{{ route('admin.dashboard') }}" class="brand-title d-inline-flex align-items-center">
            <img src="/favicon_io/android-chrome-192x192.png" alt="All Season Garden" style="width: 26px; height: 26px; border-radius: 6px; margin-right: 8px; object-fit: cover;">
            <span>All Season Garden</span>
          </a>
          <button type="button" class="mobile-toggle-btn" id="mobileSidebarToggle">
            <i class="fas fa-bars"></i>
          </button>
        </div>

        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <div class="container-fluid page-body-wrapper p-0">
          @include('partials.admin.sidebar')
          
          <div class="main-panel">
            @yield('content')
            @include('partials.admin.footer')
          </div>

          @include('partials.logout')
        </div>
      </div>
    </div>

    <!-- Global Delete Confirmation Modal -->
    <div class="modal fade" id="globalDeleteConfirmModal" tabindex="-1" aria-hidden="true" style="z-index: 1080;">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
          <div class="modal-header border-0 pb-0">
            <h5 class="modal-title font-weight-bold text-dark d-flex align-items-center gap-2">
              <i class="fas fa-exclamation-triangle text-danger"></i> Confirm Deletion
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center py-4" style="font-size: 14px; color: #4b5563;">
            <p class="mb-1 fw-semibold" id="globalDeleteConfirmMessage">Are you sure you want to delete this record?</p>
            <small class="text-danger">This action cannot be undone.</small>
          </div>
          <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
            <button type="button" class="btn btn-light px-4 me-2 font-weight-semibold" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-danger px-4 font-weight-bold" id="globalDeleteConfirmSubmitBtn">Yes, Delete</button>
          </div>
        </div>
      </div>
    </div>
 
    @if(session('auto_print_receipt_url'))
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            window.open("{{ session('auto_print_receipt_url') }}", '_blank', 'width=400,height=600');
        });
    </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toggleBtn = document.getElementById('mobileSidebarToggle');
            var closeBtn = document.getElementById('closeSidebarBtn');
            var sidebar = document.getElementById('sidebar');
            var overlay = document.getElementById('sidebarOverlay');

            function openSidebar() {
                if (sidebar) sidebar.classList.add('mobile-open');
                if (overlay) overlay.classList.add('show');
            }
            function closeSidebar() {
                if (sidebar) sidebar.classList.remove('mobile-open');
                if (overlay) overlay.classList.remove('show');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', openSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (overlay) overlay.addEventListener('click', closeSidebar);
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Global Alert Dismissal Handler
        document.addEventListener('click', function(e) {
          var closeBtn = e.target.closest('[data-bs-dismiss="alert"], [data-dismiss="alert"], .btn-close, .close');
          if (closeBtn) {
            var alertBox = closeBtn.closest('.alert');
            if (alertBox) {
              alertBox.classList.remove('show');
              setTimeout(function() {
                alertBox.remove();
              }, 150);
            }
          }
        });

        // Global Delete Form Interceptor
        var targetFormToSubmit = null;
        var globalDeleteModalEl = document.getElementById('globalDeleteConfirmModal');
        var globalDeleteModal = null;
        var globalConfirmBtn = document.getElementById('globalDeleteConfirmSubmitBtn');

        if (globalConfirmBtn) {
          globalConfirmBtn.addEventListener('click', function() {
            if (targetFormToSubmit) {
              targetFormToSubmit.dataset.confirmed = 'true';
              targetFormToSubmit.submit();
            }
          });
        }

        document.addEventListener('submit', function(e) {
          var form = e.target;
          if (!form) return;

          if (form.dataset.confirmed === 'true') return;
          if (form.closest('.modal')) return;

          var methodInput = form.querySelector('input[name="_method"]');
          var isDeleteMethod = (methodInput && methodInput.value.toUpperCase() === 'DELETE') || 
                               (form.getAttribute('method') && form.getAttribute('method').toUpperCase() === 'DELETE');

          if (isDeleteMethod) {
            e.preventDefault();
            e.stopPropagation();
            targetFormToSubmit = form;
            var customMsg = form.dataset.confirmMessage || 'Are you sure you want to delete this item?';
            var msgEl = document.getElementById('globalDeleteConfirmMessage');
            if (msgEl) msgEl.textContent = customMsg;

            if (globalDeleteModalEl && typeof bootstrap !== 'undefined') {
              if (!globalDeleteModal) {
                globalDeleteModal = new bootstrap.Modal(globalDeleteModalEl);
              }
              globalDeleteModal.show();
            } else if (confirm(customMsg)) {
              form.dataset.confirmed = 'true';
              form.submit();
            }
          }
        }, true);
      });
    </script>

    @stack('scripts')

  </body>
</html>