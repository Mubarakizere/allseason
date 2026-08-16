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
    <style>
        .room-details-card {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
        .booking-card {
            background: #fff;
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.08);
            position: sticky;
            top: 120px;
        }
        .booking-card .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            height: auto;
            border: 1px solid #e0e0e0;
            background-color: #f9f9f9;
        }
        .booking-card .form-control:focus {
            box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
            border-color: #d32f2f;
            background-color: #fff;
        }
        .btn-checkout {
            background: linear-gradient(135deg, #d32f2f 0%, #ff4d4d 100%);
            color: white;
            border-radius: 50px;
            padding: 15px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-checkout:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(211, 47, 47, 0.3);
            color: white;
        }
        .btn-checkout:disabled {
            background: #ccc;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
            color: #666;
        }
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 12px;
            font-weight: 500;
            color: #444;
            transition: transform 0.3s ease;
        }
        .feature-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .feature-item i {
            color: #d32f2f;
            font-size: 1.2rem;
        }
        .price-display {
            font-size: 2.2rem;
            font-weight: 700;
            color: #d32f2f;
            margin-bottom: 25px;
            display: flex;
            align-items: baseline;
            gap: 5px;
        }
        .price-display span {
            font-size: 1.1rem;
            color: #666;
            font-weight: 400;
        }
        .carousel-item img {
            height: 500px;
            object-fit: cover;
            border-radius: 20px;
        }
        .carousel-inner {
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        }
    </style>
@endpush

@push('scripts')
    <script src="/assets/js/jquery-1.12.4.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/datepicker.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
    <script>
        $(document).ready(function() {
            var datepickerOptions = {
                startDate: new Date(),
                autoHide: true,
                format: 'yyyy-mm-dd'
            };

            $('#check_in_date').datepicker(datepickerOptions).on('change', function() {
                var checkIn = $(this).val();
                $('#check_out_date').datepicker('setStartDate', checkIn);
                checkAvailability();
            });

            $('#check_out_date').datepicker(datepickerOptions).on('change', function() {
                checkAvailability();
            });

            function checkAvailability() {
                var checkIn = $('#check_in_date').val();
                var checkOut = $('#check_out_date').val();

                if (checkIn && checkOut) {
                    $('#availability-msg').text('Checking availability...').removeClass('text-success text-danger');
                    $('#checkout-btn').prop('disabled', true);
                    
                    $.ajax({
                        url: '{{ route("rooms.checkAvailability") }}',
                        type: 'GET',
                        data: {
                            room_id: '{{ $room->id }}',
                            check_in_date: checkIn,
                            check_out_date: checkOut
                        },
                        success: function(response) {
                            if (response.available) {
                                $('#availability-msg').text('Dates are available!').addClass('text-success');
                                $('#checkout-btn').prop('disabled', false);
                            } else {
                                $('#availability-msg').text(response.message || 'Dates are already booked.').addClass('text-danger');
                            }
                        }
                    });
                } else {
                    $('#availability-msg').text('').removeClass('text-success text-danger');
                    $('#checkout-btn').prop('disabled', true);
                }
            }
        });
    </script>
@endpush

@section('title', $room->name . ' - Room Booking')
@section('meta_description', Str::limit(strip_tags($room->description ? $room->description : 'Reserve ' . $room->name . ' at ' . config('site.name') . '. Luxury stay with premium amenities.'), 155))
@section('meta_keywords', $room->name . ', book room, accommodation, Kigali lodge, ' . config('site.name'))
@section('canonical_url', route('rooms.show', $room->id))
@section('og_title', $room->name . ' - ' . config('site.name'))
@section('og_description', Str::limit(strip_tags($room->description ? $room->description : 'Reserve ' . $room->name . ' at ' . config('site.name')), 155))
@if($room->image)
@section('og_image', asset('storage/' . $room->image))
@elseif($room->images->count() > 0)
@section('og_image', asset('storage/' . $room->images->first()->image))
@endif

@section('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "HotelRoom",
  "name": "{{ e($room->name) }}",
  "url": "{{ route('rooms.show', $room->id) }}",
  "description": "{{ e(strip_tags($room->description ?? $room->name)) }}",
  "occupancy": {
    "@type": "QuantitativeValue",
    "value": "{{ $room->capacity }}"
  }
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
<div class="breadcrumb_section background_bg overlay_bg_50 page_title_light" data-img-src="/assets/images/about_bg.jpg">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-sm-12">
                <div class="page-title">
                    <h1 class="font-weight-bold" style="font-size: 3.5rem; text-shadow: 2px 2px 8px rgba(0,0,0,0.3);">{{ $room->name }}</h1>
                </div>
                <ol class="breadcrumb justify-content-center mt-3">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('rooms.index') }}">Rooms</a></li>
                    <li class="breadcrumb-item active">{{ $room->name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="section pb_70">
    <div class="container">
        @include('partials.message-bag')
        
        <div class="row mb-5">
            <div class="col-12">
                @if($room->images->count() > 0 || $room->image)
                <div id="roomCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @if($room->image)
                            <div class="carousel-item active">
                                <img src="{{ asset('storage/' . $room->image) }}" class="d-block w-100" alt="Room Main Image">
                            </div>
                        @endif
                        @foreach($room->images as $index => $image)
                            <div class="carousel-item {{ !$room->image && $index == 0 ? 'active' : '' }}">
                                <img src="{{ asset('storage/' . $image->image) }}" class="d-block w-100" alt="Room Image">
                            </div>
                        @endforeach
                    </div>
                    @if($room->images->count() > 0 && ($room->image || $room->images->count() > 1))
                    <a class="carousel-control-prev" href="#roomCarousel" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#roomCarousel" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>

        <form action="{{ route('rooms.checkout') }}" method="POST">
            @csrf
            <input type="hidden" name="room_id" value="{{ $room->id }}">
            <div class="row">
                <div class="col-lg-7">
                    <div class="room-details-card">
                        <div class="price-display">
                            {!! $site_settings->currency_symbol !!}{{ number_format($room->price, 2) }} <span>/ Night</span>
                        </div>
                        
                        <h3 class="mb-4 font-weight-bold">Overview</h3>
                        <p class="text-muted" style="font-size: 1.1rem; line-height: 1.8;">{{ $room->description }}</p>
                        
                        <div class="feature-grid mt-4 mb-5">
                            <div class="feature-item">
                                <i class="fas fa-user-friends"></i>
                                <span>Capacity: {{ $room->capacity }} Person(s)</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-wallet"></i>
                                <span>{{ $room->deposit_percentage }}% Deposit Required</span>
                            </div>
                        </div>

                        @if($room->features->count() > 0)
                            <h4 class="font-weight-bold mb-4">Inclusions & Amenities</h4>
                            <div class="feature-grid">
                                @foreach($room->features as $feature)
                                    <div class="feature-item">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>{{ $feature->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="col-lg-5">
                    <div class="booking-card">
                        <h3 class="font-weight-bold mb-4">Reserve this Room</h3>
                        
                        <div class="row">
                            <div class="col-md-6 form-group mb-4">
                                <label class="font-weight-bold text-dark">Check-in Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="check_in_date" id="check_in_date" placeholder="Select Date" required readonly style="cursor: pointer;">
                            </div>
                            <div class="col-md-6 form-group mb-4">
                                <label class="font-weight-bold text-dark">Check-out Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="check_out_date" id="check_out_date" placeholder="Select Date" required readonly style="cursor: pointer;">
                            </div>
                        </div>
                        <div id="availability-msg" class="font-weight-bold mt-1 mb-4" style="min-height: 20px;"></div>

                        @auth
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Phone Number <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customer_phone" required placeholder="Enter your phone number" value="{{ auth()->user()->phone ?? '' }}">
                            </div>

                            <input type="hidden" name="customer_name" value="{{ trim(auth()->user()->first_name . ' ' . auth()->user()->last_name) }}">
                            <input type="hidden" name="customer_email" value="{{ auth()->user()->email }}">
                            
                            <div class="alert alert-info border-0 mt-2 mb-4" style="background-color: #f0f8ff; border-radius: 10px;">
                                <i class="fas fa-info-circle mr-2"></i>
                                <small>A <strong>{{ $room->deposit_percentage }}% deposit</strong> ({!! $site_settings->currency_symbol !!}{{ number_format(($room->price * $room->deposit_percentage) / 100, 2) }} per night) is required to secure your booking.</small>
                            </div>

                            <button type="submit" class="btn btn-checkout w-100" id="checkout-btn" disabled>
                                Proceed to Payment <i class="fas fa-lock ml-2"></i>
                            </button>
                        @else
                            <div class="text-center py-4">
                                <h5 class="mb-3">Please login to book this room.</h5>
                                <a href="{{ route('auth.login') }}" class="btn btn-secondary w-100 text-center text-white" style="border-radius: 50px; padding: 15px; font-weight: 600; display: block; text-decoration: none; background-color: #444;">
                                    Login to Book <i class="fas fa-sign-in-alt ml-2"></i>
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
