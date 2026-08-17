@extends('layouts.admin')

@php
    $dateLabel = ($startDate === $endDate)
        ? \Carbon\Carbon::parse($startDate)->format('d M Y')
        : \Carbon\Carbon::parse($startDate)->format('d M Y') . ' — ' . \Carbon\Carbon::parse($endDate)->format('d M Y');
@endphp

@section('title', 'Global Report — ' . $dateLabel)

@push('styles')
<style>
    /* Custom Styling for Global Report */
    .report-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }
    .stat-icon-wrapper {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .stat-value {
        font-size: clamp(1.15rem, 2.5vw, 1.75rem);
        font-weight: 700;
        line-height: 1.2;
        word-break: break-word;
    }
    .badge-soft-success {
        background-color: #d1fae5;
        color: #065f46;
    }
    .badge-soft-warning {
        background-color: #fef3c7;
        color: #92400e;
    }
    .badge-soft-danger {
        background-color: #fee2e2;
        color: #991b1b;
    }
    .badge-soft-info {
        background-color: #e0f2fe;
        color: #075985;
    }
    .badge-soft-primary {
        background-color: #e0e7ff;
        color: #3730a3;
    }
    .badge-soft-secondary {
        background-color: #f3f4f6;
        color: #374151;
    }
    .section-header-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #111827;
    }
    .table-responsive {
        -webkit-overflow-scrolling: touch;
    }
    .table-custom {
        min-width: 750px;
    }
    .table-custom th {
        background-color: #f9fafb;
        color: #4b5563;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 600;
        border-bottom: 2px solid #e5e7eb;
    }
    .table-custom td {
        font-size: 0.875rem;
        vertical-align: middle;
        color: #1f2937;
    }

    /* Print View Styling */
    @media print {
        body {
            background-color: #ffffff !important;
            font-size: 12pt;
        }
        .sidebar, .mobile-admin-header, .sidebar-overlay, .no-print, nav, footer {
            display: none !important;
        }
        .main-panel {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        .content-wrapper {
            padding: 0 !important;
        }
        .card, .report-card {
            border: 1px solid #ccc !important;
            box-shadow: none !important;
        }
        .print-header {
            display: block !important;
            margin-bottom: 20px;
            text-align: center;
        }
        .table-custom {
            min-width: 100% !important;
        }
        .page-break {
            page-break-before: always;
        }
    }
    .print-header {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">

    <!-- Print Header (Only shows during print) -->
    <div class="print-header">
        <h2 class="fw-bold mb-1">{{ config('app.name', 'All Season Garden') }}</h2>
        <h4 class="text-muted">Global Consolidated Daily Report</h4>
        <p class="mb-0"><strong>Period:</strong> {{ $dateLabel }} | <strong>Generated At:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
        <hr>
    </div>

    <!-- Screen Header & Filters -->
    <div class="d-flex flex-column flex-xl-row align-items-xl-center justify-content-between mb-4 no-print gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="fas fa-chart-line text-primary me-2"></i>Global Daily Report
            </h3>
            <p class="text-muted mb-0">
                Consolidated sales, room bookings, and venue bookings for 
                <span class="fw-semibold text-dark">{{ $dateLabel }}</span>
            </p>
        </div>

        <div class="d-flex flex-column flex-sm-row flex-wrap align-items-sm-center gap-2">
            <!-- Date Quick Presets -->
            <div class="btn-group btn-group-sm flex-wrap" role="group" aria-label="Date Presets">
                <a href="{{ route('admin.reports.global', ['start_date' => now()->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
                   class="btn {{ ($startDate === now()->format('Y-m-d') && $endDate === now()->format('Y-m-d')) ? 'btn-primary' : 'btn-outline-secondary' }}">
                   Today
                </a>
                <a href="{{ route('admin.reports.global', ['start_date' => now()->subDay()->format('Y-m-d'), 'end_date' => now()->subDay()->format('Y-m-d')]) }}" 
                   class="btn {{ ($startDate === now()->subDay()->format('Y-m-d') && $endDate === now()->subDay()->format('Y-m-d')) ? 'btn-primary' : 'btn-outline-secondary' }}">
                   Yesterday
                </a>
                <a href="{{ route('admin.reports.global', ['start_date' => now()->startOfWeek()->format('Y-m-d'), 'end_date' => now()->endOfWeek()->format('Y-m-d')]) }}" 
                   class="btn {{ ($startDate === now()->startOfWeek()->format('Y-m-d') && $endDate === now()->endOfWeek()->format('Y-m-d')) ? 'btn-primary' : 'btn-outline-secondary' }}">
                   This Week
                </a>
                <a href="{{ route('admin.reports.global', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" 
                   class="btn {{ ($startDate === now()->startOfMonth()->format('Y-m-d') && $endDate === now()->endOfMonth()->format('Y-m-d')) ? 'btn-primary' : 'btn-outline-secondary' }}">
                   This Month
                </a>
            </div>

            <!-- Date Range Form ("Date Between") -->
            <form method="GET" action="{{ route('admin.reports.global') }}" class="d-flex flex-wrap align-items-center gap-2">
                <div class="d-flex align-items-center gap-1">
                    <span class="text-muted small fw-semibold">From:</span>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-sm shadow-sm" style="max-width: 135px;" required>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <span class="text-muted small fw-semibold">To:</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-sm shadow-sm" style="max-width: 135px;" required>
                </div>
                <button type="submit" class="btn btn-sm btn-dark">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </form>

            <!-- Print Button -->
            <button onclick="window.print()" class="btn btn-sm btn-outline-dark ms-auto ms-sm-0">
                <i class="fas fa-print me-1"></i> Print / PDF
            </button>
        </div>
    </div>

    <!-- 1. OVERALL CONSOLIDATED STATS -->
    <div class="row g-3 mb-4">
        <!-- Combined Revenue -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card report-card p-3 h-100 border-0 shadow-sm" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff;">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div style="min-width: 0;">
                        <p class="text-uppercase font-weight-bold text-light mb-1 opacity-75" style="font-size: 0.72rem; letter-spacing: 0.05em;">Total Combined Revenue</p>
                        <div class="stat-value text-white mb-0">{{ $site_settings->currency_symbol }}{{ number_format($combinedTotalRevenue, 2) }}</div>
                        <small class="text-light opacity-75 d-block mt-1">{{ $combinedTotalTransactions }} total transactions</small>
                    </div>
                    <div class="stat-icon-wrapper bg-primary text-white shadow-sm flex-shrink-0">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Food & Sales Total -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card report-card p-3 h-100 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Food & Beverage Sales</p>
                        <div class="stat-value text-dark mb-0">{{ $site_settings->currency_symbol }}{{ number_format($salesTotal, 2) }}</div>
                        <small class="text-muted d-block mt-1">{{ $ordersCount }} total orders</small>
                    </div>
                    <div class="stat-icon-wrapper badge-soft-success flex-shrink-0">
                        <i class="fas fa-utensils"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Bookings Total -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card report-card p-3 h-100 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Room Bookings</p>
                        <div class="stat-value text-dark mb-0">{{ $site_settings->currency_symbol }}{{ number_format($roomsRevenue, 2) }}</div>
                        <small class="text-muted d-block mt-1">{{ $roomsCount }} bookings / check-ins</small>
                    </div>
                    <div class="stat-icon-wrapper badge-soft-info flex-shrink-0">
                        <i class="fas fa-hotel"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Venue Bookings Total -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card report-card p-3 h-100 border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <div style="min-width: 0;">
                        <p class="text-muted text-uppercase fw-semibold mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Venue Bookings</p>
                        <div class="stat-value text-dark mb-0">{{ $site_settings->currency_symbol }}{{ number_format($venuesRevenue, 2) }}</div>
                        <small class="text-muted d-block mt-1">{{ $venuesCount }} venue events</small>
                    </div>
                    <div class="stat-icon-wrapper badge-soft-primary flex-shrink-0">
                        <i class="fas fa-glass-cheers"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- 2. SECTION: FOOD & RESTAURANT SALES -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 border-0 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="stat-icon-wrapper badge-soft-success flex-shrink-0" style="width: 36px; height: 36px; font-size: 15px;">
                    <i class="fas fa-receipt"></i>
                </span>
                <div>
                    <h5 class="section-header-title mb-0">1. Restaurant & Food Sales</h5>
                    <small class="text-muted">POS orders placed or completed for period {{ $dateLabel }}</small>
                </div>
            </div>
            
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge badge-soft-success px-3 py-2">Completed: {{ $completedOrdersCount }}</span>
                <span class="badge badge-soft-warning px-3 py-2">Pending: {{ $pendingOrdersCount }}</span>
                <span class="badge badge-soft-danger px-3 py-2">Cancelled: {{ $cancelledOrdersCount }}</span>
                <span class="badge badge-soft-secondary px-3 py-2">Dine-in: {{ $instoreOrdersCount }} | Delivery: {{ $deliveryOrdersCount }}</span>
            </div>
        </div>

        <div class="card-body p-3 border-bottom bg-light">
            <div class="row g-3">
                <div class="col-md-3 col-sm-6">
                    <div class="p-3 bg-white border rounded shadow-sm">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small font-weight-bold text-uppercase"><i class="fas fa-money-bill-wave text-success me-1"></i> Cash Sales</span>
                            <span class="badge bg-success text-white">Cash</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-0 mt-2">{{ $site_settings->currency_symbol }}{{ number_format($cashSales, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-3 bg-white border rounded shadow-sm">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small font-weight-bold text-uppercase"><i class="fas fa-mobile-alt text-primary me-1"></i> MoMo Pay</span>
                            <span class="badge bg-primary text-white">Mobile</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-0 mt-2">{{ $site_settings->currency_symbol }}{{ number_format($momoPaySales, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-3 bg-white border rounded shadow-sm">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small font-weight-bold text-uppercase"><i class="fas fa-credit-card text-warning me-1"></i> Bank / Card</span>
                            <span class="badge bg-warning text-dark">Bank</span>
                        </div>
                        <h4 class="fw-bold text-dark mb-0 mt-2">{{ $site_settings->currency_symbol }}{{ number_format($bankCardSales, 2) }}</h4>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="p-3 bg-white border rounded shadow-sm">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="text-muted small font-weight-bold text-uppercase"><i class="fas fa-chart-pie text-secondary me-1"></i> Total Completed</span>
                            <span class="badge bg-dark text-white">Total</span>
                        </div>
                        <h4 class="fw-bold text-success mb-0 mt-2">{{ $site_settings->currency_symbol }}{{ number_format($salesTotal, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($orders->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-shopping-basket fa-2x mb-2 text-secondary"></i>
                    <p class="mb-0">No sales or orders recorded for this period.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Type</th>
                                <th>Customer / Table / Waiter</th>
                                <th>Items</th>
                                <th>Payment Method</th>
                                <th>Status</th>
                                <th class="text-end">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="fw-bold">
                                        <a href="{{ route('admin.order.show', $order->id) }}" class="text-decoration-none text-dark no-print">
                                            #{{ $order->order_no }}
                                        </a>
                                        <span class="print-only d-none">#{{ $order->order_no }}</span>
                                    </td>
                                    <td>
                                        @if($order->order_type === 'instore')
                                            <span class="badge badge-soft-primary"><i class="fas fa-chair me-1"></i> Dine-in</span>
                                        @else
                                            <span class="badge badge-soft-info"><i class="fas fa-truck me-1"></i> Delivery</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->customer)
                                            <div><i class="fas fa-user text-muted me-1"></i> {{ $order->customer->first_name }} {{ $order->customer->last_name }}</div>
                                        @elseif($order->restaurantTable)
                                            <div><i class="fas fa-chair text-muted me-1"></i> {{ $order->restaurantTable->name }}</div>
                                        @else
                                            <span class="text-muted">Walk-in Guest</span>
                                        @endif

                                        @if($order->waiter)
                                            <small class="text-muted d-block"><i class="fas fa-user-tag me-1"></i> Waiter: {{ $order->waiter->name }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $order->orderItems->count() }} item(s):
                                            {{ $order->orderItems->pluck('menu.name')->filter()->take(2)->implode(', ') }}
                                            @if($order->orderItems->count() > 2) ... @endif
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-secondary text-uppercase" style="font-size: 11px;">
                                            {{ $order->payment_method ?? 'Cash' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($order->status === 'completed')
                                            <span class="badge badge-soft-success"><i class="fas fa-check-circle me-1"></i> Completed</span>
                                        @elseif($order->status === 'pending')
                                            <span class="badge badge-soft-warning"><i class="fas fa-clock me-1"></i> Pending</span>
                                        @else
                                            <span class="badge badge-soft-danger"><i class="fas fa-times-circle me-1"></i> {{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">
                                        {{ $site_settings->currency_symbol }}{{ number_format($order->total_price, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="6" class="text-end text-uppercase">Total Sales Revenue (Completed):</td>
                                <td class="text-end text-success fs-6">
                                    {{ $site_settings->currency_symbol }}{{ number_format($salesTotal, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>


    <!-- 3. SECTION: ROOM BOOKINGS -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 border-0 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="stat-icon-wrapper badge-soft-info flex-shrink-0" style="width: 36px; height: 36px; font-size: 15px;">
                    <i class="fas fa-hotel"></i>
                </span>
                <div>
                    <h5 class="section-header-title mb-0">2. Room Bookings</h5>
                    <small class="text-muted">Room stays active, created, checking in/out for period {{ $dateLabel }}</small>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge badge-soft-success px-3 py-2">Confirmed: {{ $roomStatusBreakdown['confirmed'] }}</span>
                <span class="badge badge-soft-warning px-3 py-2">Pending: {{ $roomStatusBreakdown['pending'] }}</span>
                <span class="badge badge-soft-secondary px-3 py-2">Total Deposits: {{ $site_settings->currency_symbol }}{{ number_format($roomsDepositTotal, 2) }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($roomBookings->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-bed fa-2x mb-2 text-secondary"></i>
                    <p class="mb-0">No room bookings recorded for this period.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer Info</th>
                                <th>Room Name / No</th>
                                <th>Check-in Date</th>
                                <th>Check-out Date</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-end">Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roomBookings as $booking)
                                <tr>
                                    <td class="fw-bold">#RB-{{ $booking->id }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $booking->customer_name }}</div>
                                        <small class="text-muted d-block"><i class="fas fa-envelope me-1"></i> {{ $booking->customer_email ?? 'N/A' }}</small>
                                        <small class="text-muted d-block"><i class="fas fa-phone me-1"></i> {{ $booking->customer_phone ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $booking->room->room_number ?? 'Room' }}</span>
                                        @if(isset($booking->room->room_type))
                                            <small class="text-muted d-block">({{ $booking->room->room_type }})</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-primary">
                                            <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-secondary">
                                            <i class="far fa-calendar-check me-1"></i> {{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $booking->payment_status === 'paid' ? 'badge-soft-success' : 'badge-soft-warning' }} text-uppercase">
                                            {{ $booking->payment_status ?? 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(in_array($booking->status, ['confirmed', 'completed']))
                                            <span class="badge badge-soft-success"><i class="fas fa-check-circle me-1"></i> {{ ucfirst($booking->status) }}</span>
                                        @elseif($booking->status === 'pending')
                                            <span class="badge badge-soft-warning"><i class="fas fa-clock me-1"></i> Pending</span>
                                        @else
                                            <span class="badge badge-soft-danger"><i class="fas fa-times-circle me-1"></i> {{ ucfirst($booking->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">
                                        {{ $site_settings->currency_symbol }}{{ number_format($booking->total_price, 2) }}
                                        @if($booking->deposit_amount > 0)
                                            <small class="text-muted d-block">Dep: {{ $site_settings->currency_symbol }}{{ number_format($booking->deposit_amount, 2) }}</small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="7" class="text-end text-uppercase">Total Rooms Revenue:</td>
                                <td class="text-end text-primary fs-6">
                                    {{ $site_settings->currency_symbol }}{{ number_format($roomsRevenue, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>


    <!-- 4. SECTION: VENUE BOOKINGS -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 border-0 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="stat-icon-wrapper badge-soft-primary flex-shrink-0" style="width: 36px; height: 36px; font-size: 15px;">
                    <i class="fas fa-glass-cheers"></i>
                </span>
                <div>
                    <h5 class="section-header-title mb-0">3. Venue & Event Bookings</h5>
                    <small class="text-muted">Venue events scheduled or created for period {{ $dateLabel }}</small>
                </div>
            </div>

            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="badge badge-soft-success px-3 py-2">Confirmed: {{ $venueStatusBreakdown['confirmed'] }}</span>
                <span class="badge badge-soft-warning px-3 py-2">Pending: {{ $venueStatusBreakdown['pending'] }}</span>
                <span class="badge badge-soft-secondary px-3 py-2">Total Deposits: {{ $site_settings->currency_symbol }}{{ number_format($venuesDepositTotal, 2) }}</span>
            </div>
        </div>

        <div class="card-body p-0">
            @if($venueBookings->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-glass-cheers fa-2x mb-2 text-secondary"></i>
                    <p class="mb-0">No venue bookings recorded for this period.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-custom mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Customer Name</th>
                                <th>Venue</th>
                                <th>Package</th>
                                <th>Event Date</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th class="text-end">Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($venueBookings as $vBooking)
                                <tr>
                                    <td class="fw-bold">#VB-{{ $vBooking->id }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $vBooking->customer_name }}</div>
                                        <small class="text-muted d-block"><i class="fas fa-envelope me-1"></i> {{ $vBooking->customer_email ?? 'N/A' }}</small>
                                        <small class="text-muted d-block"><i class="fas fa-phone me-1"></i> {{ $vBooking->customer_phone ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="fw-semibold text-dark">{{ $vBooking->venue->name ?? 'Venue Hall' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-info">
                                            {{ $vBooking->package->name ?? 'Custom Package' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-primary">
                                            <i class="far fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($vBooking->booking_date)->format('M d, Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge {{ $vBooking->payment_status === 'paid' ? 'badge-soft-success' : 'badge-soft-warning' }} text-uppercase">
                                            {{ $vBooking->payment_status ?? 'Pending' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if(in_array($vBooking->status, ['confirmed', 'completed']))
                                            <span class="badge badge-soft-success"><i class="fas fa-check-circle me-1"></i> {{ ucfirst($vBooking->status) }}</span>
                                        @elseif($vBooking->status === 'pending')
                                            <span class="badge badge-soft-warning"><i class="fas fa-clock me-1"></i> Pending</span>
                                        @else
                                            <span class="badge badge-soft-danger"><i class="fas fa-times-circle me-1"></i> {{ ucfirst($vBooking->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold">
                                        {{ $site_settings->currency_symbol }}{{ number_format($vBooking->total_price, 2) }}
                                        @if($vBooking->deposit_amount > 0)
                                            <small class="text-muted d-block">Dep: {{ $site_settings->currency_symbol }}{{ number_format($vBooking->deposit_amount, 2) }}</small>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light fw-bold">
                                <td colspan="7" class="text-end text-uppercase">Total Venue Revenue:</td>
                                <td class="text-end text-primary fs-6">
                                    {{ $site_settings->currency_symbol }}{{ number_format($venuesRevenue, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
