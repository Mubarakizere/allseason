@extends('layouts.main-site')

@section('title', 'Sign In — All The Season Garden')

@push('styles')
<link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/assets/css/all.min.css">

<style>
    /* Hide main site chrome */
    header.header_wrap, footer, .footer_top, .bottom_footer, #preloader {
        display: none !important;
    }
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow-x: hidden;
        background: #fff;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* ── Layout ── */
    .login-wrap {
        display: flex;
        min-height: 100vh;
    }

    /* ── Left Panel ── */
    .login-panel {
        flex: 1;
        position: relative;
        background: url('/assets/images/banner2.jpg') center/cover no-repeat;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 40px 48px;
        min-height: 100vh;
    }
    .login-panel::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(160deg, rgba(10,10,10,0.72) 0%, rgba(180,20,20,0.48) 100%);
    }
    .login-panel > * {
        position: relative;
        z-index: 1;
    }
    .panel-logo {
        font-size: 16px;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
        letter-spacing: -0.2px;
    }
    .panel-logo span {
        color: #f87171;
    }
    .panel-body h1 {
        font-size: clamp(26px, 3.2vw, 40px);
        font-weight: 700;
        color: #fff;
        line-height: 1.25;
        margin: 0 0 14px;
        letter-spacing: -0.5px;
    }
    .panel-body p {
        font-size: 15px;
        color: rgba(255,255,255,0.6);
        margin: 0;
        max-width: 400px;
        line-height: 1.65;
    }
    .panel-footer {
        font-size: 12px;
        color: rgba(255,255,255,0.35);
    }

    /* ── Right / Form Panel ── */
    .login-form-wrap {
        width: 440px;
        min-width: 340px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px 48px;
        background: #fff;
        border-left: 1px solid #f0f0f0;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: #9ca3af;
        text-decoration: none;
        margin-bottom: 40px;
        transition: color 0.12s;
    }
    .back-link:hover {
        color: #374151;
    }
    .form-heading h2 {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 6px;
        letter-spacing: -0.4px;
    }
    .form-heading p {
        font-size: 14px;
        color: #9ca3af;
        margin: 0 0 28px;
    }

    /* ── Fields ── */
    .field-label {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }
    .field-label label {
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        margin: 0;
    }
    .field-label a {
        font-size: 12px;
        color: #dc2626;
        text-decoration: none;
    }
    .field-label a:hover {
        text-decoration: underline;
    }
    .login-input {
        width: 100%;
        height: 44px;
        padding: 0 14px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        color: #111827;
        background: #fff;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s;
        box-sizing: border-box;
    }
    .login-input:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }
    .pw-wrap {
        position: relative;
    }
    .pw-wrap .login-input {
        padding-right: 42px;
    }
    .pw-toggle {
        position: absolute;
        right: 13px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #9ca3af;
        font-size: 14px;
        line-height: 1;
        background: none;
        border: none;
        padding: 0;
    }
    .pw-toggle:hover {
        color: #374151;
    }

    /* ── Button ── */
    .btn-signin {
        width: 100%;
        height: 44px;
        background: #dc2626;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.15s;
        margin-top: 24px;
    }
    .btn-signin:hover {
        background: #b91c1c;
    }

    /* ── Bottom link ── */
    .form-bottom {
        margin-top: 20px;
        text-align: center;
        font-size: 13px;
        color: #9ca3af;
    }
    .form-bottom a {
        color: #dc2626;
        font-weight: 600;
        text-decoration: none;
    }
    .form-bottom a:hover {
        text-decoration: underline;
    }

    /* ── Mobile ── */
    @media (max-width: 767px) {
        .login-panel { display: none; }
        .login-form-wrap {
            width: 100%;
            min-width: 0;
            padding: 40px 28px;
            border-left: none;
        }
        .mobile-brand {
            display: block;
            margin-bottom: 32px;
        }
    }
    @media (min-width: 768px) {
        .mobile-brand { display: none; }
    }
</style>
@endpush

@push('scripts')
<script>
    document.getElementById('pwToggle').addEventListener('click', function () {
        var input = document.getElementById('password');
        var isText = input.type === 'text';
        input.type = isText ? 'password' : 'text';
        this.className = isText ? 'fas fa-eye pw-toggle' : 'fas fa-eye-slash pw-toggle';
    });
</script>
@endpush

@section('header')
@endsection

@section('content')
<div class="login-wrap">

    {{-- Left: Image Panel --}}
    <div class="login-panel d-none d-md-flex">
        <a href="{{ route('home') }}" class="panel-logo">
            All The Season <span>Garden</span>
        </a>
        <div class="panel-body">
            <h1>Your restaurant,<br>fully in control.</h1>
            <p>Manage orders, tables, kitchen, bar, payroll and more — from one clean dashboard.</p>
        </div>
        <p class="panel-footer">&copy; {{ date('Y') }} All The Season Garden. All rights reserved.</p>
    </div>

    {{-- Right: Form --}}
    <div class="login-form-wrap">

        {{-- Mobile-only brand --}}
        <a href="{{ route('home') }}" class="panel-logo mobile-brand" style="color:#111827; font-size:15px;">
            All The Season <span style="color:#dc2626;">Garden</span>
        </a>

        <a href="{{ route('home') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to website
        </a>

        <div class="form-heading">
            <h2>Sign in</h2>
            <p>Enter your email and password to continue.</p>
        </div>

        @include('partials.message-bag')

        <form method="post" action="{{ route('auth.login.process') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-3">
                <div class="field-label">
                    <label for="email">Email address</label>
                </div>
                <input id="email" class="login-input" type="email" name="email"
                       value="{{ old('email') }}" placeholder="you@example.com" required autocomplete="email">
            </div>

            {{-- Password --}}
            <div class="mb-0">
                <div class="field-label">
                    <label for="password">Password</label>
                    <a href="{{ route('auth.password.request') }}">Forgot password?</a>
                </div>
                <div class="pw-wrap">
                    <input id="password" class="login-input" type="password" name="password"
                           placeholder="••••••••" required autocomplete="current-password">
                    <i class="fas fa-eye pw-toggle" id="pwToggle" role="button" tabindex="0" aria-label="Toggle password visibility"></i>
                </div>
            </div>

            <button type="submit" class="btn-signin">Sign in</button>

            <p class="form-bottom">
                No account? <a href="{{ route('customer.account.create') }}">Create one</a>
            </p>
        </form>
    </div>

</div>
@endsection
