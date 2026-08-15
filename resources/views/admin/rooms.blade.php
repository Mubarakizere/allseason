@extends('layouts.admin')

@section('title', 'Accommodation & Rooms — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .rm-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .rm-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .rm-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .rm-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-rm {
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
    .btn-add-rm:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .rm-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .rm-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .rm-card-title {
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

    .room-thumb {
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
        $('#rooms-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search rooms..."
            }
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper rm-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="rm-header">
        <div class="rm-title-group">
            <h1>Accommodation & Hotel Rooms</h1>
            <p>Manage room types, nightly rates, guest capacities, and room photo galleries.</p>
        </div>
        <button class="btn-add-rm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Add New Room
        </button>
    </div>

    {{-- Rooms Card --}}
    <div class="rm-card">
        <div class="rm-card-header">
            <h3 class="rm-card-title">All Rooms ({{ $rooms->count() }})</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="rooms-table">
                    <thead>
                        <tr>
                            <th>Cover Photo</th>
                            <th>Room Name</th>
                            <th>Nightly Rate</th>
                            <th>Capacity</th>
                            <th>Deposit Req</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rooms as $room)
                            @php
                                $thumbSrc = $room->image ? asset('storage/' . $room->image) : ($room->images->count() > 0 ? asset('storage/' . $room->images->first()->image) : '/assets/images/placeholder.jpg');
                            @endphp
                            <tr>
                                <td>
                                    <img src="{{ $thumbSrc }}" 
                                         alt="{{ $room->name }}" 
                                         class="room-thumb"
                                         onerror="this.onerror=null;this.src='/assets/images/placeholder.jpg';">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $room->name }}</div>
                                    @if($room->features->count() > 0)
                                        <small class="text-muted" style="font-size: 11px;">{{ $room->features->pluck('name')->implode(', ') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-dark">{!! $site_settings->currency_symbol !!}{{ number_format($room->price, 2) }}</strong>
                                    <small class="text-muted d-block" style="font-size: 11px;">per night</small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-weight-normal" style="font-size: 11.5px;">
                                        <i class="fas fa-user me-1 text-muted"></i> {{ $room->capacity }} {{ $room->capacity == 1 ? 'Guest' : 'Guests' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border" style="font-size: 11px;">
                                        {{ $room->deposit_percentage }}% Deposit
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal{{ $room->id }}" title="Edit Room">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $room->id }}" title="Delete Room">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $room->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0" style="border-radius: 10px;">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title font-weight-bold">Edit Room</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body py-3">
                                            <form method="POST" action="{{ route('admin.rooms.update', $room->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                
                                                <div class="row g-2">
                                                    <div class="col-md-6 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Room Name *</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $room->name }}" required style="font-size: 13px;">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Nightly Rate ({!! $site_settings->currency_symbol !!}) *</label>
                                                        <input type="number" name="price" class="form-control" value="{{ $room->price }}" step="0.01" required style="font-size: 13px;">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Capacity (Guests) *</label>
                                                        <input type="number" name="capacity" class="form-control" value="{{ $room->capacity }}" required style="font-size: 13px;">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Deposit Percentage (%) *</label>
                                                        <input type="number" name="deposit_percentage" class="form-control" value="{{ $room->deposit_percentage }}" step="0.01" required style="font-size: 13px;">
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Inclusions / Features (Comma separated)</label>
                                                        <input type="text" name="features" class="form-control" value="{{ $room->features->pluck('name')->implode(', ') }}" placeholder="e.g. Breakfast, Free Wi-Fi, Air Conditioning" style="font-size: 13px;">
                                                    </div>
                                                    <div class="col-12 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Description</label>
                                                        <textarea name="description" class="form-control" rows="3" style="font-size: 13px;">{{ $room->description }}</textarea>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Replace Main Image</label>
                                                        <input type="file" name="image" class="form-control" accept="image/*" style="font-size: 13px;">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="fw-semibold mb-1" style="font-size: 12px;">Add Gallery Photos</label>
                                                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple style="font-size: 13px;">
                                                    </div>
                                                </div>

                                                <div class="text-end mt-3">
                                                    <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Room Changes</button>
                                                </div>
                                            </form>
                                            
                                            <hr class="my-4">
                                            
                                            <h6 class="fw-bold mb-3" style="font-size: 13px;">Manage Existing Images</h6>
                                            <div class="row g-2">
                                                @if($room->image)
                                                    <div class="col-6 col-md-3 text-center">
                                                        <div class="border rounded p-1 mb-2 bg-light">
                                                            <img src="{{ asset('storage/' . $room->image) }}" class="w-100 rounded mb-1" style="height: 90px; object-fit: cover;">
                                                            <span class="badge bg-dark" style="font-size: 10px;">Main Cover Photo</span>
                                                        </div>
                                                    </div>
                                                @endif
                                                @forelse($room->images as $image)
                                                    <div class="col-6 col-md-3 text-center">
                                                        <div class="border rounded p-1 mb-2 bg-light">
                                                            <img src="{{ asset('storage/' . $image->image) }}" 
                                                                 class="w-100 rounded mb-2" 
                                                                 style="height: 90px; object-fit: cover;"
                                                                 onerror="this.onerror=null;this.src='/assets/images/placeholder.jpg';">
                                                            <form action="{{ route('admin.rooms.delete-image', $image->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="font-size: 11px;">
                                                                    <i class="fas fa-trash me-1"></i> Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @empty
                                                    @if(!$room->image)
                                                        <p class="text-muted small">No gallery photos uploaded yet.</p>
                                                    @endif
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
                            <div class="modal fade" id="deleteModal{{ $room->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <form method="POST" action="{{ route('admin.rooms.destroy', $room->id) }}" style="width: 100%;">
                                        @csrf
                                        @method('DELETE')
                                        <div class="modal-content border-0" style="border-radius: 10px;">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title font-weight-bold">Confirm Deletion</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-center py-4" style="font-size: 13.5px; color: #4b5563;">
                                                Are you sure you want to delete <strong>{{ $room->name }}</strong>?
                                                <div class="text-danger small mt-1"><i class="fas fa-exclamation-triangle me-1"></i> This will also remove related room bookings!</div>
                                            </div>
                                            <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                                                <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete Room</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No rooms available.</td>
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
            <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Add New Room</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Room Name *</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Deluxe Garden Suite" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Nightly Rate ({!! $site_settings->currency_symbol !!}) *</label>
                                <input type="number" name="price" class="form-control" step="0.01" required placeholder="0.00" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Capacity (Guests) *</label>
                                <input type="number" name="capacity" class="form-control" value="2" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Deposit Percentage (%) *</label>
                                <input type="number" name="deposit_percentage" class="form-control" value="20" step="0.01" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Inclusions / Features (Comma separated)</label>
                                <input type="text" name="features" class="form-control" placeholder="e.g. Breakfast, Free Wi-Fi, Air Conditioning" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Describe room size, view, bed type..." style="font-size: 13px;"></textarea>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Main Cover Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Additional Photos</label>
                                <input type="file" name="images[]" class="form-control" accept="image/*" multiple style="font-size: 13px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Create Room</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
