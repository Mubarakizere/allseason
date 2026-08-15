@extends('layouts.admin')

@section('title', 'Venue Bookings — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .vb-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .vb-header {
        margin-bottom: 24px;
    }
    .vb-header h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .vb-header p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }

    /* Card & Table */
    .vb-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .vb-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .vb-card-title {
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
        $('#venue-bookings-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search venue bookings..."
            }
        });

        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            let status = $(this).data('status');
            let payment = $(this).data('payment');

            $('#editStatus').val(status);
            $('#editPayment').val(payment);

            let actionUrl = "{{ route('admin.venue-bookings.update', ':id') }}".replace(':id', id);
            $('#editForm').attr('action', actionUrl);
        });

        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            let actionUrl = "{{ route('admin.venue-bookings.destroy', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', actionUrl);
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper vb-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="vb-header">
        <h1>Venue Reservations & Bookings</h1>
        <p>Manage customer reservations for wedding and event venues.</p>
    </div>

    {{-- Bookings Card --}}
    <div class="vb-card">
        <div class="vb-card-header">
            <h3 class="vb-card-title">All Venue Bookings ({{ $bookings->count() }})</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="venue-bookings-table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Venue & Package</th>
                            <th>Event Date</th>
                            <th>Total & Deposit</th>
                            <th>Payment Status</th>
                            <th>Booking Status</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $booking->customer_name }}</div>
                                    <div class="text-muted" style="font-size: 11px;">
                                        <i class="fas fa-envelope me-1"></i> {{ $booking->customer_email }}
                                    </div>
                                    @if($booking->customer_phone)
                                        <div class="text-muted" style="font-size: 11px;">
                                            <i class="fas fa-phone me-1"></i> {{ $booking->customer_phone }}
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">{{ $booking->venue->name ?? 'N/A' }}</div>
                                    <span class="badge bg-light text-dark border font-weight-normal" style="font-size: 11px;">
                                        {{ $booking->package->name ?? 'Custom Package' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark">
                                        <i class="fas fa-calendar-alt text-muted me-1"></i> {{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}
                                    </div>
                                    <small class="text-muted" style="font-size: 11px;">{{ \Carbon\Carbon::parse($booking->booking_date)->diffForHumans() }}</small>
                                </td>
                                <td>
                                    <div>Total: <strong class="text-dark">{!! $site_settings->currency_symbol !!}{{ number_format($booking->total_price, 2) }}</strong></div>
                                    <small class="text-muted" style="font-size: 11px;">Deposit: {!! $site_settings->currency_symbol !!}{{ number_format($booking->deposit_amount, 2) }}</small>
                                </td>
                                <td>
                                    @if($booking->payment_status == 'unpaid')
                                        <span class="badge bg-warning text-dark fw-semibold" style="font-size: 11px;">Unpaid</span>
                                    @elseif($booking->payment_status == 'deposit_paid')
                                        <span class="badge bg-info text-dark fw-semibold" style="font-size: 11px;">Deposit Paid</span>
                                    @else
                                        <span class="badge bg-success text-white fw-semibold" style="font-size: 11px;">Fully Paid</span>
                                    @endif
                                </td>
                                <td>
                                    @if($booking->status == 'pending')
                                        <span class="badge bg-warning text-dark fw-semibold" style="font-size: 11px;">Pending</span>
                                    @elseif($booking->status == 'confirmed')
                                        <span class="badge bg-success text-white fw-semibold" style="font-size: 11px;">Confirmed</span>
                                    @else
                                        <span class="badge bg-danger text-white fw-semibold" style="font-size: 11px;">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button class="btn btn-sm btn-outline-secondary edit-btn" 
                                            data-id="{{ $booking->id }}" 
                                            data-status="{{ $booking->status }}"
                                            data-payment="{{ $booking->payment_status }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            title="Update Status">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-outline-danger delete-btn" 
                                            data-id="{{ $booking->id }}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal"
                                            title="Delete Booking">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No venue bookings available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="editForm" style="width: 100%;">
                @csrf
                @method('PUT')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Update Booking Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-3">
                            <label class="fw-semibold mb-1" style="font-size: 12px;">Payment Status *</label>
                            <select name="payment_status" id="editPayment" class="form-select" required style="font-size: 13px;">
                                <option value="unpaid">Unpaid</option>
                                <option value="deposit_paid">Deposit Paid</option>
                                <option value="fully_paid">Fully Paid</option>
                            </select>
                        </div>
                        <div class="mb-2">
                            <label class="fw-semibold mb-1" style="font-size: 12px;">Booking Status *</label>
                            <select name="status" id="editStatus" class="form-select" required style="font-size: 13px;">
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Status</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" id="deleteForm" style="width: 100%;">
                @csrf
                @method('DELETE')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4" style="font-size: 13.5px; color: #4b5563;">
                        Are you sure you want to delete this venue booking record?
                    </div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
