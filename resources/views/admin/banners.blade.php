@extends('layouts.admin')

@section('title', 'Homepage Hero Banners — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .bnr-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .bnr-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .bnr-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .bnr-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .btn-add-bnr {
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
    .btn-add-bnr:hover {
        background: #b91c1c;
    }

    /* Card & Table */
    .bnr-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
    }
    .bnr-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .bnr-card-title {
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

    .banner-thumb {
        width: 90px;
        height: 52px;
        border-radius: 6px;
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
        $('#banners-table').DataTable({
            paging: true,
            searching: true,
            lengthChange: false,
            pageLength: 15,
            order: [],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search hero banners..."
            }
        });

        // Edit Button Populate Logic
        $(document).on('click', '.edit-btn', function() {
            let id = $(this).data('id');
            let title = $(this).data('title');
            let subtitle = $(this).data('subtitle');
            let description = $(this).data('description');
            let btnText1 = $(this).data('btn-text-1');
            let btnLink1 = $(this).data('btn-link-1');
            let btnText2 = $(this).data('btn-text-2');
            let btnLink2 = $(this).data('btn-link-2');
            let overlayClass = $(this).data('overlay-class');
            let align = $(this).data('align');
            let sortOrder = $(this).data('sort-order');
            let isActive = $(this).data('is-active');
            let imagePreview = $(this).data('image-preview');

            $('#editTitle').val(title);
            $('#editSubtitle').val(subtitle);
            $('#editDescription').val(description);
            $('#editBtnText1').val(btnText1);
            $('#editBtnLink1').val(btnLink1);
            $('#editBtnText2').val(btnText2);
            $('#editBtnLink2').val(btnLink2);
            $('#editOverlayClass').val(overlayClass);
            $('#editAlign').val(align);
            $('#editSortOrder').val(sortOrder);
            $('#editIsActive').prop('checked', isActive == 1);
            $('#currentImagePreview').attr('src', imagePreview);

            let actionUrl = "{{ route('admin.banners.update', ':id') }}".replace(':id', id);
            $('#editForm').attr('action', actionUrl);
        });

        // Delete Button Logic
        $(document).on('click', '.delete-btn', function() {
            let id = $(this).data('id');
            let title = $(this).data('title');

            $('#deleteTitle').text(title);
            let actionUrl = "{{ route('admin.banners.destroy', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', actionUrl);
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper bnr-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="bnr-header">
        <div class="bnr-title-group">
            <h1>Homepage Hero Banners</h1>
            <p>Manage main site slider banners, background images, titles, and call-to-action buttons.</p>
        </div>
        <button type="button" class="btn-add-bnr" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Add New Banner
        </button>
    </div>

    {{-- Banners Card --}}
    <div class="bnr-card">
        <div class="bnr-card-header">
            <h3 class="bnr-card-title">All Banners ({{ $banners->count() }})</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table" id="banners-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Order</th>
                            <th>Background</th>
                            <th>Headline & Subtitle</th>
                            <th>Buttons / Links</th>
                            <th>Status</th>
                            <th style="min-width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banners as $banner)
                            <tr>
                                <td class="fw-bold text-center">
                                    <span class="badge bg-light text-dark border">{{ $banner->sort_order }}</span>
                                </td>
                                <td>
                                    <img src="{{ $banner->image_url }}" 
                                         alt="{{ $banner->title }}" 
                                         class="banner-thumb"
                                         onerror="this.onerror=null;this.src='/assets/images/placeholder.jpg';">
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $banner->title }}</div>
                                    @if($banner->subtitle)
                                        <span class="badge bg-light text-primary border me-1" style="font-size: 11px;">{{ $banner->subtitle }}</span>
                                    @endif
                                    @if($banner->description)
                                        <div class="text-muted small">{{ Str::limit($banner->description, 55) }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($banner->btn_text_1)
                                        <div class="small"><strong>Btn 1:</strong> {{ $banner->btn_text_1 }}</div>
                                    @endif
                                    @if($banner->btn_text_2)
                                        <div class="small"><strong>Btn 2:</strong> {{ $banner->btn_text_2 }}</div>
                                    @endif
                                    @if(!$banner->btn_text_1 && !$banner->btn_text_2)
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.banners.toggle-status', $banner->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-secondary' }} fw-semibold" style="border-radius: 12px; padding: 2px 10px; font-size: 11px;">
                                            {{ $banner->is_active ? 'Active' : 'Disabled' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1">
                                        <button type="button" class="btn btn-sm btn-outline-secondary edit-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editModal"
                                            data-id="{{ $banner->id }}"
                                            data-title="{{ $banner->title }}"
                                            data-subtitle="{{ $banner->subtitle }}"
                                            data-description="{{ $banner->description }}"
                                            data-btn-text-1="{{ $banner->btn_text_1 }}"
                                            data-btn-link-1="{{ $banner->btn_link_1 }}"
                                            data-btn-text-2="{{ $banner->btn_text_2 }}"
                                            data-btn-link-2="{{ $banner->btn_link_2 }}"
                                            data-overlay-class="{{ $banner->overlay_class }}"
                                            data-align="{{ $banner->align }}"
                                            data-sort-order="{{ $banner->sort_order }}"
                                            data-is-active="{{ $banner->is_active ? 1 : 0 }}"
                                            data-image-preview="{{ $banner->image_url }}"
                                            title="Edit Banner">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn"
                                            data-id="{{ $banner->id }}"
                                            data-title="{{ $banner->title }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            title="Delete Banner">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No hero banners found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" style="width: 100%;">
                @csrf
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Create New Hero Banner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-md-8 mb-2">
                                <label for="title" class="fw-semibold mb-1" style="font-size: 12px;">Headline / Title *</label>
                                <input type="text" class="form-control" id="title" name="title" required placeholder="e.g. Weddings & Event Garden" style="font-size: 13px;">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="subtitle" class="fw-semibold mb-1" style="font-size: 12px;">Sub-headline Badge</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="e.g. Celebrate With Us" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="description" class="fw-semibold mb-1" style="font-size: 12px;">Description Paragraph</label>
                                <textarea class="form-control" id="description" name="description" rows="2" placeholder="Brief summary displayed over the banner slide..." style="font-size: 13px;"></textarea>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="image" class="fw-semibold mb-1" style="font-size: 12px;">Background Image *</label>
                                <input type="file" class="form-control" id="image" name="image" required accept="image/*" style="font-size: 13px;">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="overlay_class" class="fw-semibold mb-1" style="font-size: 12px;">Dark Overlay</label>
                                <select class="form-select" id="overlay_class" name="overlay_class" style="font-size: 13px;">
                                    <option value="overlay_bg_40">40% Dark</option>
                                    <option value="overlay_bg_50" selected>50% Dark</option>
                                    <option value="overlay_bg_60">60% Dark</option>
                                    <option value="overlay_bg_70">70% Dark</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="align" class="fw-semibold mb-1" style="font-size: 12px;">Alignment</label>
                                <select class="form-select" id="align" name="align" style="font-size: 13px;">
                                    <option value="left">Left Aligned</option>
                                    <option value="center" selected>Centered</option>
                                    <option value="right">Right Aligned</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="btn_text_1" class="fw-semibold mb-1" style="font-size: 12px;">Button 1 Label</label>
                                <input type="text" class="form-control" id="btn_text_1" name="btn_text_1" placeholder="e.g. Explore Venues" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="btn_link_1" class="fw-semibold mb-1" style="font-size: 12px;">Button 1 URL / Link</label>
                                <input type="text" class="form-control" id="btn_link_1" name="btn_link_1" placeholder="e.g. /venues" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="btn_text_2" class="fw-semibold mb-1" style="font-size: 12px;">Button 2 Label</label>
                                <input type="text" class="form-control" id="btn_text_2" name="btn_text_2" placeholder="e.g. Book Table" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="btn_link_2" class="fw-semibold mb-1" style="font-size: 12px;">Button 2 URL / Link</label>
                                <input type="text" class="form-control" id="btn_link_2" name="btn_link_2" placeholder="e.g. /#book-table" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="sort_order" class="fw-semibold mb-1" style="font-size: 12px;">Display Sort Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="1" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2 d-flex align-items-center pt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked value="1">
                                    <label class="form-check-label fw-semibold ms-1" for="is_active" style="font-size: 12.5px;">Publish & Show on Homepage</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Create Banner</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form action="" method="POST" id="editForm" enctype="multipart/form-data" style="width: 100%;">
                @csrf
                @method('PUT')
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Edit Hero Banner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="row g-2">
                            <div class="col-md-8 mb-2">
                                <label for="editTitle" class="fw-semibold mb-1" style="font-size: 12px;">Headline / Title *</label>
                                <input type="text" class="form-control" id="editTitle" name="title" required style="font-size: 13px;">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="editSubtitle" class="fw-semibold mb-1" style="font-size: 12px;">Sub-headline Badge</label>
                                <input type="text" class="form-control" id="editSubtitle" name="subtitle" style="font-size: 13px;">
                            </div>
                            <div class="col-12 mb-2">
                                <label for="editDescription" class="fw-semibold mb-1" style="font-size: 12px;">Description Paragraph</label>
                                <textarea class="form-control" id="editDescription" name="description" rows="2" style="font-size: 13px;"></textarea>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="editImage" class="fw-semibold mb-1" style="font-size: 12px;">Replace Background Image (Optional)</label>
                                <input type="file" class="form-control mb-2" id="editImage" name="image" accept="image/*" style="font-size: 13px;">
                                <div class="small text-muted mb-1">Current Background:</div>
                                <img src="" id="currentImagePreview" alt="Current Image" style="height: 50px; object-fit: cover; border-radius: 4px;" class="border">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="editOverlayClass" class="fw-semibold mb-1" style="font-size: 12px;">Dark Overlay</label>
                                <select class="form-select" id="editOverlayClass" name="overlay_class" style="font-size: 13px;">
                                    <option value="overlay_bg_40">40% Dark</option>
                                    <option value="overlay_bg_50">50% Dark</option>
                                    <option value="overlay_bg_60">60% Dark</option>
                                    <option value="overlay_bg_70">70% Dark</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label for="editAlign" class="fw-semibold mb-1" style="font-size: 12px;">Alignment</label>
                                <select class="form-select" id="editAlign" name="align" style="font-size: 13px;">
                                    <option value="left">Left Aligned</option>
                                    <option value="center">Centered</option>
                                    <option value="right">Right Aligned</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="editBtnText1" class="fw-semibold mb-1" style="font-size: 12px;">Button 1 Label</label>
                                <input type="text" class="form-control" id="editBtnText1" name="btn_text_1" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="editBtnLink1" class="fw-semibold mb-1" style="font-size: 12px;">Button 1 URL / Link</label>
                                <input type="text" class="form-control" id="editBtnLink1" name="btn_link_1" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="editBtnText2" class="fw-semibold mb-1" style="font-size: 12px;">Button 2 Label</label>
                                <input type="text" class="form-control" id="editBtnText2" name="btn_text_2" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="editBtnLink2" class="fw-semibold mb-1" style="font-size: 12px;">Button 2 URL / Link</label>
                                <input type="text" class="form-control" id="editBtnLink2" name="btn_link_2" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="editSortOrder" class="fw-semibold mb-1" style="font-size: 12px;">Display Sort Order</label>
                                <input type="number" class="form-control" id="editSortOrder" name="sort_order" style="font-size: 13px;">
                            </div>
                            <div class="col-md-6 mb-2 d-flex align-items-center pt-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active" value="1">
                                    <label class="form-check-label fw-semibold ms-1" for="editIsActive" style="font-size: 12.5px;">Publish & Show on Homepage</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Update Banner</button>
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
                        Are you sure you want to delete the banner "<strong id="deleteTitle"></strong>"?
                    </div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete Banner</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
