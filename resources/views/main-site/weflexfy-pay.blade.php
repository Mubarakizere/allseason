@extends('layouts.main-site')

@section('title', 'Complete Payment')
@section('meta_robots', 'noindex, nofollow')

@push('styles')
<style>
    .payment-container {
        max-width: 850px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #eef2f6;
    }
    .payment-header {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff;
        padding: 24px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .payment-header h3 {
        color: #ffffff;
        margin: 0;
        font-weight: 700;
        font-size: 1.35rem;
    }
    .payment-header .amount-badge {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        padding: 8px 18px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
    }
    .payment-body {
        padding: 24px;
        position: relative;
    }
    .iframe-wrapper {
        position: relative;
        width: 100%;
        min-height: 620px;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .payment-iframe {
        width: 100%;
        height: 620px;
        border: none;
    }
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(248, 250, 252, 0.9);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 10;
        transition: opacity 0.3s ease;
    }
    .spinner-border {
        width: 3rem;
        height: 3rem;
        color: #d97706;
    }
    .payment-status-alert {
        display: none;
        padding: 16px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="section py-5">
    <div class="container">
        <div class="payment-container">
            <div class="payment-header">
                <div>
                    <h3>{{ $title ?? 'Complete Your Payment' }}</h3>
                    <small class="text-white-50">Secure Mobile Money & Card Payment via WeFlexfy</small>
                </div>
                <div class="amount-badge">
                    {!! $currencySymbol ?? 'RWF' !!} {{ number_format($amount, 2) }}
                </div>
            </div>

            <div class="payment-body">
                <div id="paymentAlert" class="payment-status-alert"></div>

                <div class="iframe-wrapper">
                    <div id="loadingSpinner" class="loading-overlay">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading payment gateway...</span>
                        </div>
                        <p class="mt-3 font-weight-bold text-secondary">Loading payment portal...</p>
                    </div>

                    <iframe 
                        id="weflexfyIframe" 
                        src="{{ $iframeUrl }}" 
                        class="payment-iframe" 
                        allow="payment"
                        onload="document.getElementById('loadingSpinner').style::display='none';"
                    ></iframe>
                </div>

                <div class="mt-4 text-center">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.history.back()">
                        <i class="ti-arrow-left mr-1"></i> Cancel & Return
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const redirectUrl = "{{ $redirectUrl }}";
    const alertBox = document.getElementById('paymentAlert');
    const loadingSpinner = document.getElementById('loadingSpinner');

    // Hide spinner once iframe loads
    const iframe = document.getElementById('weflexfyIframe');
    iframe.onload = function() {
        if (loadingSpinner) {
            loadingSpinner.style.display = 'none';
        }
    };

    // Client-Side Event Handling (via postMessage)
    window.addEventListener('message', function (event) {
        if (event.data && event.data.type === 'PAYMENT_STATUS') {
            const status = event.data.status;
            console.log('WeFlexfy Payment PostMessage Event:', status, event.data);

            switch (status) {
                case 'init':
                    if (loadingSpinner) {
                        loadingSpinner.style.display = 'none';
                    }
                    break;

                case 'success':
                    showAlert('success', '<i class="ti-check-box mr-2"></i> Payment completed successfully! Redirecting...');
                    setTimeout(function () {
                        window.location.href = redirectUrl;
                    }, 1500);
                    break;

                case 'failed':
                    showAlert('danger', '<i class="ti-close mr-2"></i> Payment failed or was declined. Please try again.');
                    break;

                case 'close':
                    showAlert('warning', '<i class="ti-info-alt mr-2"></i> Payment window was closed before completion.');
                    break;
            }
        }
    });

    function showAlert(type, message) {
        alertBox.className = 'payment-status-alert alert alert-' + type;
        alertBox.innerHTML = message;
        alertBox.style.display = 'block';
    }
});
</script>
@endpush
