<style>
    @media (max-width: 575.98px) {
        .navbar-brand h4 {
            font-size: 0.95rem !important;
            letter-spacing: 0px !important;
            white-space: normal !important;
            line-height: 1.2 !important;
        }
        .navbar-brand {
            margin-right: auto !important;
            max-width: 55%;
        }
        .attr-nav .nav-link {
            padding-left: 6px !important;
            padding-right: 6px !important;
        }
        .navbar-toggler {
            padding: 0.25rem 0.4rem !important;
            margin-left: 5px;
        }
    }
</style>
<nav class="navbar navbar-expand-lg"> 
    <a class="navbar-brand" href="{{ route('home') }}">
        <h4 class="mb-0 font-weight-bold text-white logo_light">All The Season Garden</h4>
        <h4 class="mb-0 font-weight-bold text-dark logo_dark">All The Season Garden</h4>
    </a>
    <div class="d-flex align-items-center order-lg-last">
        <ul class="navbar-nav attr-nav align-items-center flex-row mb-0 pr-2">
            <li class="nav-item">
                <a class="nav-link account_trigger px-2" href="#">
                    @auth
                        <span class="mr-1 d-none d-md-inline-block">Hi, {{ Auth::user()->first_name }}</span>
                    @endauth
                    <i class="linearicons-user"></i>
                </a>
            </li>

            @php
                $user = Auth::user();
                $showCart = !$user || $user->role === 'customer'; // show for guest or customer
            @endphp

            @if ($showCart)
                <li class="nav-item">
                    <a class="nav-link px-2 {{ Request::routeIs('cart') ? 'active' : '' }}" href="{{ route('customer.cart') }}">
                        <i class="linearicons-cart"></i>
                        <span class="cart_count" id="cart_count">{{ $customer_total_cart_items ?? 0 }}</span>
                    </a>
                </li>
            @endif
        </ul>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-expanded="false"> 
            <span class="ion-android-menu"></span>
        </button>
    </div>

    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li>  <a href="{{ route('home') }}" class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}">Home</a> </li>
            <li>  <a href="{{ route('menu') }}" class="nav-link {{ Request::is('menu*') ? 'active' : '' }}">Menu</a> </li>
            <li>  <a href="{{ route('venues.index') }}" class="nav-link {{ Request::is('venues*') ? 'active' : '' }}">Venues</a> </li>
            <li>  <a href="{{ route('rooms.index') }}" class="nav-link {{ Request::is('rooms*') ? 'active' : '' }}">Rooms</a> </li>
            <li>  <a href="{{ route('about') }}" class="nav-link {{ Request::routeIs('about') ? 'active' : '' }}">About</a> </li>
            <li> <a href="{{ route('contact') }}" class="nav-link {{ Request::routeIs('contact') ? 'active' : '' }}">Contact</a> </li>
        </ul>
    </div>


    @if($firstRestaurantPhoneNumber)  
    <div class="header_btn d-sm-block d-none">
        <a href="tel:{{ $firstRestaurantPhoneNumber->phone_number }}" class="btn btn-default rounded-0 ml-2 btn-sm"><i class="fa fa-phone"></i> CALL US</a>
    </div>  
    @endif

</nav>

