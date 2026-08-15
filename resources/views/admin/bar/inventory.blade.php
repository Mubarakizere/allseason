@extends('layouts.admin')

@section('title', 'Bar Drink Stock & Inventory — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .bar-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .bar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .bar-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .bar-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-drink {
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
    .btn-add-drink:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .bar-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .bar-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .bar-card-title {
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
        $('#drinks-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search bar drinks..."
            }
        });

        document.querySelectorAll('.edit-drink-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var actionUrl = "{{ route('admin.bar.inventory.update', ':id') }}".replace(':id', id);
                document.getElementById('editDrinkForm').setAttribute('action', actionUrl);

                document.getElementById('editName').value = this.getAttribute('data-name');
                document.getElementById('editUnit').value = this.getAttribute('data-unit');
                document.getElementById('editQuantity').value = this.getAttribute('data-quantity');
                document.getElementById('editAlertQuantity').value = this.getAttribute('data-alert_quantity');
                document.getElementById('editCostPrice').value = this.getAttribute('data-cost_price');
                document.getElementById('editDescription').value = this.getAttribute('data-description');
            });
        });

        document.querySelectorAll('.delete-drink-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var actionUrl = "{{ route('admin.bar.inventory.destroy', ':id') }}".replace(':id', id);
                document.getElementById('deleteDrinkForm').setAttribute('action', actionUrl);
            });
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper bar-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="bar-header">
        <div class="bar-title-group">
            <h1>Bar Drink Stock & Inventory</h1>
            <p>Manage bar beverages, wines, beers, spirits, cocktails, and shot pour quantities.</p>
        </div>
        <button class="btn-add-drink" data-bs-toggle="modal" data-bs-target="#addDrinkModal">
            <i class="fas fa-plus me-1"></i> Add Drink Stock
        </button>
    </div>

    {{-- Inventory Card --}}
    <div class="bar-card">
        <div class="bar-card-header">
            <h3 class="bar-card-title">Drink Inventory List ({{ $drinks->count() }})</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="drinks-table">
                    <thead>
                        <tr>
                            <th>Drink / Brand</th>
                            <th>SKU</th>
                            <th>Current Stock</th>
                            <th>Unit</th>
                            <th>Alert Threshold</th>
                            <th>Status</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($drinks as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $item->name }}</div>
                                    @if($item->description)
                                        <small class="text-muted" style="font-size: 11px;">{{ $item->description }}</small>
                                    @endif
                                </td>
                                <td><span class="badge bg-light text-dark border" style="font-size: 11px;">{{ $item->sku }}</span></td>
                                <td>
                                    <span class="fw-bold {{ $item->quantity <= $item->alert_quantity ? 'text-danger' : 'text-dark' }}">
                                        {{ number_format($item->quantity, 2) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border" style="font-size: 11px;">{{ $item->unit }}</span>
                                </td>
                                <td>{{ number_format($item->alert_quantity, 2) }} {{ $item->unit }}</td>
                                <td>
                                    @if($item->quantity <= 0)
                                        <span class="badge bg-danger text-white fw-semibold" style="font-size: 11px;">Out of Stock</span>
                                    @elseif($item->quantity <= $item->alert_quantity)
                                        <span class="badge bg-warning text-dark fw-semibold" style="font-size: 11px;">Low Bottle Warning</span>
                                    @else
                                        <span class="badge bg-success text-white fw-semibold" style="font-size: 11px;">In Stock</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button class="btn btn-sm btn-outline-secondary edit-drink-btn"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->name }}"
                                            data-unit="{{ $item->unit }}"
                                            data-quantity="{{ $item->quantity }}"
                                            data-alert_quantity="{{ $item->alert_quantity }}"
                                            data-cost_price="{{ $item->cost_price }}"
                                            data-description="{{ $item->description }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editDrinkModal"
                                            title="Edit Drink">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-drink-btn"
                                            data-id="{{ $item->id }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteDrinkModal"
                                            title="Delete Drink">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No bar drink stock added yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add Drink Modal --}}
    <div class="modal fade" id="addDrinkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.bar.inventory.store') }}" method="POST" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Add Bar Drink Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Drink Name / Brand *</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Red Wine, Heineken, Jameson Whiskey" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Unit of Measure (UOM) *</label>
                                <select name="unit" class="form-select" required style="font-size: 13px;">
                                    @foreach ($units as $code => $label)
                                        <option value="{{ $code }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Quantity in Stock *</label>
                                <input type="number" step="0.01" name="quantity" class="form-control" required placeholder="0.00" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Low Stock Alert Threshold *</label>
                                <input type="number" step="0.01" name="alert_quantity" class="form-control" required value="5.00" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Estimated Cost Price per Unit (Optional)</label>
                                <input type="number" step="0.01" name="cost_price" class="form-control" placeholder="0.00" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Description / Notes (Optional)</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="e.g. Premium imported red wine bottle 750ml" style="font-size: 13px;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Drink Stock</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Drink Modal --}}
    <div class="modal fade" id="editDrinkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form id="editDrinkForm" method="POST" style="width: 100%;">
                @csrf
                @method('PUT')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Edit Bar Drink Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Drink Name / Brand *</label>
                                <input type="text" name="name" id="editName" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Unit of Measure (UOM) *</label>
                                <select name="unit" id="editUnit" class="form-select" required style="font-size: 13px;">
                                    @foreach ($units as $code => $label)
                                        <option value="{{ $code }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Quantity in Stock *</label>
                                <input type="number" step="0.01" name="quantity" id="editQuantity" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Low Stock Alert Threshold *</label>
                                <input type="number" step="0.01" name="alert_quantity" id="editAlertQuantity" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Cost Price per Unit</label>
                                <input type="number" step="0.01" name="cost_price" id="editCostPrice" class="form-control" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Description / Notes</label>
                                <textarea name="description" id="editDescription" class="form-control" rows="2" style="font-size: 13px;"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Drink Stock</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteDrinkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="deleteDrinkForm" method="POST" style="width: 100%;">
                @csrf
                @method('DELETE')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4" style="font-size: 13.5px; color: #4b5563;">
                        Are you sure you want to delete this bar drink stock item?
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
