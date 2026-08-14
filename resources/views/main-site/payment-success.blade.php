@extends('layouts.main-site')

@push('styles')
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        #preloader { display: none !important; }
    </style>
@endpush

@push('scripts')
    <script src="/assets/js/jquery-1.12.4.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
    <script>
    $(document).ready(function() {
        $('#cart_count').text(0);
    });
    </script>
@endpush

@section('title', 'Order Successful')

@section('header')
    <header class="header_wrap fixed-top header_with_topbar light_skin main_menu_uppercase">
        <div class="container">
            @include('partials.nav')
        </div>
    </header>
@endsection

@section('content')
<div class="section pt-5 pb-5 mt-5">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 text-center">
                <div class="mb-4">
                    <i class="far fa-check-circle text-success" style="font-size: 5rem;"></i>
                </div>
                <h2 class="mb-3">Order Successful</h2>
                <p class="text-muted mb-4">Thank you for your order, {{ $order->customer->first_name }}. We're preparing your delicious meal.</p>
                
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4 bg-light rounded">
                        <p class="mb-1 text-muted">Your order number is:</p>
                        <h3 class="text-danger mb-3">#{{ $order->order_no }}</h3>
                        <p class="mb-0 text-muted small">An email confirmation has been sent to <strong>{{ $order->customer->email }}</strong>.</p>
                    </div>
                </div>

                <p class="text-muted small mb-4">
                    If you have any questions, please contact us at 
                    @if($firstRestaurantPhoneNumber)
                        <a href="tel:{{ $firstRestaurantPhoneNumber->phone_number }}">{{ $firstRestaurantPhoneNumber->phone_number }}</a>
                    @endif
                    or email us at <a href="mailto:{{ config('site.email') }}">{{ config('site.email') }}</a>.
                </p>
                
                <a href="{{ route('home') }}" class="btn btn-danger px-4 py-2">Return to Homepage</a>
            </div>
        </div>
    </div>
</div>
@endsection

 