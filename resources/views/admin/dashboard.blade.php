@extends('layouts.admin')

@section('title', 'Admin Dashboard — All The Season Garden')

@push('styles')
<style>
    /* ── Dashboard Base Styles ── */
    .dash-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .dash-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .dash-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .dash-actions {
        display: flex;
        gap: 10px;
    }
    .dash-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        transition: all 0.15s ease;
        border: 1px solid transparent;
    }
    .dash-btn-primary {
        background: #dc2626;
        color: #ffffff !important;
    }
    .dash-btn-primary:hover {
        background: #b91c1c;
    }
    .dash-btn-outline {
        background: #ffffff;
        color: #374151 !important;
        border-color: #e5e7eb;
    }
    .dash-btn-outline:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    /* ── Metric Cards ── */
    .dash-metric-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 18px 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: border-color 0.15s ease, box-shadow 0.15s ease;
        text-decoration: none !important;
        color: inherit !important;
    }
    .dash-metric-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }
    .dash-metric-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .dash-metric-title {
        font-size: 13px;
        font-weight: 600;
        color: #6b7280;
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .dash-metric-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .dash-metric-value {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 6px 0;
        letter-spacing: -0.02em;
    }
    .dash-metric-footer {
        display: flex;
        align-items: center;
        font-size: 12px;
        font-weight: 500;
        color: #9ca3af;
        margin-top: 4px;
    }
    .dash-metric-footer i {
        margin-left: 4px;
        font-size: 10px;
        transition: transform 0.15s ease;
    }
    .dash-metric-card:hover .dash-metric-footer i {
        transform: translateX(3px);
    }

    .icon-sales { background: #f0fdf4; color: #16a34a; }
    .icon-rooms { background: #eff6ff; color: #2563eb; }
    .icon-venues { background: #fff7ed; color: #ea580c; }
    .icon-customers { background: #fdf2f8; color: #db2777; }

    /* ── Chart Container ── */
    .dash-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 22px 24px;
        margin-top: 24px;
    }
    .dash-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .dash-card-title {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .dash-card-subtitle {
        font-size: 12.5px;
        color: #6b7280;
        margin: 2px 0 0;
    }
    .chart-wrapper {
        position: relative;
        height: 320px;
        width: 100%;
    }

    /* ── Quick Shortcuts ── */
    .quick-link-card {
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none !important;
        color: inherit !important;
        transition: all 0.15s ease;
    }
    .quick-link-card:hover {
        background: #ffffff;
        border-color: #d1d5db;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    }
    .quick-link-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: #111827;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }
    .quick-link-text h5 {
        font-size: 13.5px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 2px;
    }
    .quick-link-text p {
        font-size: 11.5px;
        color: #6b7280;
        margin: 0;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var canvas = document.getElementById('salesBarChart');
        if (!canvas) return;

        var ctx = canvas.getContext('2d');
        var salesData = {!! json_encode($formattedSalesData->values()->toArray()) !!};
        var salesLabels = {!! json_encode($formattedSalesData->keys()->toArray()) !!};
        var currencySymbol = {!! json_encode($site_settings->currency_symbol ?? '$') !!};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Sales Amount',
                    data: salesData,
                    backgroundColor: '#ef4444',
                    hoverBackgroundColor: '#dc2626',
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 24
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f1117',
                        titleFont: { size: 13, weight: '600' },
                        bodyFont: { size: 13 },
                        padding: 10,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                var val = context.raw || 0;
                                return ' Sales: ' + currencySymbol + val.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 11 },
                            color: '#6b7280'
                        }
                    },
                    y: {
                        border: { dash: [4, 4] },
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            font: { size: 11 },
                            color: '#6b7280',
                            callback: function(value) {
                                return currencySymbol + value.toLocaleString();
                            }
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper">
    
    @include('partials.message-bag')

    {{-- Header --}}
    <div class="dash-header">
        <div class="dash-title-group">
            <h1>Dashboard Overview</h1>
            <p>Welcome back, {{ $loggedInUser->first_name }}! Here is what's happening today.</p>
        </div>
        <div class="dash-actions">
            <a href="{{ route('admin.pos.index') }}" class="dash-btn dash-btn-primary">
                <i class="fas fa-cash-register"></i> Open POS
            </a>
            <a href="{{ route('admin.orders.index') }}" class="dash-btn dash-btn-outline">
                <i class="fas fa-list"></i> Manage Orders
            </a>
        </div>
    </div>

    {{-- Order Statistics Row --}}
    @include('partials.order-stats')

    {{-- Business Metrics Row --}}
    <div class="row g-3 mt-1">
        
        <!-- Sales Today -->
        <div class="col-lg-3 col-sm-6">
            <a href="{{ route('admin.orders.index', ['filter' => 'completed']) }}" class="dash-metric-card">
                <div>
                    <div class="dash-metric-header">
                        <span class="dash-metric-title">Sales Today</span>
                        <div class="dash-metric-icon icon-sales">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                    <div class="dash-metric-value">{!! $site_settings->currency_symbol !!}{{ number_format($salesToday, 2) }}</div>
                </div>
                <div class="dash-metric-footer">
                    <span>View completed orders</span>
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
        </div>

        <!-- Room Bookings -->
        <div class="col-lg-3 col-sm-6">
            <a href="{{ route('admin.room-bookings.index') }}" class="dash-metric-card">
                <div>
                    <div class="dash-metric-header">
                        <span class="dash-metric-title">Active Room Bookings</span>
                        <div class="dash-metric-icon icon-rooms">
                            <i class="fas fa-bed"></i>
                        </div>
                    </div>
                    <div class="dash-metric-value">{{ number_format($activeRoomBookings) }}</div>
                </div>
                <div class="dash-metric-footer">
                    <span>View room bookings</span>
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
        </div>

        <!-- Venue Bookings -->
        <div class="col-lg-3 col-sm-6">
            <a href="{{ route('admin.venue-bookings.index') }}" class="dash-metric-card">
                <div>
                    <div class="dash-metric-header">
                        <span class="dash-metric-title">Active Venue Bookings</span>
                        <div class="dash-metric-icon icon-venues">
                            <i class="fas fa-glass-cheers"></i>
                        </div>
                    </div>
                    <div class="dash-metric-value">{{ number_format($activeVenueBookings) }}</div>
                </div>
                <div class="dash-metric-footer">
                    <span>View venue bookings</span>
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>
        </div>

        <!-- Total Customers -->
        <div class="col-lg-3 col-sm-6">
            <div class="dash-metric-card">
                <div>
                    <div class="dash-metric-header">
                        <span class="dash-metric-title">Total Customers</span>
                        <div class="dash-metric-icon icon-customers">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="dash-metric-value">{{ number_format($totalCustomers) }}</div>
                </div>
                <div class="dash-metric-footer">
                    <span>Registered users</span>
                </div>
            </div>
        </div>

    </div>

    {{-- Monthly Sales Chart --}}
    <div class="dash-card">
        <div class="dash-card-header">
            <div>
                <h3 class="dash-card-title">Monthly Revenue Trend ({{ date('Y') }})</h3>
                <p class="dash-card-subtitle">Overview of monthly completed sales revenue</p>
            </div>
        </div>
        <div class="chart-wrapper">
            <canvas id="salesBarChart"></canvas>
        </div>
    </div>

    {{-- Quick Operational Shortcuts --}}
    <div class="dash-card" style="margin-top: 20px;">
        <div class="dash-card-header" style="margin-bottom: 14px;">
            <h3 class="dash-card-title">Quick Operations</h3>
        </div>
        <div class="row g-3">
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.kitchen.kot') }}" class="quick-link-card">
                    <div class="quick-link-icon"><i class="fas fa-utensils"></i></div>
                    <div class="quick-link-text">
                        <h5>Live KOT Display</h5>
                        <p>Kitchen order tickets</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.bar.inventory') }}" class="quick-link-card">
                    <div class="quick-link-icon"><i class="fas fa-cocktail"></i></div>
                    <div class="quick-link-text">
                        <h5>Bar Inventory</h5>
                        <p>Drink & beverage stock</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.stock-items.index') }}" class="quick-link-card">
                    <div class="quick-link-icon"><i class="fas fa-boxes"></i></div>
                    <div class="quick-link-text">
                        <h5>Stock Items</h5>
                        <p>Raw materials & inventory</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="{{ route('admin.payroll.index') }}" class="quick-link-card">
                    <div class="quick-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                    <div class="quick-link-text">
                        <h5>Payroll</h5>
                        <p>Staff salaries & history</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection