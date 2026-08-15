@extends('layouts.admin')

@section('title', 'Stock Categories — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .stk-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .stk-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .stk-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .stk-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-stk {
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
    .btn-add-stk:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .stk-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .stk-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .stk-card-title {
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
        $('#stock-categories-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search categories..."
            }
        });

        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            $('#editName').val($(this).data('name'));
            $('#editDescription').val($(this).data('description'));

            let actionUrl = "{{ route('admin.stock-categories.update', ':id') }}".replace(':id', id);
            $('#editForm').attr('action', actionUrl);
        });

        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            let actionUrl = "{{ route('admin.stock-categories.destroy', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', actionUrl);
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper stk-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="stk-header">
        <div class="stk-title-group">
            <h1>Stock Categories</h1>
            <p>Organize raw materials, ingredients, bar inventory, and supplies into categories.</p>
        </div>
        <button class="btn-add-stk" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Add New Category
        </button>
    </div>

    {{-- Categories Card --}}
    <div class="stk-card">
        <div class="stk-card-header">
            <h3 class="stk-card-title">All Stock Categories ({{ $categories->count() }})</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="stock-categories-table">
                    <thead>
                        <tr>
                            <th>Category Name</th>
                            <th>Description</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border font-weight-semibold" style="font-size: 12px;">
                                            <i class="fas fa-folder text-muted me-1"></i> {{ $category->name }}
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if($category->description)
                                        <span class="text-secondary" style="font-size: 12.5px;">{{ $category->description }}</span>
                                    @else
                                        <span class="text-muted small">No description provided</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button class="btn btn-sm btn-outline-secondary edit-btn" 
                                            data-id="{{ $category->id }}" 
                                            data-name="{{ $category->name }}" 
                                            data-description="{{ $category->description }}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            title="Edit Category">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-sm btn-outline-danger delete-btn" 
                                            data-id="{{ $category->id }}" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal"
                                            title="Delete Category">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No stock categories available.</td>
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
            <form method="POST" action="{{ route('admin.stock-categories.store') }}" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-2">
                            <label class="fw-semibold mb-1" style="font-size: 12px;">Category Name *</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Dairy & Eggs" style="font-size: 13px;">
                        </div>
                        <div class="mb-2">
                            <label class="fw-semibold mb-1" style="font-size: 12px;">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Optional category description..." style="font-size: 13px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Create Category</button>
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
                        <h5 class="modal-title font-weight-bold">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-2">
                            <label class="fw-semibold mb-1" style="font-size: 12px;">Category Name *</label>
                            <input type="text" name="name" id="editName" class="form-control" required style="font-size: 13px;">
                        </div>
                        <div class="mb-2">
                            <label class="fw-semibold mb-1" style="font-size: 12px;">Description</label>
                            <textarea name="description" id="editDescription" class="form-control" rows="3" style="font-size: 13px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Category</button>
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
                        Are you sure you want to delete this stock category?
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
