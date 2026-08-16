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
    <link href="/assets/css/datepicker.min.css" rel="stylesheet">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="/assets/css/magnific-popup.css">
    <style>
        .venue-details-section {
            background-color: #fcfcfc;
            padding: 60px 0;
        }
        .page-title-banner {
            padding: 120px 0 80px;
            position: relative;
        }
        .page-title-banner h1 {
            font-size: 3.5rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            text-shadow: 2px 2px 10px rgba(0,0,0,0.5);
            color: #fff;
        }
        /* Sticky Booking Card */
        .booking-card-wrapper {
            position: sticky;
            top: 100px;
        }
        .booking-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.06);
            border: none;
            overflow: hidden;
        }
        .booking-card-header {
            background: linear-gradient(135deg, #2b2b2b, #444);
            color: white;
            padding: 25px;
            text-align: center;
        }
        .booking-card-header h3 {
            color: white;
            margin: 0;
            font-weight: 700;
            font-size: 1.4rem;
        }
        .booking-card-body {
            padding: 30px;
        }
        
        /* Package Cards */
        .package-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.04);
            border: 2px solid transparent;
            overflow: hidden;
            transition: all 0.3s ease;
            cursor: pointer;
            height: 100%;
        }
        .package-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(211,47,47,0.1);
        }
        .package-card.selected {
            border-color: #d32f2f;
            box-shadow: 0 10px 30px rgba(211,47,47,0.15);
        }
        .package-price {
            font-size: 1.3rem;
            font-weight: 800;
            color: #d32f2f;
            margin-bottom: 10px;
        }
        .package-features {
            list-style: none;
            padding-left: 0;
            margin-top: 15px;
        }
        .package-features li {
            margin-bottom: 8px;
            color: #555;
            display: flex;
            align-items: center;
        }
        .package-features li i {
            color: #28a745;
            margin-right: 10px;
            font-size: 0.9rem;
        }
        
        /* Form Inputs */
        .form-control {
            border-radius: 12px;
            padding: 12px 20px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            transition: all 0.3s;
        }
        .form-control:focus {
            background-color: #fff;
            border-color: #d32f2f;
            box-shadow: 0 0 0 0.2rem rgba(211, 47, 47, 0.15);
        }
        .btn-checkout {
            background: linear-gradient(135deg, #d32f2f, #ff6b6b);
            border: none;
            border-radius: 50px;
            padding: 15px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(211,47,47,0.2);
            color: white;
        }
        .btn-checkout:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211,47,47,0.4);
            color: white;
        }
        .btn-checkout:disabled {
            background: #ccc;
            box-shadow: none;
            cursor: not-allowed;
            color: #777;
        }
        
        /* Custom Radio */
        .custom-radio-wrap {
            position: relative;
        }
        .custom-radio-wrap input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        .custom-radio-box {
            display: block;
            padding: 20px;
        }

        /* Package Gallery Overlay */
        .pkg-gallery-wrapper {
            position: relative;
            overflow: hidden;
            border-top-left-radius: 14px;
            border-top-right-radius: 14px;
        }
        .pkg-gallery-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 5;
            cursor: zoom-in;
        }
        .pkg-gallery-wrapper:hover .pkg-gallery-overlay {
            opacity: 1;
        }
        .pkg-gallery-overlay i {
            font-size: 2rem;
        }
    </style>
@endpush

@push('scripts')
    <script src="/assets/js/jquery-1.12.4.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/datepicker.min.js"></script>
    <script src="/assets/js/magnific-popup.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
    <script>
        $(document).ready(function() {
            $('#booking_date').datepicker({
                startDate: new Date(),
                autoHide: true,
                format: 'yyyy-mm-dd'
            }).on('change', function() {
                checkAvailability($(this).val());
            });

            function checkAvailability(dateStr) {
                $('#availability-msg').html('<i class="fas fa-spinner fa-spin me-2"></i> Checking availability...').removeClass('text-success text-danger').addClass('text-info');
                $('#checkout-btn').prop('disabled', true);
                
                $.ajax({
                    url: '{{ route("venues.checkAvailability") }}',
                    type: 'GET',
                    data: {
                        venue_id: '{{ $venue->id }}',
                        date: dateStr
                    },
                    success: function(response) {
                        if (response.available) {
                            $('#availability-msg').html('<i class="fas fa-check-circle me-1"></i> Date is available!').removeClass('text-info text-danger').addClass('text-success');
                            if ($('input[name="package_id"]:checked').length > 0) {
                                $('#checkout-btn').prop('disabled', false);
                            }
                        } else {
                            $('#availability-msg').html('<i class="fas fa-times-circle me-1"></i> Date is already booked.').removeClass('text-info text-success').addClass('text-danger');
                        }
                    }
                });
            }

            $('input[name="package_id"]').on('change', function() {
                $('.package-card').removeClass('selected');
                $(this).siblings('.package-card').addClass('selected');
                
                if ($('#availability-msg').hasClass('text-success')) {
                    $('#checkout-btn').prop('disabled', false);
                }
            });

            // Prevent radio selection when clicking gallery
            $('.pkg-gallery-wrapper').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
            });

            // Initialize Magnific Popup for each package gallery
            $('.pkg-gallery').each(function() {
                $(this).magnificPopup({
                    delegate: 'a',
                    type: 'image',
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0,1]
                    }
                });
            });
        });
    </script>
@endpush

@section('title', $venue->name . ' - Venue & Tent Booking')
@section('meta_description', Str::limit(strip_tags($venue->description ? $venue->description : 'Book ' . $venue->name . ' at ' . config('site.name') . '. Flexible packages and event spaces.'), 155))
@section('meta_keywords', $venue->name . ', event space, book venue, wedding hall, ' . config('site.name'))
@section('canonical_url', route('venues.show', $venue->id))
@section('og_title', $venue->name . ' - ' . config('site.name'))
@section('og_description', Str::limit(strip_tags($venue->description ? $venue->description : 'Book ' . $venue->name . ' at ' . config('site.name')), 155))
@if($venue->images->count() > 0)
@section('og_image', asset('storage/' . $venue->images->first()->image_path))
@endif

@section('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "EventVenue",
  "name": "{{ e($venue->name) }}",
  "url": "{{ route('venues.show', $venue->id) }}",
  "description": "{{ e(strip_tags($venue->description ?? $venue->name)) }}"
}
</script>
@endsection

@section('header')
    <header class="header_wrap fixed-top header_with_topbar light_skin main_menu_uppercase">
        <div class="container">
            @include('partials.nav')
        </div>
    </header>
@endsection

@section('content')
<div class="breadcrumb_section background_bg overlay_bg_50 page_title_light page-title-banner" data-img-src="/assets/images/about_bg.jpg">
    <div class="container">
        <div class="row text-center">
            <div class="col-sm-12">
                <div class="page-title">
                    <h1>{{ $venue->name }}</h1>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section venue-details-section">
    <div class="container">
        @include('partials.message-bag')
        
        <div class="row mb-5">
            <div class="col-12">
                @if($venue->images->count() > 0)
                <div id="venueCarousel" class="carousel slide shadow-lg" data-ride="carousel" style="border-radius: 20px; overflow: hidden;">
                    <div class="carousel-inner">
                        @foreach($venue->images as $index => $image)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="d-block w-100" alt="Venue Image" style="height: 550px; object-fit: cover;">
                        </div>
                        @endforeach
                    </div>
                    @if($venue->images->count() > 1)
                    <a class="carousel-control-prev" href="#venueCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#venueCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <form action="{{ route('venues.checkout') }}" method="POST">
            @csrf
            <input type="hidden" name="venue_id" value="{{ $venue->id }}">
            <div class="row">
                <div class="col-lg-8 mb-5">
                    <div class="mb-4">
                        <h2 class="font-weight-bold" style="color: #2b2b2b;">Select a Package</h2>
                        <p class="text-muted">Choose the perfect package for your event.</p>
                    </div>
                    <div class="row">
                        @foreach($venue->packages as $package)
                            <div class="col-md-6 mb-4">
                                <label class="custom-radio-wrap w-100 h-100 mb-0" style="cursor: pointer;">
                                    <input type="radio" name="package_id" value="{{ $package->id }}" required>
                                    <div class="package-card">
                                        @if($package->images->count() > 0)
                                            <div class="pkg-gallery-wrapper">
                                                <div class="pkg-gallery">
                                                    @foreach($package->images as $index => $image)
                                                        @if($index == 0)
                                                            <a href="{{ asset('storage/' . $image->image_path) }}" title="{{ $package->name }} - Image {{ $index + 1 }}">
                                                                <img src="{{ asset('storage/' . $image->image_path) }}" class="w-100" style="height: 220px; object-fit: cover;">
                                                                <div class="pkg-gallery-overlay">
                                                                    <i class="fas fa-search-plus"></i>
                                                                </div>
                                                            </a>
                                                        @else
                                                            <a href="{{ asset('storage/' . $image->image_path) }}" title="{{ $package->name }} - Image {{ $index + 1 }}" style="display: none;"></a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                        <div class="custom-radio-box">
                                            <h4 class="font-weight-bold mb-2">{{ $package->name }}</h4>
                                            <div class="package-price">
                                                {!! $site_settings->currency_symbol !!}{{ number_format($package->price, 2) }}
                                            </div>
                                            <ul class="package-features">
                                                @foreach($package->features as $feature)
                                                    <li><i class="fas fa-check"></i> {{ $feature->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="booking-card-wrapper">
                        <div class="booking-card">
                            <div class="booking-card-header">
                                <h3><i class="far fa-calendar-check me-2"></i> Book This Venue</h3>
                            </div>
                            <div class="booking-card-body">
                                @auth
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">Pick a Date <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="booking_date" id="booking_date" placeholder="Select Event Date" required readonly style="background-color: #fff; cursor: pointer;">
                                        <small id="availability-msg" class="font-weight-bold mt-2 d-block"></small>
                                    </div>
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold text-dark">Phone Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_phone" required placeholder="Enter your phone number" value="{{ auth()->user()->phone_number }}">
                                    </div>
                                    
                                    <div class="alert alert-info border-0 mt-2 mb-4" style="background-color: #f0f8ff; border-radius: 10px;">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <small>A <strong>{{ $venue->deposit_percentage }}% deposit</strong> is required to secure your booking.</small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-checkout w-100" id="checkout-btn" disabled>
                                        Proceed to Payment <i class="fas fa-lock ml-2"></i>
                                    </button>
                                @else
                                    <div class="alert alert-warning border-0 mb-4" style="border-radius: 10px;">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        <small>You need to be logged in to pick a date and book this venue.</small>
                                    </div>
                                    <a href="{{ route('auth.login') }}" class="btn btn-secondary w-100 text-center" style="border-radius: 50px; padding: 15px; font-weight: 600; display: block; text-decoration: none; background-color: #444; color: white;">
                                        Login to Book <i class="fas fa-sign-in-alt ml-2"></i>
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
