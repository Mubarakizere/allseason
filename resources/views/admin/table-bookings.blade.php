@extends('layouts.admin')

@section('title', 'Table Bookings — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .tbl-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .tbl-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .tbl-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .tbl-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-create-booking {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        background: #dc2626;
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        border: none;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .btn-create-booking:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .tbl-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .tbl-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .tbl-card-title {
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
        $('#bookings-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search reservations..."
            }
        });

        // View Button Logic
        $(document).on('click', '.view-btn', function() {
            $('#viewName').val($(this).data('name'));
            $('#viewEmail').val($(this).data('email'));
            $('#viewPhone').val($(this).data('phone'));
            $('#viewDate').val($(this).data('date'));
            $('#viewTime').val($(this).data('time'));
            $('#viewPersons').val($(this).data('persons'));
        });
        
        // Edit Button Logic
        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            $('#editName').val($(this).data('name'));
            $('#editEmail').val($(this).data('email'));
            $('#editPhone').val($(this).data('phone'));
            $('#editDate').val($(this).data('date'));
            $('#editTime').val($(this).data('time'));
            $('#editPersons').val($(this).data('persons'));

            let actionUrl = "{{ route('admin.table-bookings.update', ':id') }}".replace(':id', id);
            $('#editForm').attr('action', actionUrl);
        });

        @if($loggedInUser->role !== 'sales')
        // Delete Button Logic
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            $('#deleteName').text($(this).data('name'));
            let actionUrl = "{{ route('admin.table-bookings.destroy', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', actionUrl);
        });
        @endif
    });
</script>
@endpush

@section('content')
<div class="content-wrapper tbl-wrap">
    
    @include('partials.message-bag')

    {{-- Header --}}
    <div class="tbl-header">
        <div class="tbl-title-group">
            <h1>Table Reservations</h1>
            <p>Manage customer table reservations and dining requests.</p>
        </div>
        <button class="btn-create-booking" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> New Booking
        </button>
    </div>

    {{-- Bookings Table Card --}}
    <div class="tbl-card">
        <div class="tbl-card-header">
            <h3 class="tbl-card-title">All Reservations ({{ $tableBookings->count() }})</h3>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="bookings-table">
                    <thead>
                        <tr>
                            <th>Guest Name</th>
                            <th>Contact</th>
                            <th>Date & Time</th>
                            <th>Guests</th>
                            <th style="min-width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tableBookings as $booking)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $booking->name }}</div>
                                </td>
                                <td>
                                    <div><i class="fas fa-envelope text-muted me-1" style="font-size:11px;"></i> {{ $booking->email }}</div>
                                    @if($booking->phone)
                                        <small class="text-muted"><i class="fas fa-phone me-1" style="font-size:10px;"></i> {{ $booking->phone }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold text-dark"><i class="fas fa-calendar-alt text-muted me-1" style="font-size:11px;"></i> {{ $booking->date }}</div>
                                    <small class="text-muted"><i class="fas fa-clock me-1" style="font-size:10px;"></i> {{ $booking->time }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border fw-normal" style="padding: 4px 8px;">
                                        <i class="fas fa-user-friends me-1 text-muted"></i> {{ $booking->persons }} {{ $booking->persons == 1 ? 'person' : 'people' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <!-- View -->
                                        <button class="btn btn-sm btn-dark view-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#viewModal"
                                                data-id="{{ $booking->id }}"
                                                data-name="{{ $booking->name }}"
                                                data-email="{{ $booking->email }}"
                                                data-phone="{{ $booking->phone }}"
                                                data-date="{{ $booking->date }}"
                                                data-time="{{ $booking->time }}"
                                                data-persons="{{ $booking->persons }}"
                                                title="View Booking">
                                            <i class="fas fa-eye"></i>
                                        </button>

                                        <!-- Edit -->
                                        <button class="btn btn-sm btn-outline-secondary edit-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal" 
                                                data-id="{{ $booking->id }}"
                                                data-name="{{ $booking->name }}"
                                                data-email="{{ $booking->email }}"
                                                data-phone="{{ $booking->phone }}"
                                                data-date="{{ $booking->date }}"
                                                data-time="{{ $booking->time }}"
                                                data-persons="{{ $booking->persons }}"
                                                title="Edit Booking">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        @if($loggedInUser->role !== 'sales')
                                        <!-- Delete -->
                                        <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal" 
                                                data-id="{{ $booking->id }}"
                                                data-name="{{ $booking->name }}"
                                                title="Delete Booking">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No table bookings available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- View Modal --}}
    <div class="modal fade" id="viewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Reservation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-3">
                    <div class="row g-2">
                        <div class="col-12 mb-2">
                            <label class="fw-semibold text-muted mb-1" style="font-size: 12px;">Guest Name</label>
                            <input type="text" class="form-control" id="viewName" readonly style="font-size: 13px;">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="fw-semibold text-muted mb-1" style="font-size: 12px;">Email</label>
                            <input type="email" class="form-control" id="viewEmail" readonly style="font-size: 13px;">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="fw-semibold text-muted mb-1" style="font-size: 12px;">Phone</label>
                            <input type="text" class="form-control" id="viewPhone" readonly style="font-size: 13px;">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="fw-semibold text-muted mb-1" style="font-size: 12px;">Date</label>
                            <input type="text" class="form-control" id="viewDate" readonly style="font-size: 13px;">
                        </div>
                        <div class="col-6 mb-2">
                            <label class="fw-semibold text-muted mb-1" style="font-size: 12px;">Time</label>
                            <input type="text" class="form-control" id="viewTime" readonly style="font-size: 13px;">
                        </div>
                        <div class="col-12 mb-2">
                            <label class="fw-semibold text-muted mb-1" style="font-size: 12px;">Number of Guests</label>
                            <input type="number" class="form-control" id="viewPersons" readonly style="font-size: 13px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.table-bookings.store') }}" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Create New Reservation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <label for="name" class="fw-semibold mb-1" style="font-size: 12px;">Guest Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required style="font-size: 13px;">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="email" class="fw-semibold mb-1" style="font-size: 12px;">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required style="font-size: 13px;">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="phone" class="fw-semibold mb-1" style="font-size: 12px;">Phone *</label>
                                <input type="text" class="form-control" id="phone" name="phone" required style="font-size: 13px;">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="date" class="fw-semibold mb-1" style="font-size: 12px;">Date *</label>
                                <input type="date" class="form-control" id="date" name="date" required style="font-size: 13px;">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="time" class="fw-semibold mb-1" style="font-size: 12px;">Time *</label>
                                <input type="text" class="form-control" id="time" name="time" placeholder="e.g. 7:30 PM" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="persons" class="fw-semibold mb-1" style="font-size: 12px;">Number of Guests *</label>
                                <input type="number" class="form-control" id="persons" name="persons" min="1" value="2" required style="font-size: 13px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Create Reservation</button>
                    </div>
                </div>
            </form>
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
                        <h5 class="modal-title font-weight-bold">Edit Reservation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <label for="editName" class="fw-semibold mb-1" style="font-size: 12px;">Guest Name *</label>
                                <input type="text" class="form-control" id="editName" name="name" required style="font-size: 13px;">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="editEmail" class="fw-semibold mb-1" style="font-size: 12px;">Email *</label>
                                <input type="email" class="form-control" id="editEmail" name="email" required style="font-size: 13px;">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="editPhone" class="fw-semibold mb-1" style="font-size: 12px;">Phone *</label>
                                <input type="text" class="form-control" id="editPhone" name="phone" required style="font-size: 13px;">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="editDate" class="fw-semibold mb-1" style="font-size: 12px;">Date *</label>
                                <input type="date" class="form-control" id="editDate" name="date" required style="font-size: 13px;">
                            </div>
                            <div class="col-6 mb-2">
                                <label for="editTime" class="fw-semibold mb-1" style="font-size: 12px;">Time *</label>
                                <input type="text" class="form-control" id="editTime" name="time" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="editPersons" class="fw-semibold mb-1" style="font-size: 12px;">Number of Guests *</label>
                                <input type="number" class="form-control" id="editPersons" name="persons" min="1" required style="font-size: 13px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Reservation</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($loggedInUser->role !== 'sales')
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
                        Are you sure you want to delete reservation for <strong id="deleteName"></strong>?
                    </div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection