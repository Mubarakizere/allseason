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
@endpush

@section('title', 'Booking Confirmed')

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
                <h2 class="mb-3">Booking Confirmed</h2>
                <p class="text-muted mb-4">Thank you, {{ $booking->customer_name }}. Your booking for <strong>{{ $booking->venue->name }}</strong> is confirmed.</p>
                
                <div class="card shadow-sm border-0 mb-4 text-left">
                    <div class="card-body p-4 bg-light rounded">
                        <h5 class="card-title border-bottom pb-2 mb-3">Booking Details</h5>
                        <div class="row mb-2">
                            <div class="col-sm-5 text-muted">Venue</div>
                            <div class="col-sm-7 font-weight-bold">{{ $booking->venue->name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 text-muted">Package</div>
                            <div class="col-sm-7">{{ $booking->package->name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 text-muted">Date</div>
                            <div class="col-sm-7">{{ \Carbon\Carbon::parse($booking->booking_date)->format('F d, Y') }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-sm-5 text-muted">Total Price</div>
                            <div class="col-sm-7">{!! $site_settings->currency_symbol !!}{{ number_format($booking->total_price, 2) }}</div>
                        </div>
                        <div class="row pt-2 mt-2 border-top">
                            <div class="col-sm-5 text-success font-weight-bold">Deposit Paid</div>
                            <div class="col-sm-7 text-success font-weight-bold">{!! $site_settings->currency_symbol !!}{{ number_format($booking->deposit_amount, 2) }}</div>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('home') }}" class="btn btn-primary px-4 py-2">Return to Homepage</a>
            </div>
        </div>
    </div>
</div>
@endsection
