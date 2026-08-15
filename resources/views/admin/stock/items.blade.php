@extends('layouts.admin')

@section('title', 'Stock Items — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .itm-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .itm-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .itm-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .itm-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-itm {
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
    .btn-add-itm:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .itm-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .itm-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .itm-card-title {
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
        $('#stock-items-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search stock items..."
            }
        });

        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            $('#editName').val($(this).data('name'));
            $('#editCategory').val($(this).data('category_id'));
            $('#editSku').val($(this).data('sku'));
            $('#editUnit').val($(this).data('unit'));
            $('#editAlertQuantity').val($(this).data('alert_quantity'));
            $('#editCostPrice').val($(this).data('cost_price'));
            $('#editDescription').val($(this).data('description'));

            let actionUrl = "{{ route('admin.stock-items.update', ':id') }}".replace(':id', id);
            $('#editForm').attr('action', actionUrl);
        });

        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            let actionUrl = "{{ route('admin.stock-items.destroy', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', actionUrl);
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper itm-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="itm-header">
        <div class="itm-title-group">
            <h1>Stock Items & Master Catalog</h1>
            <p>Manage raw inventory products, SKU codes, unit types, and reorder threshold levels.</p>
        </div>
        <button class="btn-add-itm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Add New Item
        </button>
    </div>

    {{-- Items Card --}}
    <div class="itm-card">
        <div class="itm-card-header">
            <h3 class="itm-card-title">All Stock Items ({{ $items->count() }})</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="stock-items-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>SKU</th>
                            <th>Unit</th>
                            <th>Current Stock</th>
                            <th>Alert Threshold</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->name }}</div>
                                    @if($item->cost_price > 0)
                                        <small class="text-muted" style="font-size: 11px;">Cost: {!! $site_settings->currency_symbol !!}{{ number_format($item->cost_price, 2) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-weight-normal" style="font-size: 11.5px;">
                                        {{ $item->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border font-monospace" style="font-size: 11px;">
                                        {{ $item->sku ?: '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border" style="font-size: 11px;">{{ $item->unit }}</span>
                                </td>
                                <td>
                                    @if($item->quantity <= $item->alert_quantity)
                                        <span class="badge bg-danger text-white fw-bold" style="font-size: 11px;">
                                            <i class="fas fa-exclamation-triangle me-1"></i> {{ $item->quantity }} {{ $item->unit }}
                                        </span>
                                    @else
                                        <span class="badge bg-success text-white fw-bold" style="font-size: 11px;">
                                            {{ $item->quantity }} {{ $item->unit }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted" style="font-size: 12px;">{{ $item->alert_quantity }} {{ $item->unit }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button class="btn btn-sm btn-outline-secondary edit-btn" 
                                            data-id="{{ $item->id }}" 
                                            data-name="{{ $item->name }}" 
                                            data-category_id="{{ $item->stock_category_id }}" 
                                            data-sku="{{ $item->sku }}" 
                                            data-unit="{{ $item->unit }}" 
                                            data-alert_quantity="{{ $item->alert_quantity }}" 
                                            data-cost_price="{{ $item->cost_price }}" 
                                            data-description="{{ $item->description }}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            title="Edit Item">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-outline-danger delete-btn" 
                                            data-id="{{ $item->id }}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal"
                                            title="Delete Item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No stock items available.</td>
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
            <form method="POST" action="{{ route('admin.stock-items.store') }}" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Add New Stock Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Item Name *</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Cooking Oil" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Category</label>
                                <select name="stock_category_id" class="form-select" style="font-size: 13px;">
                                    <option value="">Select Category...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">SKU / Code</label>
                                <input type="text" name="sku" class="form-control" placeholder="e.g. OIL-001" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Unit Type *</label>
                                <input type="text" name="unit" class="form-control" value="pcs" required placeholder="e.g. pcs, kg, L" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Initial Quantity</label>
                                <input type="number" step="0.01" name="quantity" class="form-control" value="0" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Alert Threshold Qty</label>
                                <input type="number" step="0.01" name="alert_quantity" class="form-control" value="5" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Cost Price ({!! $site_settings->currency_symbol !!})</label>
                                <input type="number" step="0.01" name="cost_price" class="form-control" value="0" style="font-size: 13px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Create Item</button>
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
                        <h5 class="modal-title font-weight-bold">Edit Stock Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Item Name *</label>
                                <input type="text" name="name" id="editName" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Category</label>
                                <select name="stock_category_id" id="editCategory" class="form-select" style="font-size: 13px;">
                                    <option value="">Select Category...</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">SKU / Code</label>
                                <input type="text" name="sku" id="editSku" class="form-control" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Unit Type *</label>
                                <input type="text" name="unit" id="editUnit" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Alert Threshold Qty</label>
                                <input type="number" step="0.01" name="alert_quantity" id="editAlertQuantity" class="form-control" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Cost Price ({!! $site_settings->currency_symbol !!})</label>
                                <input type="number" step="0.01" name="cost_price" id="editCostPrice" class="form-control" style="font-size: 13px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Item</button>
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
                        Are you sure you want to delete this stock item?
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
