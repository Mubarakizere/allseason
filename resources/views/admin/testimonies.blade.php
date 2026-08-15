@extends('layouts.admin')

@section('title', 'Customer Testimonials — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .tst-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .tst-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .tst-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .tst-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-tst {
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
    .btn-add-tst:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .tst-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .tst-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .tst-card-title {
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
        $('#testimonies-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search testimonials..."
            }
        });

        $(document).on('click', '.edit-btn', function() {
            let testimonyId = $(this).data('id');
            let name = $(this).data('name');
            let content = $(this).data('content');

            $('#editName').val(name);
            $('#editContent').val(content);

            let actionUrl = "{{ route('admin.testimonies.update', ':id') }}".replace(':id', testimonyId);
            $('#editForm').attr('action', actionUrl);
        });

        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            let name = $(this).data('name');

            $('#deleteName').text(name);
            let actionUrl = "{{ route('admin.testimonies.destroy', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', actionUrl);
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper tst-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="tst-header">
        <div class="tst-title-group">
            <h1>Customer Testimonials & Reviews</h1>
            <p>Manage customer reviews and feedback displayed on the main website homepage.</p>
        </div>
        <button type="button" class="btn-add-tst" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Add Testimonial
        </button>
    </div>

    {{-- Testimonials Card --}}
    <div class="tst-card">
        <div class="tst-card-header">
            <h3 class="tst-card-title">All Customer Reviews ({{ $testimonies->count() }})</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="testimonies-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Review Quote</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($testimonies as $testimony)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center border" style="width: 34px; height: 34px; font-size: 13px; font-weight: 600;">
                                            {{ strtoupper(substr($testimony->name, 0, 1)) }}
                                        </div>
                                        <div class="fw-bold text-dark">{{ $testimony->name }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-secondary" style="font-size: 12.5px; font-style: italic;">
                                        <i class="fas fa-quote-left text-muted me-1" style="font-size: 10px;"></i>
                                        {{ Str::limit($testimony->content, 90) }}
                                        <i class="fas fa-quote-right text-muted ms-1" style="font-size: 10px;"></i>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary edit-btn" 
                                                data-id="{{ $testimony->id }}"
                                                data-name="{{ $testimony->name }}"
                                                data-content="{{ $testimony->content }}"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal"
                                                title="Edit Review">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn" 
                                                data-id="{{ $testimony->id }}" 
                                                data-name="{{ $testimony->name }}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal"
                                                title="Delete Review">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No customer testimonials found.</td>
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
            <form action="{{ route('admin.testimonies.store') }}" method="POST" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Add New Testimonial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-2">
                            <label for="name" class="fw-semibold mb-1" style="font-size: 12px;">Customer Name *</label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Sarah Jenkins" style="font-size: 13px;">
                        </div>
                        <div class="mb-2">
                            <label for="content" class="fw-semibold mb-1" style="font-size: 12px;">Review / Feedback Content *</label>
                            <textarea class="form-control" id="content" name="content" rows="4" required placeholder="Describe the customer's dining or event experience..." style="font-size: 13px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Testimonial</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="POST" id="editForm" style="width: 100%;">
                @csrf
                @method('PUT')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Edit Testimonial</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="mb-2">
                            <label for="editName" class="fw-semibold mb-1" style="font-size: 12px;">Customer Name *</label>
                            <input type="text" class="form-control" id="editName" name="name" required style="font-size: 13px;">
                        </div>
                        <div class="mb-2">
                            <label for="editContent" class="fw-semibold mb-1" style="font-size: 12px;">Review / Feedback Content *</label>
                            <textarea class="form-control" id="editContent" name="content" rows="4" required style="font-size: 13px;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Testimonial</button>
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
                        Are you sure you want to delete testimonial from <strong id="deleteName"></strong>?
                    </div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete Testimonial</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection