<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title')</title>
  @include('partials.pwa-head')
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-6 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo text-center mb-4">
                <img src="/favicon_io/android-chrome-192x192.png" alt="All Season Garden" style="width: 64px; height: 64px; border-radius: 14px; object-fit: cover; margin-bottom: 12px; box-shadow: 0 6px 16px rgba(0,0,0,0.12);">
                <h3 class="font-weight-bold" style="color: #2d4a3e; font-size: 1.25rem; margin: 0;">All Season Garden</h3>
              </div>

              @yield('content')
            
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <script src="/admin_resources/vendors/js/vendor.bundle.base.js"></script>
  <script src="/admin_resources/js/off-canvas.js"></script>
  <script src="/admin_resources/js/hoverable-collapse.js"></script>
  <script src="/admin_resources/js/template.js"></script>
  <script src="/admin_resources/js/settings.js"></script>
  <script src="/admin_resources/js/todolist.js"></script>
  @stack('script')
</body>
</html>
