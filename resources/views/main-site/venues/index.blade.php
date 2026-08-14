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
        .venue-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            background: #fff;
            position: relative;
        }
        .venue-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(211, 47, 47, 0.15);
        }
        .venue-img-wrap {
            position: relative;
            overflow: hidden;
        }
        .venue-img-wrap img {
            transition: all 0.6s ease;
            height: 280px;
            object-fit: cover;
            width: 100%;
        }
        .venue-card:hover .venue-img-wrap img {
            transform: scale(1.08);
        }
        .venue-deposit-tag {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 8px 15px;
            border-radius: 30px;
            font-weight: 700;
            color: #d32f2f;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            font-size: 0.9rem;
            z-index: 2;
        }
        .venue-content {
            padding: 30px 25px;
            position: relative;
        }
        .venue-title {
            font-weight: 800;
            font-size: 1.4rem;
            color: #2b2b2b;
            margin-bottom: 12px;
            line-height: 1.3;
        }
        .venue-desc {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 25px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .btn-view-venue {
            background: linear-gradient(135deg, #d32f2f, #ff6b6b);
            border: none;
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
            display: inline-block;
            text-align: center;
        }
        .btn-view-venue:hover {
            background: linear-gradient(135deg, #b71c1c, #d32f2f);
            color: white;
            box-shadow: 0 5px 15px rgba(211, 47, 47, 0.3);
            transform: translateY(-2px);
        }
        
        /* Banner Styles */
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
        .page-title-banner p {
            font-size: 1.2rem;
            color: rgba(255,255,255,0.9);
            max-width: 600px;
            margin: 0 auto 20px;
            text-shadow: 1px 1px 5px rgba(0,0,0,0.5);
        }
    </style>
@endpush

@push('scripts')
    <script src="/assets/js/jquery-1.12.4.min.js"></script>
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="/assets/js/scripts.js"></script>
@endpush

@section('title', 'Venues & Tents')

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
                    <h1>Exquisite Venues</h1>
                    <p>Discover the perfect setting for your next unforgettable event, from elegant banquets to magical outdoor tents.</p>
                </div>
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Venues</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="section pt-5 pb-5 bg-light">
    <div class="container mt-4">
        @include('partials.message-bag')
        <div class="row">
            @forelse($venues as $venue)
                <div class="col-lg-4 col-md-6 mb-5">
                    <div class="venue-card">
                        <div class="venue-img-wrap">
                            <div class="venue-deposit-tag">
                                <i class="fas fa-wallet me-1"></i> {{ $venue->deposit_percentage }}% Deposit
                            </div>
                            @if($venue->images->count() > 0)
                                <img src="{{ asset('storage/' . $venue->images->first()->image_path) }}" alt="{{ $venue->name }}">
                            @else
                                <img src="/assets/images/about_img5.jpg" alt="{{ $venue->name }}">
                            @endif
                        </div>
                        <div class="venue-content">
                            <h3 class="venue-title">{{ $venue->name }}</h3>
                            <p class="venue-desc">{{ $venue->description }}</p>
                            
                            <a href="{{ route('venues.show', $venue->id) }}" class="btn-view-venue">
                                View Packages <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-map-marked-alt fa-4x text-muted mb-4"></i>
                    <h3 class="text-muted">No venues currently available.</h3>
                    <p class="text-muted">Please check back later for our beautiful event spaces.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
