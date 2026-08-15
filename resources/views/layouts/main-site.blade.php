<!DOCTYPE html>
<html lang="en">

<head>
<!-- Meta -->
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="{{ config('site.name') }}" name="author">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Best restaurant experience, don't miss out on {{ config('site.name') }}.">
<meta name="keywords" content="African food, Fast Food, cafe, bar, BBQ, restaurant, sushi, steakhouse, pizza, Mexican Food, menu, meat, Breakfast, Lunch, Dinner, Delicious, Tasty, Snack, Wine, Cola">

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- SITE TITLE -->
<title>{{ config('site.name') }} - @yield('title')</title>
@include('partials.pwa-head')

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