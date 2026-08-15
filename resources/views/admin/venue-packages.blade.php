@extends('layouts.admin')

@section('title', 'Venue Packages — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .pkg-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .pkg-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .pkg-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .pkg-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-pkg {
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
    .btn-add-pkg:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .pkg-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .pkg-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .pkg-card-title {
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
        $('#packages-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search venue packages..."
            }
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper pkg-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="pkg-header">
        <div class="pkg-title-group">
            <h1>Venue Packages & Pricing</h1>
            <p>Configure pricing tiers, included features, and photo galleries for event venues.</p>
        </div>
        <button class="btn-add-pkg" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Add New Package
        </button>
    </div>

    {{-- Packages Card --}}
    <div class="pkg-card">
        <div class="pkg-card-header">
            <h3 class="pkg-card-title">All Venue Packages</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="packages-table">
                    <thead>
                        <tr>
                            <th>Venue</th>
                            <th>Package Name</th>
                            <th>Price</th>
                            <th>Included Features</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($venues as $venue)
                            @foreach ($venue->packages as $package)
                                <tr>
                                    <td>
                                        <span class="badge bg-light text-dark border font-weight-normal" style="font-size: 11.5px;">{{ $venue->name }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $package->name }}</div>
                                    </td>
                                    <td>
                                        <strong class="text-dark">{!! $site_settings->currency_symbol !!}{{ number_format($package->price, 2) }}</strong>
                                    </td>
                                    <td>
                                        <ul class="mb-0 ps-3 text-muted" style="font-size: 12px; list-style-type: disc;">
                                            @foreach($package->features as $feature)
                                                <li>{{ $feature->name }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal{{ $package->id }}" title="Edit Package">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $package->id }}" title="Delete Package">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                {{-- Edit Modal --}}
                                <div class="modal fade" id="editModal{{ $package->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0" style="border-radius: 10px;">
                                            <div class="modal-header border-0 pb-0">
                                                <h5 class="modal-title font-weight-bold">Edit Venue Package</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body py-3">
                                                <form method="POST" action="{{ route('admin.venue-packages.update', $package->id) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="row g-2">
                                                        <div class="col-md-6 mb-2">
                                                            <label class="fw-semibold mb-1" style="font-size: 12px;">Package Name *</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $package->name }}" required style="font-size: 13px;">
                                                        </div>
                                                        <div class="col-md-6 mb-2">
                                                            <label class="fw-semibold mb-1" style="font-size: 12px;">Price ({!! $site_settings->currency_symbol !!}) *</label>
                                                            <input type="number" name="price" class="form-control" value="{{ $package->price }}" step="0.01" required style="font-size: 13px;">
                                                        </div>
                                                        <div class="col-12 mb-2">
                                                            <label class="fw-semibold mb-1" style="font-size: 12px;">Features (Comma separated)</label>
                                                            <textarea name="features" class="form-control" rows="3" style="font-size: 13px;">{{ implode(', ', $package->features->pluck('name')->toArray()) }}</textarea>
                                                        </div>
                                                        <div class="col-12 mb-2">
                                                            <label class="fw-semibold mb-1" style="font-size: 12px;">Add More Images</label>
                                                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple style="font-size: 13px;">
                                                        </div>
                                                    </div>

                                                    <div class="text-end mt-3">
                                                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Package Changes</button>
                                                    </div>
                                                </form>

                                                <hr class="my-4">
                                                
                                                <h6 class="fw-bold mb-3" style="font-size: 13px;">Manage Package Images</h6>
                                                <div class="row g-2">
                                                    @forelse($package->images as $image)
                                                        <div class="col-6 col-md-3 text-center">
                                                            <div class="border rounded p-1 mb-2 bg-light">
                                                                <img src="{{ asset('storage/' . $image->image_path) }}" 
                                                                     class="w-100 rounded mb-2" 
                                                                     style="height: 90px; object-fit: cover;"
                                                                     onerror="this.onerror=null;this.src='/assets/images/placeholder.jpg';">
                                                                <form action="{{ route('admin.venue-packages.delete-image', $image->id) }}" method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="font-size: 11px;">
                                                                        <i class="fas fa-trash me-1"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @empty
                                                        <p class="text-muted small">No extra photos uploaded for this package.</p>
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
                                <div class="modal fade" id="deleteModal{{ $package->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form method="POST" action="{{ route('admin.venue-packages.destroy', $package->id) }}" style="width: 100%;">
                                            @csrf
                                            @method('DELETE')
                                            <div class="modal-content border-0" style="border-radius: 10px;">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="modal-title font-weight-bold">Confirm Deletion</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body text-center py-4" style="font-size: 13.5px; color: #4b5563;">
                                                    Are you sure you want to delete package <strong>{{ $package->name }}</strong>?
                                                </div>
                                                <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                                                    <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete Package</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No venue packages available.</td>
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
            <form method="POST" action="{{ route('admin.venue-packages.store') }}" enctype="multipart/form-data" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Add New Venue Package</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Select Venue *</label>
                                <select name="venue_id" class="form-select" required style="font-size: 13px;">
                                    <option value="">Select Venue...</option>
                                    @foreach($venues as $v)
                                        <option value="{{ $v->id }}">{{ $v->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Package Name *</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Premium Wedding Package" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Price ({!! $site_settings->currency_symbol !!}) *</label>
                                <input type="number" name="price" class="form-control" step="0.01" required placeholder="0.00" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Features (Comma separated)</label>
                                <textarea name="features" class="form-control" rows="3" placeholder="e.g. 50 Chairs, Stage setup, Sound system" style="font-size: 13px;"></textarea>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Images</label>
                                <input type="file" name="images[]" class="form-control" accept="image/*" multiple style="font-size: 13px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Create Package</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
