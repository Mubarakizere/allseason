@extends('layouts.admin')

@section('title', 'Bar Stock & Consumption Reports — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .bar-rpt-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .bar-rpt-header {
        margin-bottom: 24px;
    }
    .bar-rpt-header h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .bar-rpt-header p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    /* Metric Cards */
    .bar-metric-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 18px 20px;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 14px;
    }
    .bar-metric-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .icon-total-bar { background: #fffbe6; color: #d97706; }
    .icon-alert-bar { background: #fef2f2; color: #dc2626; }
    .icon-logs-bar { background: #f0fdf4; color: #16a34a; }

    .bar-metric-title {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 2px;
    }
    .bar-metric-val {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0;
        letter-spacing: -0.02em;
    }

    /* Low Stock Warning Card */
    .alert-card-box {
        background: #fffdfc;
        border: 1px solid #fee2e2;
        border-left: 4px solid #dc2626;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 24px;
    }
    .alert-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #991b1b;
        margin: 0 0 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .alert-card-sub {
        font-size: 12.5px;
        color: #7f1d1d;
        margin: 0 0 12px;
    }
    .low-stock-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .low-stock-pill {
        background: #ffffff;
        border: 1px solid #fca5a5;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 12px;
        color: #991b1b;
        font-weight: 600;
    }

    /* Card & Table */
    .bar-rpt-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .bar-rpt-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
    }
    .bar-rpt-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper {
        padding: 0;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        padding: 14px 20px 10px;
        font-size: 13px;
        color: #6b7280;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 13px;
        outline: none;
        margin-left: 8px;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #dc2626;
    }
    table.dataTable {
        border-collapse: collapse !important;
        width: 100% !important;
        border: none !important;
        margin: 0 !important;
    }
    table.dataTable<thead>th {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb !important;
        border-top: none !important;
        color: #374151;
        font-weight: 600;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 10px 18px !important;
    }
    table.dataTable<tbody>td {
        padding: 12px 18px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6 !important;
        border-top: none !important;
        color: #111827;
        font-size: 13px;
    }
    table.dataTable<tbody>tr:hover {
        background-color: #f9fafb !important;
    }
    .dataTables_info,
    .dataTables_paginate {
        padding: 12px 20px !important;
        font-size: 12.5px;
        color: #6b7280;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px !important;
        border: 1px solid #e5e7eb !important;
        background: #ffffff !important;
        color: #374151 !important;
        font-size: 12px !important;
        padding: 3px 9px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #111827 !important;
        color: #ffffff !important;
        border-color: #111827 !important;
        box-shadow: none !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#bar-logs-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search bar logs..."
            }
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper bar-rpt-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="bar-rpt-header">
        <h1>Bar Reports & Consumption</h1>
        <p>Overview of bar beverage stock, bottle consumption history, and low bottle alerts.</p>
    </div>

    {{-- 3 Summary Metric Cards --}}
    <div class="row g-3 mb-4">
        <!-- Total Drinks -->
        <div class="col-md-4">
            <div class="bar-metric-card">
                <div class="bar-metric-icon icon-total-bar">
                    <i class="fas fa-wine-bottle"></i>
                </div>
                <div>
                    <div class="bar-metric-title">Total Drink Items</div>
                    <div class="bar-metric-val">{{ number_format($barItems->count()) }}</div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="col-md-4">
            <div class="bar-metric-card">
                <div class="bar-metric-icon icon-alert-bar">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="bar-metric-title">Low Bottle Alerts</div>
                    <div class="bar-metric-val text-danger">{{ number_format($lowStockItems->count()) }}</div>
                </div>
            </div>
        </div>

        <!-- Total Logs Tracked -->
        <div class="col-md-4">
            <div class="bar-metric-card">
                <div class="bar-metric-icon icon-logs-bar">
                    <i class="fas fa-glass-cheers"></i>
                </div>
                <div>
                    <div class="bar-metric-title">Drink Logs Tracked</div>
                    <div class="bar-metric-val text-success">{{ number_format($consumptionLogs->count()) }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Low Stock Alerts Card --}}
    @if($lowStockItems->isNotEmpty())
        <div class="alert-card-box">
            <h3 class="alert-card-title">
                <i class="fas fa-exclamation-circle me-1"></i> Low Bottle & Drink Stock Warning
            </h3>
            <p class="alert-card-sub">The following bar drinks or raw spirits have reached or fallen below their alert thresholds:</p>
            
            <div class="low-stock-grid">
                @foreach ($lowStockItems as $item)
                    <div class="low-stock-pill">
                        {{ $item->name }}: <strong>{{ number_format($item->quantity, 2) }} {{ $item->unit }}</strong> (Alert: {{ $item->alert_quantity }})
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Consumption Log Table Card --}}
    <div class="bar-rpt-card">
        <div class="bar-rpt-card-header">
            <h3 class="bar-rpt-card-title">Recent Bar Consumption Log</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="bar-logs-table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Drink Stock Item</th>
                            <th>Deducted Qty</th>
                            <th>Previous Stock</th>
                            <th>New Stock</th>
                            <th>Reason / Activity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($consumptionLogs as $log)
                            <tr>
                                <td>
                                    <div class="text-dark">{{ $log->created_at->format('g:i A — d M, Y') }}</div>
                                    <small class="text-muted" style="font-size: 11px;">{{ $log->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $log->stockItem->name ?? 'Bar Item' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-danger text-white fw-semibold" style="font-size: 11px;">
                                        -{{ number_format($log->quantity, 2) }} {{ $log->stockItem->unit ?? '' }}
                                    </span>
                                </td>
                                <td>{{ number_format($log->previous_quantity, 2) }}</td>
                                <td>
                                    <strong class="text-dark">{{ number_format($log->new_quantity, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size: 12px;">{{ $log->notes }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No bar consumption activity logged yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
