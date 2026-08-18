@extends('layouts.main-site')

@push('styles')
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/css/themify-icons.css">
    <link rel="stylesheet" href="/assets/css/linearicons.css">
    <link rel="stylesheet" href="/assets/css/flaticon.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <link id="layoutstyle" rel="stylesheet" href="/assets/color/theme-red.css">
    <style>
        .room-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: #fff;
            margin-bottom: 2.5rem;
            position: relative;
        }
        .room-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.15);
        }
        .room-image-wrapper {
            position: relative;
            overflow: hidden;
            height: 280px;
        }
        .room-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .room-card:hover .room-img {
            transform: scale(1.1);
        }
        .room-price-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 8px 18px;
            border-radius: 30px;
            font-weight: 700;
            color: #d32f2f;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 2;
            font-size: 1.1rem;
        }
        .room-details {
            padding: 30px;
        }
        .room-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }
        .room-description {
            color: #666;
            margin-bottom: 25px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.6;
        }
        .room-amenities {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid #eee;
        }
        .amenity-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #555;
            font-size: 0.95rem;
            font-weight: 500;
        }
        .amenity-item i {
            color: #d32f2f;
            font-size: 1.2rem;
        }
        .btn-book-room {
            display: block;
            width: 100%;
            padding: 12px;
            border-radius: 50px;
            background: linear-gradient(135deg, #d32f2f 0%, #ff4d4d 100%);
            color: white;
            text-align: center;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
        }
        .btn-book-room:hover {
            background: linear-gradient(135deg, #ff4d4d 0%, #d32f2f 100%);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.3);
            text-decoration: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="/assets/js/jquery-1.12.4.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
@endpush

@section('title', 'Rooms & Accommodation')
@section('meta_description', 'Book luxurious, comfortable rooms and accommodations at ' . config('site.name') . '. Enjoy premium amenities and serene atmosphere.')
@section('meta_keywords', 'Rooms, accommodation, hotel rooms, lodge, Kigali stay, book room, ' . config('site.name'))
@section('canonical_url', route('rooms.index'))

@section('schema')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Accommodation",
  "name": "Rooms & Accommodation - {{ config('site.name') }}",
  "url": "{{ route('rooms.index') }}",
  "description": "Premium luxury rooms available for booking."
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
                    <h1 class="font-weight-bold" style="font-size: 3.5rem; text-shadow: 2px 2px 8px rgba(0,0,0,0.3);">Experience Luxury</h1>
                </div>
                <p style="font-size: 1.25rem; max-width: 700px; margin: 20px auto 30px auto; color: #fff; text-shadow: 1px 1px 4px rgba(0,0,0,0.5);">Choose from our selection of premium rooms designed for your ultimate comfort and relaxation.</p>
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Rooms</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="section pb_70">
    <div class="container">
        @include('partials.message-bag')

        <div class="row">
            @forelse($rooms as $room)
                <div class="col-lg-4 col-md-6">
                    <div class="room-card">
                        <div class="room-image-wrapper">
                            <div class="room-price-badge">
                                {!! $site_settings->currency_symbol !!}{{ number_format($room->price, 2) }} <span style="font-size: 0.8rem; font-weight: normal; color: #666;">/ Night</span>
                            </div>
                            <img src="{{ $room->image_url }}" class="room-img" alt="{{ $room->name }}" onerror="this.onerror=null;this.src='/assets/images/placeholder.jpg';">
                        </div>
                        <div class="room-details">
                            <h3 class="room-title">{{ $room->name }}</h3>
                            <p class="room-description">{{ $room->description }}</p>
                            
                            <div class="room-amenities">
                                <div class="amenity-item" title="Capacity">
                                    <i class="fas fa-user-friends"></i> {{ $room->capacity }} Person(s)
                                </div>
                                <div class="amenity-item" title="Deposit Required">
                                    <i class="fas fa-wallet"></i> {{ $room->deposit_percentage }}% Deposit
                                </div>
                            </div>
                            
                            <a href="{{ route('rooms.show', $room->id) }}" class="btn-book-room">
                                View Details & Book <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="p-5 bg-light rounded" style="border: 2px dashed #ccc;">
                        <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No rooms currently available.</h4>
                        <p>Please check back later or contact us for more information.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
