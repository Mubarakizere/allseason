@extends('layouts.admin')

@section('title', 'Stock Movement Ledger — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .his-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .his-header {
        margin-bottom: 24px;
    }
    .his-header h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .his-header p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    /* Card & Table */
    .his-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .his-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .his-card-title {
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
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#stock-history-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            order: [],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search stock movements..."
            }
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper his-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="his-header">
        <h1>Stock Movement Ledger</h1>
        <p>Complete audit log of stock entries, issues, sales, and running inventory balances.</p>
    </div>

    {{-- History Card --}}
    <div class="his-card">
        <div class="his-card-header">
            <h3 class="his-card-title">Movement Log</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="stock-history-table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>Stock Item</th>
                            <th>Movement Type</th>
                            <th>Qty Change</th>
                            <th>Running Balance</th>
                            <th>Reference</th>
                            <th>User / Staff</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $history)
                            <tr>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $history->date }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $history->stockItem->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    @if($history->type == 'in')
                                        <span class="badge bg-success text-white fw-semibold" style="font-size: 11px;">STOCK IN</span>
                                    @elseif($history->type == 'out')
                                        <span class="badge bg-danger text-white fw-semibold" style="font-size: 11px;">STOCK OUT</span>
                                    @elseif($history->type == 'sale')
                                        <span class="badge bg-primary text-white fw-semibold" style="font-size: 11px;">POS SALE</span>
                                    @elseif($history->type == 'issue')
                                        <span class="badge bg-warning text-dark fw-semibold" style="font-size: 11px;">DEPARTMENT ISSUE</span>
                                    @else
                                        <span class="badge bg-secondary text-white fw-semibold" style="font-size: 11px;">{{ strtoupper($history->type) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="{{ $history->type == 'in' ? 'text-success' : 'text-danger' }}">
                                        {{ $history->type == 'in' ? '+' : '-' }}{{ $history->quantity }}
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-bold" style="font-size: 11.5px;">{{ $history->balance }}</span>
                                </td>
                                <td>
                                    <span class="text-muted font-monospace" style="font-size: 11.5px;">{{ $history->reference ?: '—' }}</span>
                                    @if($history->note)
                                        <small class="d-block text-muted" style="font-size: 11px;">{{ $history->note }}</small>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-dark fw-semibold"><i class="fas fa-user-circle me-1 text-muted"></i> {{ $history->user->first_name ?? 'System' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No stock history recorded.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
