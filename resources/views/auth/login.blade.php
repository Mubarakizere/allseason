
@extends('layouts.main-site')

@section('title', 'Login')

@push('styles')
    <!-- Latest Bootstrap min CSS -->
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="/assets/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/css/themify-icons.css">
    <link rel="stylesheet" href="/assets/css/linearicons.css">
    <!-- Style CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <link id="layoutstyle" rel="stylesheet" href="/assets/color/theme-red.css">

<style>
    header.header_wrap, footer, .footer_top, .bottom_footer, #preloader {
        display: none !important;
    }
    html, body {
        overflow-x: hidden !important;
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background-color: #f8f9fa !important;
        color: #333333;
        min-height: 100vh;
    }
    .container-fluid {
        padding-left: 0 !important;
        padding-right: 0 !important;
        overflow-x: hidden !important;
        max-width: 100% !important;
    }
    .auth-container, .auth-container.row {
        margin-left: 0 !important;
        margin-right: 0 !important;
        max-width: 100% !important;
        width: 100% !important;
        min-height: 100vh;
    }
    .auth-bg-side {
        background: linear-gradient(135deg, rgba(20, 20, 20, 0.75) 0%, rgba(255, 50, 77, 0.55) 100%), url('/assets/images/banner1.jpg') center/cover no-repeat;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 60px;
    }
    .auth-form-side {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #ffffff;
        padding: 40px 20px;
    }
    .auth-card {
        width: 100%;
        max-width: 440px;
        background: #ffffff;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
        border: 1px solid #eef0f2;
    }
    .form-control-light {
        background: #f8f9fa !important;
        border: 1px solid #dde1e5 !important;
        color: #222222 !important;
        border-radius: 8px !important;
        height: 50px !important;
        padding-left: 16px !important;
    }
    .form-control-light:focus {
        background: #ffffff !important;
        border-color: #FF324D !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 50, 77, 0.2) !important;
    }
    .btn-brand {
        background: #FF324D !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        font-size: 15px !important;
        height: 50px !important;
        border-radius: 8px !important;
        border: none !important;
        transition: all 0.3s ease;
        box-shadow: 0 6px 18px rgba(255, 50, 77, 0.35);
    }
    .btn-brand:hover {
        background: #e0263f !important;
        box-shadow: 0 8px 25px rgba(255, 50, 77, 0.45);
        transform: translateY(-2px);
    }
    .input-group-text-light {
        background: #f8f9fa !important;
        border: 1px solid #dde1e5 !important;
        border-left: none !important;
        color: #666666 !important;
        border-top-right-radius: 8px !important;
        border-bottom-right-radius: 8px !important;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        $('.toggle-password').on('click', function() {
            const input = $('#password');
            const icon = $(this);
            const type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            icon.toggleClass('fa-eye fa-eye-slash');
        });
    });
</script>
@endpush

@section('header')
@endsection

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0 auth-container">
        <!-- LEFT SIDE: Hero Background Image -->
        <div class="col-lg-6 d-none d-lg-flex auth-bg-side">
            <div>
                <a href="{{ route('home') }}" class="d-inline-block text-decoration-none">
                    <h3 class="font-weight-bold text-white mb-0">All The Season Garden</h3>
                </a>
            </div>
            <div>
                <span class="badge bg-danger px-3 py-2 rounded-pill mb-3" style="font-size: 12px; letter-spacing: 1px;">WELCOME BACK</span>
                <h1 class="display-5 font-weight-bold text-white mb-3">Tasty African Delights & Memorable Moments</h1>
                <p class="text-white-50 lead mb-4">Log in to manage your orders, check room reservations, and explore our special dishes.</p>
                <div class="d-flex gap-3">
                    <div class="d-flex align-items-center me-4">
                        <i class="fa fa-check-circle text-danger me-2" style="font-size: 18px;"></i>
                        <span class="text-white">Authentic Cuisine</span>
                    </div>
                    <div class="d-flex align-items-center me-4">
                        <i class="fa fa-check-circle text-danger me-2" style="font-size: 18px;"></i>
                        <span class="text-white">Fast Online Ordering</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle text-danger me-2" style="font-size: 18px;"></i>
                        <span class="text-white">Luxury Accommodation</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-white-50 mb-0 small">&copy; {{ date('Y') }} All The Season Garden. All Rights Reserved.</p>
            </div>
        </div>

        <!-- RIGHT SIDE: Form Container (Light Theme) -->
        <div class="col-lg-6 col-12 auth-form-side">
            <div class="mb-4 text-center d-lg-none">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <h4 class="font-weight-bold text-dark mb-1">All The Season Garden</h4>
                </a>
            </div>

            <div class="mb-4 w-100 text-start" style="max-width: 440px;">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none small">
                    <i class="fa fa-arrow-left me-1"></i> Back to Website
                </a>
            </div>

            <div class="auth-card">
                <h3 class="font-weight-bold text-dark mb-1">Sign In</h3>
                <p class="text-muted small mb-4">Enter your credentials to access your account.</p>

                @include('partials.message-bag')

                <form method="post" action="{{ route('auth.login.process') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <label for="email" class="text-dark small font-weight-bold mb-1">Email Address</label>
                        <input id="email" class="form-control form-control-light" required type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
                    </div>

                    <!-- Password -->
                    <div class="form-group mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label for="password" class="text-dark small font-weight-bold mb-0">Password</label>
                            <a href="{{ route('auth.password.request') }}" class="text-danger small text-decoration-none">Forgot password?</a>
                        </div>
                        <div class="input-group">
                            <input id="password" class="form-control form-control-light" style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;" required type="password" name="password" placeholder="••••••••">
                            <span class="input-group-text input-group-text-light">
                                <i class="fas fa-eye toggle-password" style="cursor:pointer;"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Submission -->
                    <div class="form-group mb-4 mt-4">
                        <button type="submit" class="btn btn-brand w-100">Sign In</button>
                    </div>

                    <!-- Signup Link -->
                    <p class="text-center text-muted small mb-0">
                        Don't have an account? <a href="{{ route('customer.account.create') }}" class="text-danger font-weight-bold text-decoration-none">Create an Account</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
