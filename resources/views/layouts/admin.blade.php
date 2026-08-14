<!DOCTYPE html>
<html lang="en">
  <head>
     <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>

    @stack('styles')
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">

    <!-- endinject -->
    <link rel="shortcut icon" href="/favicon_io/favicon.ico" />
  </head>
  <body>
 
    <div class="container-scroller">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="{{ route('admin.dashboard') }}" style="text-decoration: none;"><h6 class="mb-0 font-weight-bold" style="color: inherit;">All The Season Garden</h6></a>
          <a class="navbar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}" style="text-decoration: none;"><h6 class="mb-0 font-weight-bold" style="color: inherit;">ATSG</h6></a>
          <button class="navbar-toggler navbar-toggler align-self-center d-none d-lg-flex" type="button" data-toggle="minimize">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
 
          <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item d-none d-lg-flex  mr-2">  <a class="nav-link" href="{{ route('admin.view.myprofile') }}"> <i class="typcn typcn-user-outline mr-0"></i> {{ $loggedInUser->first_name }}  </a> </li>
            <li class="nav-item d-none d-lg-flex  mr-2">  <a class="nav-link" href="{{ route('auth.logout') }}"> <i class="typcn typcn-power-outline mr-0"></i> Logout  </a> </li>
         
         

          </ul>

          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="typcn typcn-th-menu"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">


        @include('partials.admin.sidebar')
        
        @yield('content')

        @include('partials.logout')

      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
 
    @if(session('auto_print_receipt_url'))
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            window.open("{{ session('auto_print_receipt_url') }}", '_blank', 'width=400,height=600');
        });
    </script>
    @endif

    @stack('scripts')

  </body>
</html>