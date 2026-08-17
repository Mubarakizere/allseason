<!DOCTYPE html>
<html lang="en">

<head>
<!-- Meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="{{ config('site.name') }}">
<meta name="robots" content="@yield('meta_robots', 'index, follow')">
<meta name="google-site-verification" content="KcBfw91JlS01_cVPLnGRAQY4lxKoHp4bWyRlLety5eQ" />

<!-- SEO Meta Tags -->
@hasSection('title')
<title>@yield('title') - {{ config('site.name') }}</title>
@else
<title>@yield('meta_title', config('site.name') . ' - Authentic Rwandan & African Cuisine, Restaurant & Bar')</title>
@endif

<meta name="description" content="@yield('meta_description', 'Experience the finest authentic Rwandan and African cuisine, fast food, beverages, and hospitality at ' . config('site.name') . '. Book tables, order online, or reserve event venues.')">
<meta name="keywords" content="@yield('meta_keywords', 'African food, Rwanda restaurant, Kigali dining, BBQ, bar, restaurant, food delivery, book table, venue booking, hospitality, menu, breakfast, lunch, dinner')">
<link rel="canonical" href="@yield('canonical_url', url()->current())">

<!-- Open Graph / Facebook / WhatsApp -->
<meta property="og:site_name" content="{{ config('site.name') }}">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:url" content="@yield('canonical_url', url()->current())">
<meta property="og:title" content="@yield('og_title', config('site.name') . ' - Authentic Rwandan & African Cuisine')">
<meta property="og:description" content="@yield('og_description', 'Experience the finest authentic Rwandan and African cuisine, fast food, beverages, and hospitality at ' . config('site.name') . '.')">
<meta property="og:image" content="@yield('og_image', asset('assets/images/logo.png'))">
<meta property="og:locale" content="en_US">

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('og_title', config('site.name') . ' - Authentic Rwandan & African Cuisine')">
<meta name="twitter:description" content="@yield('og_description', 'Experience the finest authentic Rwandan and African cuisine, fast food, beverages, and hospitality at ' . config('site.name') . '.')">
<meta name="twitter:image" content="@yield('og_image', asset('assets/images/logo.png'))">

<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.pwa-head')

<!-- Structured Data (Schema.org JSON-LD) -->
@hasSection('schema')
  @yield('schema')
@else
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Restaurant",
  "name": "{{ config('site.name') }}",
  "image": "{{ asset('assets/images/logo.png') }}",
  "url": "{{ url('/') }}",
  "telephone": "{{ $firstRestaurantPhoneNumber->phone_number ?? '' }}",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ $firstCompanyAddress->address ?? '' }}",
    "addressCountry": "{{ $site_settings->country ?? 'Rwanda' }}"
  },
  "priceRange": "$$",
  "servesCuisine": ["Rwandan", "African", "Continental", "Fast Food"],
  "acceptsReservations": "True"
}
</script>
@endif

<!-- Latest Bootstrap min CSS -->
<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&amp;display=swap" rel="stylesheet"> 
<!-- Icon Font CSS -->
<link rel="stylesheet" href="/assets/css/all.min.css">
<link rel="stylesheet" href="/assets/css/ionicons.min.css">
<link rel="stylesheet" href="/assets/css/themify-icons.css">
<link rel="stylesheet" href="/assets/css/linearicons.css">
<link rel="stylesheet" href="/assets/css/flaticon.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Style CSS -->
<link rel="stylesheet" href="/assets/css/style.css">
<link rel="stylesheet" href="/assets/css/responsive.css">
<link id="layoutstyle" rel="stylesheet" href="/assets/color/theme-red.css">

@stack('styles')
 <script>
     const csrfToken = "{{ csrf_token() }}";
     const addToCartUrl = "{{ route('customer.cart.add') }}";
     const removeFromCartUrl = "{{ route('customer.cart.remove') }}";
     const updateCartUrl = "{{ route('customer.cart.update') }}";
 </script>
 
</head>

<body>

<!-- LOADER -->
<div id="preloader">
	<div class="loader_wrap">
        <div class="sk-chase">
          <div class="sk-chase-dot"></div>
          <div class="sk-chase-dot"></div>
          <div class="sk-chase-dot"></div>
          <div class="sk-chase-dot"></div>
          <div class="sk-chase-dot"></div>
          <div class="sk-chase-dot"></div>
        </div>
    </div>
</div>
<!-- END LOADER --> 

<script>
    function hidePreloader() {
        var loader = document.getElementById('preloader');
        if (loader) {
            loader.style.opacity = '0';
            loader.style.transition = 'opacity 0.3s ease';
            setTimeout(function() {
                if (loader && loader.parentNode) {
                    loader.parentNode.removeChild(loader);
                }
            }, 300);
        }
    }
    if (document.readyState === 'complete') {
        hidePreloader();
    } else {
        window.addEventListener('load', hidePreloader);
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(hidePreloader, 500);
        });
        setTimeout(hidePreloader, 1200);
    }
</script>
 @yield('header')

 @include('partials.account')

 @yield('content')
 
 @include('partials.logout')

 @include('partials.footer')



@stack('scripts')

@if($liveChatScript && $liveChatScript->script_code)
    {!! $liveChatScript->script_code !!}
@endif


</body>
</html>