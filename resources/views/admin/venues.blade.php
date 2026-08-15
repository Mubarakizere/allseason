@extends('layouts.admin')

@section('title', 'Wedding & Event Venues — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .vn-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .vn-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .vn-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .vn-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-vn {
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
    .btn-add-vn:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .vn-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .vn-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .vn-card-title {
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

    .venue-thumb {
        width: 52px;
        height: 52px;
        border-radius: 8px;
        object-fit: cover;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#venues-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search venues..."
            }
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper vn-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="vn-header">
        <div class="vn-title-group">
            <h1>Wedding & Event Venues</h1>
            <p>Manage event spaces, deposit requirements, gallery photos, and venue packages.</p>
        </div>
        <button class="btn-add-vn" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Add New Venue
        </button>
    </div>

    {{-- Venues Card --}}
    <div class="vn-card">
        <div class="vn-card-header">
            <h3 class="vn-card-title">All Venues ({{ $venues->count() }})</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="venues-table">
                    <thead>
                        <tr>
                            <th>Cover Photo</th>
                            <th>Venue Name</th>
                            <th>Deposit Required</th>
                            <th>Photos</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($venues as $venue)
                            <tr>
                                <td>
                                    @if($venue->images->count() > 0)
                                        <img src="{{ asset('storage/' . $venue->images->first()->image_path) }}" 
                                             alt="{{ $venue->name }}" 
                                             class="venue-thumb"
                                             onerror="this.onerror=null;this.src='/assets/images/placeholder.jpg';">
                                    @else
                                        <img src="/assets/images/placeholder.jpg" alt="{{ $venue->name }}" class="venue-thumb">
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $venue->name }}</div>
                                    @if($venue->description)
                                        <small class="text-muted" style="font-size: 11px;">{{ Str::limit($venue->description, 60) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-weight-normal" style="font-size: 11.5px;">
                                        {{ $venue->deposit_percentage }}% Deposit
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border" style="font-size: 11px;">
                                        <i class="fas fa-image me-1"></i> {{ $venue->images->count() }} {{ $venue->images->count() == 1 ? 'photo' : 'photos' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal{{ $venue->id }}" title="Edit Venue">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $venue->id }}" title="Delete Venue">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $venue->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0" style="border-radius: 10px;">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title font-weight-bold">Edit Venue</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body py-3">
                                            <form method="POST" action="{{ route('admin.venues.update', $venue->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                
                                                <div class="row g-2">
                                                    <div class="col-md-6 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Venue Name *</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $venue->name }}" required style="font-size: 13px;">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Deposit Percentage (%) *</label>
                                                        <input type="number" name="deposit_percentage" class="form-control" value="{{ $venue->deposit_percentage }}" step="0.01" required style="font-size: 13px;">
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Description</label>
                                                        <textarea name="description" class="form-control" rows="3" style="font-size: 13px;">{{ $venue->description }}</textarea>
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Add Gallery Photos</label>
                                                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple style="font-size: 13px;">
                                                    </div>
                                                </div>

                                                <div class="text-end mt-3">
                                                    <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Venue Changes</button>
                                                </div>
                                            </form>
                                            
                                            <hr class="my-4">
                                            
                                            <h6 class="fw-bold mb-3" style="font-size: 13px;">Manage Gallery Photos</h6>
                                            <div class="row g-2">
                                                @forelse($venue->images as $image)
                                                    <div class="col-6 col-md-3 text-center">
                                                        <div class="border rounded p-1 mb-2 bg-light">
                                                            <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                                 class="w-100 rounded mb-2" 
                                                                 style="height: 90px; object-fit: cover;"
                                                                 onerror="this.onerror=null;this.src='/assets/images/placeholder.jpg';">
                                                            <form action="{{ route('admin.venues.delete-image', $image->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="font-size: 11px;">
                                                                    <i class="fas fa-trash me-1"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-muted small">No photos uploaded for this venue yet.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pt-0 pb-3">
                                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Delete Modal --}}
                            <div class="modal fade" id="deleteModal{{ $venue->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form method="POST" action="{{ route('admin.venues.destroy', $venue->id) }}" style="width: 100%;">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content border-0" style="border-radius: 10px;">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title font-weight-bold">Confirm Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center py-4" style="font-size: 13.5px; color: #4b5563;">
                                                Are you sure you want to delete <strong>{{ $venue->name }}</strong>?
                                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-triangle me-1"></i> This will also remove related packages and bookings!</div>
                                            </div>
                                            <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                                                <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete Venue</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No wedding or event venues available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.venues.store') }}" enctype="multipart/form-data" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Add New Venue</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Venue Name *</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Garden Pavilion" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Deposit Percentage (%) *</label>
                                <input type="number" name="deposit_percentage" class="form-control" value="20" step="0.01" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Describe capacity, amenities, seating..." style="font-size: 13px;"></textarea>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Gallery Photos</label>
                                <input type="file" name="images[]" class="form-control" accept="image/*" multiple style="font-size: 13px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Create Venue</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
