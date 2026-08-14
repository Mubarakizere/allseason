@extends('layouts.main-site')

@push('styles')
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/css/themify-icons.css">
    <link rel="stylesheet" href="/assets/css/linearicons.css">
    <link rel="stylesheet" href="/assets/css/flaticon.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link id="layoutstyle" rel="stylesheet" href="/assets/color/theme-red.css">
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
<div class="section mt-5 pt-5">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <i class="fa fa-check-circle text-success" style="font-size: 80px;"></i>
                <h1 class="mt-4">Booking Confirmed!</h1>
                <p class="lead">Thank you, {{ $booking->customer_name }}. Your deposit for the <strong>{{ $booking->room->name }}</strong> has been successfully processed.</p>
                <div class="card mt-4">
                    <div class="card-body text-left">
                        <h4>Booking Details</h4>
                        <ul class="list-unstyled">
                            <li><strong>Room:</strong> {{ $booking->room->name }}</li>
                            <li><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</li>
                            <li><strong>Check-out:</strong> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</li>
                            <li><strong>Total Price:</strong> {!! $site_settings->currency_symbol !!}{{ number_format($booking->total_price, 2) }}</li>
                            <li><strong>Deposit Paid:</strong> {!! $site_settings->currency_symbol !!}{{ number_format($booking->deposit_amount, 2) }}</li>
                        </ul>
                    </div>
                </div>
                <a href="{{ route('home') }}" class="btn btn-primary mt-4">Return Home</a>
            </div>
        </div>
    </div>
</div>
@endsection
