
@extends('layouts.main-site')

@section('title', 'Create Account')

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
    header.header_wrap, footer, .footer_top, .bottom_footer, #preloader, .breadcrumb_section {
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
        background: linear-gradient(135deg, rgba(20, 20, 20, 0.75) 0%, rgba(255, 50, 77, 0.55) 100%), url('/assets/images/banner2.jpg') center/cover no-repeat;
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
        max-width: 520px;
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
        height: 46px !important;
        padding-left: 14px !important;
        font-size: 14px !important;
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
            const targetInput = $($(this).data('target'));
            const type = targetInput.attr('type') === 'password' ? 'text' : 'password';
            targetInput.attr('type', type);
            $(this).toggleClass('fa-eye fa-eye-slash');
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
        <div class="col-lg-5 d-none d-lg-flex auth-bg-side">
            <div>
                <a href="{{ route('home') }}" class="d-inline-block text-decoration-none">
                    <h3 class="font-weight-bold text-white mb-0">All The Season Garden</h3>
                </a>
            </div>
            <div>
                <span class="badge bg-danger px-3 py-2 rounded-pill mb-3" style="font-size: 12px; letter-spacing: 1px;">CREATE YOUR ACCOUNT</span>
                <h1 class="display-6 font-weight-bold text-white mb-3">Join Us For Extraordinary Experiences</h1>
                <p class="text-white-50 lead mb-4" style="font-size: 16px;">Sign up today to place fast food orders, reserve luxury rooms, and book unforgettable event venues.</p>
                <div class="d-flex flex-column gap-2">
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa fa-check-circle text-danger me-2" style="font-size: 18px;"></i>
                        <span class="text-white">Instant Online Table & Food Ordering</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="fa fa-check-circle text-danger me-2" style="font-size: 18px;"></i>
                        <span class="text-white">Personal Customer Account Dashboard</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle text-danger me-2" style="font-size: 18px;"></i>
                        <span class="text-white">Exclusive Discounts & Offers</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-white-50 mb-0 small">&copy; {{ date('Y') }} All The Season Garden. All Rights Reserved.</p>
            </div>
        </div>

        <!-- RIGHT SIDE: Form Container (Light Theme) -->
        <div class="col-lg-7 col-12 auth-form-side">
            <div class="mb-4 text-center d-lg-none">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <h4 class="font-weight-bold text-dark mb-1">All The Season Garden</h4>
                </a>
            </div>

            <div class="mb-4 w-100 text-start" style="max-width: 520px;">
                <a href="{{ route('home') }}" class="text-muted text-decoration-none small">
                    <i class="fa fa-arrow-left me-1"></i> Back to Website
                </a>
            </div>

            <div class="auth-card">
                <h3 class="font-weight-bold text-dark mb-1">Create Account</h3>
                <p class="text-muted small mb-4">Fill in your information to get started.</p>

                @include('partials.message-bag')

                <form method="post" action="{{ route('customer.account.store') }}">
                    @csrf

                    <div class="row">
                        <!-- First Name -->
                        <div class="form-group mb-3 col-md-6">
                            <label for="first_name" class="text-dark small font-weight-bold mb-1">First Name</label>
                            <input id="first_name" class="form-control form-control-light" required type="text" name="first_name" value="{{ old('first_name') }}" placeholder="John">
                        </div>

                        <!-- Last Name -->
                        <div class="form-group mb-3 col-md-6">
                            <label for="last_name" class="text-dark small font-weight-bold mb-1">Last Name</label>
                            <input id="last_name" class="form-control form-control-light" required type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Doe">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-3">
                        <label for="email" class="text-dark small font-weight-bold mb-1">Email Address</label>
                        <input id="email" class="form-control form-control-light" required type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
                    </div>

                    <!-- Phone Number -->
                    <div class="form-group mb-3">
                        <label for="phone_number" class="text-dark small font-weight-bold mb-1">Phone Number</label>
                        <input id="phone_number" class="form-control form-control-light" required type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="+250 780 000 000">
                    </div>

                    <div class="row">
                        <!-- Password -->
                        <div class="form-group mb-3 col-md-6">
                            <label for="password" class="text-dark small font-weight-bold mb-1">Password</label>
                            <div class="input-group">
                                <input id="password" class="form-control form-control-light" style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;" required type="password" name="password" placeholder="••••••••">
                                <span class="input-group-text input-group-text-light">
                                    <i class="fas fa-eye toggle-password" data-target="#password" style="cursor:pointer;"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group mb-3 col-md-6">
                            <label for="password_confirmation" class="text-dark small font-weight-bold mb-1">Confirm Password</label>
                            <div class="input-group">
                                <input id="password_confirmation" class="form-control form-control-light" style="border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important;" required type="password" name="password_confirmation" placeholder="••••••••">
                                <span class="input-group-text input-group-text-light">
                                    <i class="fas fa-eye toggle-password" data-target="#password_confirmation" style="cursor:pointer;"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Submission -->
                    <div class="form-group mb-4 mt-3">
                        <button type="submit" class="btn btn-brand w-100">Create Account</button>
                    </div>

                    <!-- Login Link -->
                    <p class="text-center text-muted small mb-0">
                        Already have an account? <a href="{{ route('auth.login') }}" class="text-danger font-weight-bold text-decoration-none">Login here</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
