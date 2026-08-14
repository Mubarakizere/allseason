
@extends('layouts.main-site')

@push('styles')
    
    
    <!-- Animation CSS -->
    <link rel="stylesheet" href="/assets/css/animate.css">	
    <!-- Latest Bootstrap min CSS -->
    <link rel="stylesheet" href="/assets/bootstrap/css/bootstrap.min.css">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script&amp;display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Josefin+Sans:100,100i,300,300i,400,400i,600,600i,700,700i&amp;display=swap" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&amp;display=swap" rel="stylesheet"> 
    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="/assets/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/ionicons.min.css">
    <link rel="stylesheet" href="/assets/css/themify-icons.css">
    <link rel="stylesheet" href="/assets/css/linearicons.css">
    <link rel="stylesheet" href="/assets/css/flaticon.css">
    <!--- owl carousel CSS-->
    <link rel="stylesheet" href="/assets/owlcarousel/css/owl.carousel.min.css">
    <link rel="stylesheet" href="/assets/owlcarousel/css/owl.theme.css">
    <link rel="stylesheet" href="/assets/owlcarousel/css/owl.theme.default.min.css">
    <!-- Slick CSS -->
    <link rel="stylesheet" href="/assets/css/slick.css">
    <link rel="stylesheet" href="/assets/css/slick-theme.css">
    <!-- Magnific Popup CSS -->
    <link rel="stylesheet" href="/assets/css/magnific-popup.css">
    <!-- DatePicker CSS -->
    <link href="/assets/css/datepicker.min.css" rel="stylesheet">
    <!-- TimePicker CSS -->
    <link href="/assets/css/mdtimepicker.min.css" rel="stylesheet">
    <!-- Style CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">
    <link id="layoutstyle" rel="stylesheet" href="/assets/color/theme-red.css">
@endpush

@push('scripts')
 
    <!-- Latest jQuery --> 
    <script src="/assets/js/jquery-1.12.4.min.js"></script> 
    <!-- Latest compiled and minified Bootstrap --> 
    <script src="/assets/bootstrap/js/bootstrap.min.js"></script> 
    <!-- owl-carousel min js  --> 
    <script src="/assets/owlcarousel/js/owl.carousel.min.js"></script> 
    <!-- magnific-popup min js  --> 
    <script src="/assets/js/magnific-popup.min.js"></script> 
    <!-- waypoints min js  --> 
    <script src="/assets/js/waypoints.min.js"></script> 
    <!-- parallax js  --> 
    <script src="/assets/js/parallax.js"></script> 
    <!-- countdown js  --> 
    <script src="/assets/js/jquery.countdown.min.js"></script> 
    <!-- jquery.countTo js  -->
    <script src="/assets/js/jquery.countTo.js"></script>
    <!-- imagesloaded js --> 
    <script src="/assets/js/imagesloaded.pkgd.min.js"></script>
    <!-- isotope min js --> 
    <script src="/assets/js/isotope.min.js"></script>
    <!-- jquery.appear js  -->
    <script src="/assets/js/jquery.appear.js"></script>
    <!-- jquery.dd.min js -->
    <script src="/assets/js/jquery.dd.min.js"></script>
    <!-- slick js -->
    <script src="/assets/js/slick.min.js"></script>
    <!-- DatePicker js -->
    <script src="/assets/js/datepicker.min.js"></script>
    <!-- TimePicker js -->
    <script src="/assets/js/mdtimepicker.min.js"></script>
    <!-- scripts js --> 
    <script src="/assets/js/scripts.js"></script>

    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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


@section('title', 'Create Account')


@section('header')
    <!-- START HEADER -->
        <header class="header_wrap fixed-top header_with_topbar light_skin main_menu_uppercase">
        <div class="container">
            @include('partials.nav')
        </div>
    </header>
    <!-- END HEADER -->
@endsection


@section('content')

 

 <!-- START: Customer Account -->
<style>
    .dashboard-wrapper {
        background-color: #f8f9fa;
        padding-bottom: 60px;
    }
    .profile-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        overflow: hidden;
        margin-bottom: 30px;
        border: none;
    }
    .profile-header {
        background: linear-gradient(135deg, #d32f2f, #ff6b6b);
        padding: 40px 30px 60px;
        text-align: center;
        color: white;
    }
    .profile-avatar-wrapper {
        margin-top: -50px;
        text-align: center;
        position: relative;
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 5px solid #fff;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        background: #fff;
        object-fit: cover;
    }
    .profile-name {
        margin-top: 15px;
        font-weight: 800;
        font-size: 1.5rem;
        color: #2b2b2b;
    }
    .profile-email {
        color: #777;
        font-size: 0.95rem;
        margin-bottom: 20px;
    }
    .profile-nav .btn-custom {
        display: flex;
        align-items: center;
        width: 100%;
        padding: 12px 20px;
        background: #fcfcfc;
        border: 1px solid #eee;
        border-radius: 12px;
        margin-bottom: 10px;
        color: #444;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    .profile-nav .btn-custom i {
        width: 30px;
        color: #d32f2f;
        font-size: 1.1rem;
    }
    .profile-nav .btn-custom:hover {
        background: #d32f2f;
        color: white;
        border-color: #d32f2f;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(211, 47, 47, 0.2);
    }
    .profile-nav .btn-custom:hover i {
        color: white;
    }

    .info-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        padding: 30px;
        margin-bottom: 30px;
        border: none;
    }
    .info-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 15px;
    }
    .info-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #333;
        margin: 0;
    }
    
    .detail-item {
        margin-bottom: 20px;
    }
    .detail-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #999;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .detail-value {
        font-size: 1.05rem;
        font-weight: 600;
        color: #333;
        display: flex;
        align-items: center;
    }
    .detail-value i {
        color: #d32f2f;
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }

    .mini-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 10px;
    }
    .mini-table th {
        color: #888;
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 600;
        padding: 0 15px 5px;
        border-bottom: 2px solid #eee;
    }
    .mini-table td {
        background: #fcfcfc;
        padding: 15px;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        color: #444;
        font-weight: 500;
        vertical-align: middle;
    }
    .mini-table tr td:first-child {
        border-left: 1px solid #eee;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    .mini-table tr td:last-child {
        border-right: 1px solid #eee;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    .status-badge {
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-completed, .status-confirmed, .status-paid { background: #e8f5e9; color: #2e7d32; }
    .status-pending { background: #fff8e1; color: #f57f17; }
    .status-cancelled { background: #ffebee; color: #c62828; }
    
    .view-all-link {
        font-size: 0.9rem;
        font-weight: 600;
        color: #d32f2f;
        text-decoration: none;
        transition: all 0.2s;
    }
    .view-all-link:hover {
        color: #b71c1c;
        text-decoration: underline;
    }
</style>

<div class="section dashboard-wrapper pt-5">
    <div class="container">
        @include('partials.message-bag')

        <div class="row">
            <!-- Sidebar Profile Card -->
            <div class="col-lg-4 col-md-5">
                <div class="profile-card">
                    <div class="profile-header">
                        <h4 class="mb-0 text-white">Welcome Back!</h4>
                    </div>
                    <div class="profile-avatar-wrapper">
                        <img
                            class="profile-avatar"
                            src="https://ui-avatars.com/api/?name={{ urlencode(trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))) }}&size=192&background=ffffff&color=d32f2f"
                            alt="User avatar"
                        />
                        <h4 class="profile-name">{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'Guest User' }}</h4>
                        <div class="profile-email">{{ $user->email ?? 'No email provided' }}</div>
                        <div class="badge bg-light text-dark mb-4 px-3 py-2 border"><i class="fas fa-calendar-alt me-2 text-danger"></i>Member since {{ optional($user->created_at)->format('M Y') ?? '—' }}</div>
                    </div>
                    
                    <div class="p-4 pt-0 profile-nav">
                        <a href="{{ route('customer.edit.profile') }}" class="btn-custom">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </a>
                        <a href="{{ route('customer.change.password') }}" class="btn-custom">
                            <i class="fas fa-key"></i> Change Password
                        </a>
                        <a href="{{ route('customer.orders') }}" class="btn-custom">
                            <i class="fas fa-file-invoice-dollar"></i> View All Orders
                        </a>
                        <a href="{{ route('home') }}" class="btn-custom">
                            <i class="fas fa-store"></i> Return to Shopping
                        </a>
                        <a href="{{ route('auth.logout') }}" class="btn-custom text-danger mt-3" style="background: #fff5f5; border-color: #ffe0e0;">
                            <i class="fas fa-sign-out-alt text-danger"></i> Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-8 col-md-7">
                
                <!-- Personal Information -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3 class="info-card-title"><i class="fas fa-id-card me-2 text-danger"></i> Personal Information</h3>
                        <a href="{{ route('customer.edit.profile') }}" class="view-all-link">Edit</a>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-sm-6 detail-item">
                            <div class="detail-label">First Name</div>
                            <div class="detail-value">{{ $user->first_name ?? '—' }}</div>
                        </div>
                        <div class="col-md-4 col-sm-6 detail-item">
                            <div class="detail-label">Last Name</div>
                            <div class="detail-value">{{ $user->last_name ?? '—' }}</div>
                        </div>
                        <div class="col-md-4 col-sm-6 detail-item">
                            <div class="detail-label">Phone Number</div>
                            <div class="detail-value"><i class="fas fa-phone-alt"></i> {{ $user->phone_number ?? '—' }}</div>
                        </div>
                        <div class="col-md-12 detail-item mb-0">
                            <div class="detail-label">Default Address</div>
                            <div class="detail-value"><i class="fas fa-map-marker-alt"></i> {{ $user->address ?? 'No address provided yet.' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Recent Restaurant Orders -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3 class="info-card-title"><i class="fas fa-utensils me-2 text-danger"></i> Recent Food Orders</h3>
                        <a href="{{ route('customer.orders') }}" class="view-all-link">View All</a>
                    </div>
                    
                    @if(isset($food_orders) && $food_orders->count() > 0)
                        <div class="table-responsive">
                            <table class="mini-table">
                                <thead>
                                    <tr>
                                        <th>Order No</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($food_orders as $order)
                                    <tr>
                                        <td><a href="{{ route('customer.order.details', $order->id) }}" class="text-danger font-weight-bold text-decoration-none">#{{ $order->order_no }}</a></td>
                                        <td>{{ optional($order->created_at)->format('d M Y') }}</td>
                                        <td class="font-weight-bold">{!! \App\Models\SiteSetting::latest()->first()->currency_symbol ?? '£' !!}{{ number_format($order->total_price, 2) }}</td>
                                        <td>
                                            @php
                                                $statusClass = match($order->status ?? 'pending') {
                                                    'completed' => 'status-completed',
                                                    'cancelled' => 'status-cancelled',
                                                    default     => 'status-pending',
                                                };
                                            @endphp
                                            <span class="status-badge {{ $statusClass }}">{{ $order->status ?? 'pending' }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 bg-light rounded" style="border: 1px dashed #ddd;">
                            <i class="fas fa-hamburger fa-2x text-muted mb-2"></i>
                            <p class="mb-2 text-muted">You haven't ordered any food yet.</p>
                            <a href="{{ route('menu') }}" class="btn btn-sm btn-outline-danger" style="border-radius: 20px;">Browse Menu</a>
                        </div>
                    @endif
                </div>

                <!-- Recent Room Bookings -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3 class="info-card-title"><i class="fas fa-bed me-2 text-danger"></i> Recent Room Bookings</h3>
                        <a href="{{ route('customer.orders') }}" class="view-all-link">View All</a>
                    </div>
                    
                    @if(isset($room_bookings) && $room_bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="mini-table">
                                <thead>
                                    <tr>
                                        <th>Room</th>
                                        <th>Check-in</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($room_bookings as $booking)
                                    <tr>
                                        <td class="font-weight-bold">{{ $booking->room->name ?? 'Room #'.$booking->room_id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</td>
                                        <td class="font-weight-bold">{!! \App\Models\SiteSetting::latest()->first()->currency_symbol ?? '£' !!}{{ number_format($booking->total_price, 2) }}</td>
                                        <td>
                                            @php
                                                $statusClass = match($booking->status ?? 'pending') {
                                                    'confirmed' => 'status-confirmed',
                                                    'cancelled' => 'status-cancelled',
                                                    default     => 'status-pending',
                                                };
                                            @endphp
                                            <span class="status-badge {{ $statusClass }}">{{ $booking->status ?? 'pending' }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 bg-light rounded" style="border: 1px dashed #ddd;">
                            <i class="fas fa-concierge-bell fa-2x text-muted mb-2"></i>
                            <p class="mb-2 text-muted">You don't have any room bookings yet.</p>
                            <a href="{{ route('rooms.index') }}" class="btn btn-sm btn-outline-danger" style="border-radius: 20px;">Explore Rooms</a>
                        </div>
                    @endif
                </div>

                <!-- Recent Venue Bookings -->
                <div class="info-card">
                    <div class="info-card-header">
                        <h3 class="info-card-title"><i class="fas fa-map-marked-alt me-2 text-danger"></i> Recent Venue Bookings</h3>
                        <a href="{{ route('customer.orders') }}#venues" class="view-all-link">View All</a>
                    </div>
                    
                    @if(isset($venue_bookings) && $venue_bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="mini-table">
                                <thead>
                                    <tr>
                                        <th>Venue</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($venue_bookings as $booking)
                                    <tr>
                                        <td class="font-weight-bold">{{ $booking->venue->name ?? 'Venue #'.$booking->venue_id }}</td>
                                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</td>
                                        <td class="font-weight-bold">{!! \App\Models\SiteSetting::latest()->first()->currency_symbol ?? '£' !!}{{ number_format($booking->total_price, 2) }}</td>
                                        <td>
                                            @php
                                                $statusClass = match($booking->status ?? 'pending') {
                                                    'confirmed' => 'status-confirmed',
                                                    'cancelled' => 'status-cancelled',
                                                    default     => 'status-pending',
                                                };
                                            @endphp
                                            <span class="status-badge {{ $statusClass }}">{{ $booking->status ?? 'pending' }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 bg-light rounded" style="border: 1px dashed #ddd;">
                            <i class="fas fa-calendar-alt fa-2x text-muted mb-2"></i>
                            <p class="mb-2 text-muted">You don't have any venue bookings yet.</p>
                            <a href="{{ route('venues.index') }}" class="btn btn-sm btn-outline-danger" style="border-radius: 20px;">Explore Venues</a>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
<!-- END: Customer Account -->


@endsection
