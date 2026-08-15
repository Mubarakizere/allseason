<style>
    .attr-nav .account_trigger {
        display: inline-flex !important;
        align-items: center !important;
        white-space: nowrap !important;
    }
    .attr-nav .user_greeting {
        text-transform: capitalize !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        margin-right: 5px !important;
        letter-spacing: 0px !important;
    }
    .navbar-expand-lg .navbar-nav .nav-link {
        padding-left: 10px !important;
        padding-right: 10px !important;
        font-size: 13px !important;
    }
    @media (max-width: 991.98px) {
    header.header_wrap.nav-fixed,
    header.header_wrap.menu_open,
    header.header_wrap:has(.navbar-collapse.show) {
        background-color: #1a1d20 !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4) !important;
    }

    .breadcrumb_section {
        padding-top: 140px !important;
        padding-bottom: 50px !important;
    }

        .header_wrap .navbar {
            display: flex !important;
            flex-wrap: wrap !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding-top: 10px !important;
            padding-bottom: 10px !important;
        }

        .navbar-brand {
            float: none !important;
            margin-right: auto !important;
            max-width: 50% !important;
            display: flex !important;
            align-items: center !important;
            order: 1 !important;
        }

        .navbar-brand h4 {
            color: #ffffff !important;
            font-size: 0.92rem !important;
            letter-spacing: 0px !important;
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
            margin: 0 !important;
        }
        .navbar-brand .logo_dark {
            display: none !important;
        }
        .navbar-brand .logo_light {
            display: block !important;
        }

        .header_wrap .navbar .d-flex.order-lg-last {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            float: none !important;
            margin: 0 !important;
            gap: 4px !important;
            order: 2 !important;
        }

        .header_wrap .navbar .navbar-nav.attr-nav {
            display: flex !important;
            flex-direction: row !important;
            align-items: center !important;
            float: none !important;
            margin-bottom: 0 !important;
            padding-right: 0 !important;
        }

        .attr-nav .nav-link,
        .attr-nav .nav-link i,
        .navbar-toggler,
        .navbar-toggler span {
            color: #ffffff !important;
        }
        .attr-nav .nav-link {
            padding-left: 5px !important;
            padding-right: 5px !important;
            display: flex !important;
            align-items: center !important;
        }
        .navbar-toggler {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            float: none !important;
            margin: 0 0 0 4px !important;
            padding: 4px 8px !important;
            height: 36px !important;
            border-color: rgba(255,255,255,0.3) !important;
        }

        /* Mobile Dropdown Menu Container (Takes 100% width on Row 2) */
        .header_wrap .navbar-collapse {
            order: 3 !important;
            width: 100% !important;
            flex-basis: 100% !important;
            position: relative !important;
            left: 0 !important;
            right: 0 !important;
            top: auto !important;
            background-color: #1a1d20 !important;
            border-radius: 8px !important;
            padding: 10px 15px !important;
            box-shadow: 0 10px 25px rgba(0,0,0,0.5) !important;
            margin-top: 12px !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
        }
        .header_wrap .navbar-collapse .navbar-nav {
            width: 100% !important;
            display: block !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .header_wrap .navbar-collapse .navbar-nav .nav-link {
            color: #ffffff !important;
            padding: 12px 10px !important;
            font-size: 14px !important;
            font-weight: 600 !important;
            border-bottom: 1px solid rgba(255,255,255,0.08) !important;
            display: block !important;
            width: 100% !important;
            text-align: left !important;
        }
        .header_wrap .navbar-collapse .navbar-nav li:last-child .nav-link {
            border-bottom: none !important;
        }
        .header_wrap .navbar-collapse .navbar-nav .nav-link:hover,
        .header_wrap .navbar-collapse .navbar-nav .nav-link.active {
            color: #FF324D !important;
        }
    }
    @media (max-width: 575.98px) {
        .navbar-brand h4 {
            font-size: 0.85rem !important;
            max-width: 140px;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggler = document.querySelector('.navbar-toggler');
        var header = document.querySelector('header.header_wrap');
        if (toggler && header) {
            toggler.addEventListener('click', function() {
                header.classList.toggle('menu_open');
            });
        }
    });
</script>
<nav class="navbar navbar-expand-lg"> 
    <a class="navbar-brand" href="{{ route('home') }}">
        <h4 class="mb-0 font-weight-bold text-white logo_light">All The Season Garden</h4>
        <h4 class="mb-0 font-weight-bold text-dark logo_dark">All The Season Garden</h4>
    </a>
    <div class="d-flex align-items-center order-lg-last">
        <ul class="navbar-nav attr-nav align-items-center flex-row mb-0 pr-2">
            <li class="nav-item">
                <a class="nav-link account_trigger px-2 d-inline-flex align-items-center" href="#" style="white-space: nowrap;">
                    @auth
                        <span class="user_greeting mr-1 d-none d-md-inline-block">Hi, {{ Auth::user()->first_name }}</span>
                    @endauth
                    <i class="linearicons-user"></i>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link px-2 {{ Request::routeIs('cart') ? 'active' : '' }}" href="{{ route('customer.cart') }}">
                    <i class="linearicons-cart"></i>
                    <span class="cart_count" id="cart_count">{{ $customer_total_cart_items ?? 0 }}</span>
                </a>
            </li>
        </ul>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-expanded="false"> 
            <span class="ion-android-menu"></span>
        </button>
    </div>

    <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li>  <a href="{{ route('home') }}" class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}">Home</a> </li>
            <li>  <a href="{{ route('menu') }}" class="nav-link {{ Request::is('menu*') ? 'active' : '' }}">Menu</a> </li>
            <li>  <a href="{{ route('venues.index') }}" class="nav-link {{ Request::is('venues*') ? 'active' : '' }}">Wedding & Events</a> </li>
            <li>  <a href="{{ route('rooms.index') }}" class="nav-link {{ Request::is('rooms*') ? 'active' : '' }}">Accommodation / Rooms</a> </li>
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

