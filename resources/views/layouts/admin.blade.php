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

    @stack('scripts')

  </body>
</html>