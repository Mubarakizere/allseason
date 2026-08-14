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

    <style>
        .acct {
            --radius: 16px;
            --shadow: 0 12px 30px rgba(0,0,0,.08);
            --muted: #6c757d;
            --border: #eef0f3;
            --soft: #f8f9fb;
            --accent: #0d6efd;
        }
        .acct-card {
            border: 0;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            background: #fff;
        }
        .acct-hero {
            background: linear-gradient(135deg, rgba(13,110,253,.18), rgba(111,66,193,.14));
            padding: 28px 24px;
        }
        .acct-avatar {
            width: 84px;
            height: 84px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 6px 16px rgba(0,0,0,.12);
            background: #e9ecef;
        }
        .acct-name {
            font-weight: 800;
            font-size: 1.25rem;
            line-height: 1.1;
        }
        .acct-sub {
            color: var(--muted);
        }
        .acct-hd {
            font-weight: 800;
            font-size: 1.05rem;
            margin-bottom: .75rem;
        }
        .acct-ql .btn {
            border-radius: 999px;
            padding: .45rem .9rem;
            font-weight: 600;
            border: 1px solid var(--border);
            background: #fff;
        }
        .acct-ql .btn:hover {
            background: var(--soft);
        }
        .g-gap { gap: 1.25rem; }

        /* Orders table */
        .orders-card {
            border-radius: 20px;
            border: 1px solid #eef0f3;
            padding: 24px;
            background: #fff;
            box-shadow: 0 5px 25px rgba(0,0,0,0.03);
        }
        .orders-filters .nav-link {
            border-radius: 999px;
            padding: .5rem 1.2rem;
            font-size: .95rem;
            color: #555;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .orders-filters .nav-link:hover {
            background: #f8f9fa;
        }
        .orders-filters .nav-link.active {
            background: #0d6efd;
            color: #fff;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
        }
        
        .type-tabs .nav-link {
            border: none;
            color: #666;
            font-weight: 600;
            font-size: 1.1rem;
            padding: 10px 20px;
            background: transparent;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        .type-tabs .nav-link.active {
            color: #d32f2f;
            background: transparent;
            border-bottom: 3px solid #d32f2f;
        }
        
        .order-status-badge {
            padding: .35rem .75rem;
            border-radius: 999px;
            font-size: .85rem;
            font-weight: 700;
            text-transform: capitalize;
            letter-spacing: 0.5px;
        }
        .order-status-pending   { background: #fff3cd; color: #856404; }
        .order-status-completed, .order-status-confirmed { background: #d1e7dd; color: #0f5132; }
        .order-status-cancelled { background: #f8d7da; color: #842029; }

        .orders-table th {
            color: #888;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }
        .orders-table td {
            padding: 18px 10px;
            vertical-align: middle;
            color: #444;
        }

        @media (max-width: 575.98px) {
            .orders-table thead {
                display: none;
            }
            .orders-table tbody tr {
                display: block;
                margin-bottom: 15px;
                border: 1px solid #eef0f3;
                border-radius: 12px;
                padding: 15px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            }
            .orders-table tbody td {
                display: flex;
                justify-content: space-between;
                padding: 8px 0;
                border-bottom: 1px solid #f5f5f5;
            }
            .orders-table tbody td:last-child {
                border-bottom: none;
            }
            .orders-table tbody td::before {
                content: attr(data-label);
                font-weight: 700;
                color: #6c757d;
                margin-right: 8px;
                text-transform: uppercase;
                font-size: 0.8rem;
            }
        }
    </style>
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

@section('title', 'My Orders')

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
<div class="section acct">
    <div class="container">
        @include('partials.message-bag')

        <div class="acct-card">
            <!-- HERO -->
            <div class="acct-hero">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between g-gap">
                    <div class="d-flex align-items-center g-gap">
                        <img
                            class="acct-avatar"
                            src="https://ui-avatars.com/api/?name={{ urlencode(trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))) }}&size=192&background=E9ECEF&color=495057"
                            alt="User avatar"
                        />

                        <div>
                            <div class="acct-name">
                                {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'Your name' }}
                            </div>
                            <div class="acct-sub">
                                {{ $user->email ?? 'no-email@domain.com' }}
                                <span class="mx-2">•</span>
                                <span>
                                    Member since {{ optional($user->created_at)->format('d M Y') ?? '—' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BODY -->
            <div class="p-3 p-md-4">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <div class="acct-hd mb-0">My Orders</div>

                    <!-- Filter pills -->
                    <ul class="nav orders-filters">
                        @php
                            $filters = [
                                'all'       => 'All',
                                'pending'   => 'Pending',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ];
                        @endphp
                        @foreach($filters as $key => $label)
                            <li class="nav-item">
                                <a
                                    href="{{ route('customer.orders', $key === 'all' ? null : $key) }}"
                                    class="nav-link {{ ($filter ?? 'all') === $key ? 'active' : '' }}"
                                >
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="orders-card">
                    <!-- Nav tabs for type -->
                    <ul class="nav nav-tabs type-tabs mb-4" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="food-tab" data-bs-toggle="tab" data-bs-target="#food" type="button" role="tab" aria-controls="food" aria-selected="true">
                                <i class="fas fa-utensils me-2"></i>Restaurant Orders
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="rooms-tab" data-bs-toggle="tab" data-bs-target="#rooms" type="button" role="tab" aria-controls="rooms" aria-selected="false">
                                <i class="fas fa-bed me-2"></i>Room Bookings
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="venues-tab" data-bs-toggle="tab" data-bs-target="#venues" type="button" role="tab" aria-controls="venues" aria-selected="false">
                                <i class="fas fa-map-marked-alt me-2"></i>Venue Bookings
                            </button>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" id="myTabContent">
                        
                        <!-- FOOD ORDERS TAB -->
                        <div class="tab-pane fade show active" id="food" role="tabpanel" aria-labelledby="food-tab">
                            @if ($orders->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                    @if ($filter === 'all')
                                        <h5 class="text-muted">You don't have any restaurant orders yet.</h5>
                                    @else
                                        <h5 class="text-muted">You don't have any {{ ucfirst($filter) }} orders yet.</h5>
                                    @endif
                                    <a href="{{ route('menu') }}" class="btn btn-primary mt-3" style="border-radius: 20px;">Browse Menu</a>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0 orders-table">
                                        <thead>
                                            <tr>
                                                <th>Order No</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $order)
                                                <tr>
                                                    <td data-label="Order No">
                                                        <a href="{{ route('customer.order.details', $order->id) }}" class="text-primary text-decoration-none"><span class="fw-bold">#{{ $order->order_no }}</span></a>
                                                    </td>
                                                    <td data-label="Date">
                                                        {{ optional($order->created_at)->format('d M Y, H:i') }}
                                                    </td>
                                                    <td data-label="Type">
                                                        <span class="text-capitalize text-muted fw-semibold">
                                                            <i class="fas {{ $order->order_type == 'delivery' ? 'fa-motorcycle' : 'fa-shopping-bag' }} me-1"></i> {{ $order->order_type ?? 'online' }}
                                                        </span>
                                                    </td>
                                                    <td data-label="Amount">
                                                        <span class="fw-bold text-dark">
                                                            {!! $site_settings->currency_symbol ?? '£' !!}{{ number_format($order->total_price, 2) }}
                                                        </span>
                                                    </td>
                                                    <td data-label="Status">
                                                        @php
                                                            $status = $order->status ?? 'pending';
                                                            $statusClass = match($status) {
                                                                'completed' => 'order-status-completed',
                                                                'cancelled' => 'order-status-cancelled',
                                                                default     => 'order-status-pending',
                                                            };
                                                        @endphp
                                                        <span class="order-status-badge {{ $statusClass }}">
                                                            {{ $status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- ROOM BOOKINGS TAB -->
                        <div class="tab-pane fade" id="rooms" role="tabpanel" aria-labelledby="rooms-tab">
                            @if (isset($room_bookings) && $room_bookings->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                                    @if ($filter === 'all')
                                        <h5 class="text-muted">You don't have any room bookings yet.</h5>
                                    @else
                                        <h5 class="text-muted">You don't have any {{ ucfirst($filter) }} room bookings yet.</h5>
                                    @endif
                                    <a href="{{ route('rooms.index') }}" class="btn btn-primary mt-3" style="border-radius: 20px;">Browse Rooms</a>
                                </div>
                            @elseif(isset($room_bookings))
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0 orders-table">
                                        <thead>
                                            <tr>
                                                <th>Room</th>
                                                <th>Check-in</th>
                                                <th>Check-out</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($room_bookings as $booking)
                                                <tr>
                                                    <td data-label="Room">
                                                        <span class="fw-bold text-dark">{{ $booking->room->name ?? 'Room #'.$booking->room_id }}</span>
                                                    </td>
                                                    <td data-label="Check-in">
                                                        <span class="text-muted fw-semibold"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</span>
                                                    </td>
                                                    <td data-label="Check-out">
                                                        <span class="text-muted fw-semibold"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</span>
                                                    </td>
                                                    <td data-label="Amount">
                                                        <span class="fw-bold text-dark">
                                                            {!! $site_settings->currency_symbol ?? '£' !!}{{ number_format($booking->total_price, 2) }}
                                                        </span>
                                                    </td>
                                                    <td data-label="Status">
                                                        @php
                                                            $status = $booking->status ?? 'pending';
                                                            $statusClass = match($status) {
                                                                'confirmed' => 'order-status-completed',
                                                                'cancelled' => 'order-status-cancelled',
                                                                default     => 'order-status-pending',
                                                            };
                                                        @endphp
                                                        <span class="order-status-badge {{ $statusClass }}">
                                                            {{ $status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <!-- VENUE BOOKINGS TAB -->
                        <div class="tab-pane fade" id="venues" role="tabpanel" aria-labelledby="venues-tab">
                            @if (isset($venue_bookings) && $venue_bookings->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-map-marked-alt fa-3x text-muted mb-3"></i>
                                    @if ($filter === 'all')
                                        <h5 class="text-muted">You don't have any venue bookings yet.</h5>
                                    @else
                                        <h5 class="text-muted">You don't have any {{ ucfirst($filter) }} venue bookings yet.</h5>
                                    @endif
                                    <a href="{{ route('venues.index') }}" class="btn btn-primary mt-3" style="border-radius: 20px;">Explore Venues</a>
                                </div>
                            @elseif(isset($venue_bookings))
                                <div class="table-responsive">
                                    <table class="table align-middle mb-0 orders-table">
                                        <thead>
                                            <tr>
                                                <th>Venue</th>
                                                <th>Date</th>
                                                <th>Package</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($venue_bookings as $booking)
                                                <tr>
                                                    <td data-label="Venue">
                                                        <span class="fw-bold text-dark">{{ $booking->venue->name ?? 'Venue #'.$booking->venue_id }}</span>
                                                    </td>
                                                    <td data-label="Date">
                                                        <span class="text-muted fw-semibold"><i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</span>
                                                    </td>
                                                    <td data-label="Package">
                                                        <span class="text-muted fw-semibold">{{ $booking->package->name ?? '—' }}</span>
                                                    </td>
                                                    <td data-label="Amount">
                                                        <span class="fw-bold text-dark">
                                                            {!! $site_settings->currency_symbol ?? '£' !!}{{ number_format($booking->total_price, 2) }}
                                                        </span>
                                                    </td>
                                                    <td data-label="Status">
                                                        @php
                                                            $status = $booking->status ?? 'pending';
                                                            $statusClass = match($status) {
                                                                'confirmed' => 'order-status-completed',
                                                                'cancelled' => 'order-status-cancelled',
                                                                default     => 'order-status-pending',
                                                            };
                                                        @endphp
                                                        <span class="order-status-badge {{ $statusClass }}">
                                                            {{ $status }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>

                <!-- Bottom action buttons -->
                <div class="acct-ql d-flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('customer.edit.profile') }}" class="btn btn-sm">
                        <i class="fas fa-user-edit me-2"></i>Edit Account
                    </a>
                    <a href="{{ route('customer.change.password') }}" class="btn btn-sm">
                        <i class="fas fa-key me-2"></i>Change Password
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-sm">
                        <i class="fas fa-shopping-bag me-2"></i>Return to Shopping
                    </a>
                    <a href="{{ route('customer.orders') }}" class="btn btn-sm">
                        <i class="fas fa-file-invoice-dollar me-2"></i>My Orders
                    </a>
                    <a href="{{ route('auth.logout') }}" class="btn btn-sm">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
