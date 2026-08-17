@extends('layouts.admin')

@section('title', 'Food & Drink Menus — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .mnu-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .mnu-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .mnu-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .mnu-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-mnu {
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
    .btn-add-mnu:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .mnu-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .mnu-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .mnu-card-title {
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

    .menu-thumb {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        cursor: pointer;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#menus-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search food & drink menus..."
            }
        });

        // Lightbox trigger
        $(document).on('click', '.trigger-lightbox', function() {
            var imageUrl = $(this).data('image');
            $('#modalImage').attr('src', imageUrl);
        });

        // Edit Modal Populate
        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');
            let description = $(this).data('description');
            let price = $(this).data('price');
            let category_id = $(this).data('category_id');
            let type = $(this).data('type');
            let stock_item_id = $(this).data('stock_item_id');
            let stock_quantity = $(this).data('stock_quantity');
            let image_url = $(this).data('image');
            let has_image = $(this).data('has_image');
            
            let actionUrl = "{{ route('admin.menus.update', ':id') }}".replace(':id', id);

            $('#editName').val(name);
            $('#editDescription').val(description);
            $('#editPrice').val(price);
            $('#editCategory').val(category_id);
            $('#editType').val(type || 'kitchen');
            $('#editStockItem').val(stock_item_id);
            $('#editStockQuantity').val(stock_quantity || 1);
            $('#editForm').attr('action', actionUrl);

            $('#editRemoveImage').prop('checked', false);
            if (has_image == 1 || has_image == '1') {
                $('#editImagePreview').attr('src', image_url);
                $('#currentImageWrapper').removeClass('d-none');
            } else {
                $('#currentImageWrapper').addClass('d-none');
            }
        });

        // Delete Modal Populate
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            let actionUrl = "{{ route('admin.menus.destroy', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', actionUrl);
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper mnu-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="mnu-header">
        <div class="mnu-title-group">
            <h1>Restaurant Food & Drink Menus</h1>
            <p>Manage dining menu items, category groupings, POS prices, and linked stock ingredients.</p>
        </div>
        <button class="btn-add-mnu" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fas fa-plus me-1"></i> Add Menu Item
        </button>
    </div>

    {{-- Menus Card --}}
    <div class="mnu-card">
        <div class="mnu-card-header">
            <h3 class="mnu-card-title">All Menu Items ({{ $categories->sum(fn($c) => $c->menus->count()) }})</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="menus-table">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Category</th>
                            <th>Station</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Stock Link</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            @foreach ($category->menus as $menu)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($menu->has_real_image)
                                                <img src="{{ $menu->image_url }}" 
                                                     alt="{{ $menu->name }}" 
                                                     class="menu-thumb trigger-lightbox" 
                                                     data-bs-toggle="modal" 
                                                     data-bs-target="#imageModal" 
                                                     data-image="{{ $menu->image_url }}"
                                                     title="Click to zoom">
                                            @else
                                                <div class="rounded bg-light text-muted border d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; font-size: 11px;">
                                                    <i class="fas fa-utensils"></i>
                                                </div>
                                            @endif
                                            <div class="fw-bold text-dark">{{ $menu->name }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-weight-normal" style="font-size: 11.5px;">
                                            {{ $category->name }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($menu->type === 'bar')
                                            <span class="badge bg-success text-white" style="font-size: 11px;"><i class="fas fa-wine-glass-alt me-1"></i> Bar (BOT)</span>
                                        @else
                                            <span class="badge bg-danger text-white" style="font-size: 11px;"><i class="fas fa-utensils me-1"></i> Kitchen (KOT)</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($menu->description)
                                            <span class="text-secondary" style="font-size: 12.5px;">{{ Str::limit($menu->description, 55) }}</span>
                                        @else
                                            <span class="text-muted small">No description</span>
                                        @endif
                                    </td>
                                    <td>
                                        <strong class="text-dark">{!! $site_settings->currency_symbol !!}{{ number_format($menu->price, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if($menu->recipes->first() && $menu->recipes->first()->stockItem)
                                            <span class="badge bg-light text-success border" style="font-size: 11px;" title="Deducts {{ $menu->recipes->first()->quantity }} {{ $menu->recipes->first()->stockItem->unit }} per sale">
                                                <i class="fas fa-link me-1"></i> {{ $menu->recipes->first()->stockItem->name }} ({{ $menu->recipes->first()->quantity }} {{ $menu->recipes->first()->stockItem->unit }})
                                            </span>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="btn btn-sm btn-outline-secondary edit-btn"
                                                    data-id="{{ $menu->id }}"
                                                    data-name="{{ $menu->name }}"
                                                    data-description="{{ $menu->description }}"
                                                    data-price="{{ $menu->price }}"
                                                    data-category_id="{{ $menu->category_id }}"
                                                    data-type="{{ $menu->type ?? 'kitchen' }}"
                                                    data-stock_item_id="{{ $menu->recipes->first()->stock_item_id ?? '' }}"
                                                    data-stock_quantity="{{ $menu->recipes->first()->quantity ?? 1 }}"
                                                    data-image="{{ $menu->image_url ?? '' }}"
                                                    data-has_image="{{ $menu->has_real_image ? '1' : '0' }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal"
                                                    title="Edit Item">
                                                    <i class="fas fa-edit"></i>
                                            </button>

                                            <button class="btn btn-sm btn-outline-danger delete-btn"
                                                    data-id="{{ $menu->id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    title="Delete Item">
                                                    <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No menu items available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Lightbox Modal --}}
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Menu Photo Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-3">
                    <img id="modalImage" src="" alt="Menu Photo" class="img-fluid rounded border" style="max-height: 380px; object-fit: contain;">
                </div>
                <div class="modal-footer border-0 pt-0 pb-3">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Add Menu Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Item Name *</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Grilled Chicken Wings" style="font-size: 13px;">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Category *</label>
                                <select name="category_id" class="form-select" required style="font-size: 13px;">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Station Ticket *</label>
                                <select name="type" class="form-select" required style="font-size: 13px;">
                                    <option value="kitchen">Kitchen Ticket (KOT)</option>
                                    <option value="bar">Bar Ticket (BOT)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Price ({!! $site_settings->currency_symbol !!}) *</label>
                                <input type="number" step="0.01" name="price" class="form-control" required placeholder="0.00" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Description (Optional)</label>
                                <textarea name="description" class="form-control" rows="2" placeholder="Describe ingredients, flavor, size..." style="font-size: 13px;"></textarea>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Food / Beverage Photo</label>
                                <input type="file" name="image" class="form-control" accept="image/*" style="font-size: 13px;">
                                <small class="text-muted" style="font-size: 11px;">Recommended size: 500 x 400px.</small>
                            </div>
                            <div class="col-md-7 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Linked Stock Item (Optional — For Drinks/Stock)</label>
                                <select name="stock_item_id" class="form-select" style="font-size: 13px;">
                                    <option value="">None</option>
                                    @if(isset($stockItems))
                                        @foreach ($stockItems as $stockItem)
                                            <option value="{{ $stockItem->id }}">{{ $stockItem->name }} (In Stock: {{ $stockItem->quantity }} {{ $stockItem->unit }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-5 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Stock Deducted per Sale</label>
                                <input type="number" step="0.0001" name="stock_quantity" value="1" class="form-control" placeholder="e.g. 1 or 0.04" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted" style="font-size: 11px;">
                                    <i class="fas fa-info-circle text-primary me-1"></i>
                                    <strong>Bottles vs Shots/Glasses:</strong> Set <code>1</code> for Full Bottle. For single shots or glass of wine, set portion (e.g. <code>0.04</code> for a 25-shot bottle shot, or <code>0.2</code> for a glass of wine).
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Create Menu Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="editForm" method="POST" enctype="multipart/form-data" style="width: 100%;">
                @csrf
                @method('PATCH')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Edit Menu Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Item Name *</label>
                                <input type="text" name="name" id="editName" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Category *</label>
                                <select name="category_id" id="editCategory" class="form-select" required style="font-size: 13px;">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Station Ticket *</label>
                                <select name="type" id="editType" class="form-select" required style="font-size: 13px;">
                                    <option value="kitchen">Kitchen Ticket (KOT)</option>
                                    <option value="bar">Bar Ticket (BOT)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Price ({!! $site_settings->currency_symbol !!}) *</label>
                                <input type="number" step="0.01" name="price" id="editPrice" class="form-control" required style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Description (Optional)</label>
                                <textarea name="description" id="editDescription" class="form-control" rows="2" style="font-size: 13px;"></textarea>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Food / Beverage Photo</label>
                                <div id="currentImageWrapper" class="mb-2 d-none p-2 border rounded bg-light">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img id="editImagePreview" src="" alt="Current Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 6px;" class="border me-2">
                                            <span class="small text-muted fw-semibold">Current Picture</span>
                                        </div>
                                        <div class="form-check text-danger mb-0">
                                            <input class="form-check-input" type="checkbox" name="remove_image" id="editRemoveImage" value="1">
                                            <label class="form-check-label text-danger font-weight-bold small" for="editRemoveImage" style="cursor:pointer;">
                                                <i class="fas fa-trash me-1"></i> Remove Picture
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <input type="file" name="image" id="editImage" class="form-control" accept="image/*" style="font-size: 13px;">
                            </div>
                            <div class="col-md-7 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Linked Stock Item (Optional — For Drinks/Stock)</label>
                                <select name="stock_item_id" id="editStockItem" class="form-select" style="font-size: 13px;">
                                    <option value="">None</option>
                                    @if(isset($stockItems))
                                        @foreach ($stockItems as $stockItem)
                                            <option value="{{ $stockItem->id }}">{{ $stockItem->name }} (In Stock: {{ $stockItem->quantity }} {{ $stockItem->unit }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-5 mb-2">
                                <label class="fw-semibold mb-1" style="font-size: 12px;">Stock Deducted per Sale</label>
                                <input type="number" step="0.0001" name="stock_quantity" id="editStockQuantity" class="form-control" placeholder="e.g. 1 or 0.04" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <small class="text-muted" style="font-size: 11px;">
                                    <i class="fas fa-info-circle text-primary me-1"></i>
                                    <strong>Bottles vs Shots/Glasses:</strong> Set <code>1</code> for Full Bottle. For single shots or glass of wine, set portion (e.g. <code>0.04</code> for a 25-shot bottle shot, or <code>0.2</code> for a glass of wine).
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Menu Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form id="deleteForm" method="POST" style="width: 100%;">
                @csrf
                @method('DELETE')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4" style="font-size: 13.5px; color: #4b5563;">
                        Are you sure you want to delete this menu item?
                    </div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete Item</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection