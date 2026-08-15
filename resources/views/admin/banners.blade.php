@extends('layouts.admin')

@push('styles')
    <!-- base:css -->
    <link rel="stylesheet" href="/admin_resources/vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="/admin_resources/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/admin_resources/css/vertical-layout-light/style.css">
@endpush

@push('scripts')
<script src="/admin_resources/vendors/js/vendor.bundle.base.js"></script>
<script src="/admin_resources/js/off-canvas.js"></script>
<script src="/admin_resources/js/hoverable-collapse.js"></script>
<script src="/admin_resources/js/template.js"></script>
<script src="/admin_resources/js/settings.js"></script>
<script src="/admin_resources/js/todolist.js"></script>
<script src="/admin_resources/vendors/progressbar.js/progressbar.min.js"></script>
<script src="/admin_resources/vendors/chart.js/Chart.min.js"></script>
<script src="/admin_resources/js/dashboard.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Edit Button Populate Logic
        $('.edit-btn').on('click', function() {
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
        $('.delete-btn').on('click', function() {
            let id = $(this).data('id');
            let title = $(this).data('title');

            $('#deleteTitle').text(title);
            let actionUrl = "{{ route('admin.banners.destroy', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', actionUrl);
        });
    });
</script>
@endpush

@section('title', 'Admin - Manage Homepage Banners')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">

      @include('partials.message-bag')

      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><strong>Homepage Hero Banners</strong> ({{ $banners->count() }})</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa fa-plus me-1"></i> Add New Banner
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 50px;">Order</th>
                            <th style="width: 120px;">Background</th>
                            <th>Headline & Subtitle</th>
                            <th>Buttons / Links</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banners as $banner)
                            <tr>
                                <td class="fw-bold text-center">{{ $banner->sort_order }}</td>
                                <td>
                                    <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" style="width: 100px; height: 60px; object-fit: cover; border-radius: 6px;" class="shadow-sm">
                                </td>
                                <td>
                                    <h6 class="mb-0 font-weight-bold text-dark">{{ $banner->title }}</h6>
                                    @if($banner->subtitle)
                                        <span class="badge bg-light text-primary border me-1">{{ $banner->subtitle }}</span>
                                    @endif
                                    <p class="text-muted small mb-0">{{ Str::limit($banner->description, 60) }}</p>
                                </td>
                                <td>
                                    @if($banner->btn_text_1)
                                        <div class="small"><strong>Btn 1:</strong> {{ $banner->btn_text_1 }}</div>
                                    @endif
                                    @if($banner->btn_text_2)
                                        <div class="small"><strong>Btn 2:</strong> {{ $banner->btn_text_2 }}</div>
                                    @endif
                                    @if(!$banner->btn_text_1 && !$banner->btn_text_2)
                                        <span class="text-muted small">None</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.banners.toggle-status', $banner->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $banner->is_active ? 'btn-success' : 'btn-secondary' }}" style="border-radius: 12px; padding: 2px 10px; font-size: 11px;">
                                            {{ $banner->is_active ? 'Active' : 'Disabled' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <button class="btn btn-warning btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editModal"
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
                                        data-image-preview="{{ $banner->image_url }}">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    <button class="btn btn-danger btn-sm delete-btn"
                                        data-id="{{ $banner->id }}"
                                        data-title="{{ $banner->title }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteModal">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <div class="alert alert-warning mb-0" role="alert">
                                        No hero banners found. Create your first banner to display on the homepage.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="createModalLabel">Create New Hero Banner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="title" class="form-label font-weight-bold">Headline / Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" required placeholder="e.g. Wedding & Events">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="subtitle" class="form-label font-weight-bold">Sub-headline Badge</label>
                                <input type="text" class="form-control" id="subtitle" name="subtitle" placeholder="e.g. Celebrate With Us">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label font-weight-bold">Description Paragraph</label>
                            <textarea class="form-control" id="description" name="description" rows="2" placeholder="Brief summary displayed over the banner slide..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="image" class="form-label font-weight-bold">Background Image <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="image" name="image" required accept="image/*">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="overlay_class" class="form-label font-weight-bold">Overlay Dark Opacity</label>
                                <select class="form-control" id="overlay_class" name="overlay_class">
                                    <option value="overlay_bg_40">40% Dark</option>
                                    <option value="overlay_bg_50" selected>50% Dark (Recommended)</option>
                                    <option value="overlay_bg_60">60% Dark</option>
                                    <option value="overlay_bg_70">70% Dark</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="align" class="form-label font-weight-bold">Text Alignment</label>
                                <select class="form-control" id="align" name="align">
                                    <option value="left">Left Aligned</option>
                                    <option value="center" selected>Centered</option>
                                    <option value="right">Right Aligned</option>
                                </select>
                            </div>
                        </div>

                        <div class="row border-top pt-3">
                            <div class="col-md-6 mb-3">
                                <label for="btn_text_1" class="form-label font-weight-bold">Button 1 Label</label>
                                <input type="text" class="form-control" id="btn_text_1" name="btn_text_1" placeholder="e.g. Explore Venues">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="btn_link_1" class="form-label font-weight-bold">Button 1 URL / Link</label>
                                <input type="text" class="form-control" id="btn_link_1" name="btn_link_1" placeholder="e.g. /venues or https://...">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="btn_text_2" class="form-label font-weight-bold">Button 2 Label</label>
                                <input type="text" class="form-control" id="btn_text_2" name="btn_text_2" placeholder="e.g. Book Event">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="btn_link_2" class="form-label font-weight-bold">Button 2 URL / Link</label>
                                <input type="text" class="form-control" id="btn_link_2" name="btn_link_2" placeholder="e.g. /contact">
                            </div>
                        </div>

                        <div class="row border-top pt-3">
                            <div class="col-md-6 mb-3">
                                <label for="sort_order" class="form-label font-weight-bold">Sort Display Order</label>
                                <input type="number" class="form-control" id="sort_order" name="sort_order" value="1">
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-center pt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked value="1">
                                    <label class="form-check-label font-weight-bold ms-2" for="is_active">Publish & Show on Homepage Carousel</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save Banner</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="" method="POST" id="editForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="editModalLabel">Edit Hero Banner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="editTitle" class="form-label font-weight-bold">Headline / Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editTitle" name="title" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="editSubtitle" class="form-label font-weight-bold">Sub-headline Badge</label>
                                <input type="text" class="form-control" id="editSubtitle" name="subtitle">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="editDescription" class="form-label font-weight-bold">Description Paragraph</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="2"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editImage" class="form-label font-weight-bold">Replace Background Image (Optional)</label>
                                <input type="file" class="form-control mb-2" id="editImage" name="image" accept="image/*">
                                <div class="small text-muted mb-1">Current Background:</div>
                                <img src="" id="currentImagePreview" alt="Current Image" style="height: 60px; object-fit: cover; border-radius: 4px;" class="border">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editOverlayClass" class="form-label font-weight-bold">Overlay Dark Opacity</label>
                                <select class="form-control" id="editOverlayClass" name="overlay_class">
                                    <option value="overlay_bg_40">40% Dark</option>
                                    <option value="overlay_bg_50">50% Dark</option>
                                    <option value="overlay_bg_60">60% Dark</option>
                                    <option value="overlay_bg_70">70% Dark</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="editAlign" class="form-label font-weight-bold">Text Alignment</label>
                                <select class="form-control" id="editAlign" name="align">
                                    <option value="left">Left Aligned</option>
                                    <option value="center">Centered</option>
                                    <option value="right">Right Aligned</option>
                                </select>
                            </div>
                        </div>

                        <div class="row border-top pt-3">
                            <div class="col-md-6 mb-3">
                                <label for="editBtnText1" class="form-label font-weight-bold">Button 1 Label</label>
                                <input type="text" class="form-control" id="editBtnText1" name="btn_text_1">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editBtnLink1" class="form-label font-weight-bold">Button 1 URL / Link</label>
                                <input type="text" class="form-control" id="editBtnLink1" name="btn_link_1">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="editBtnText2" class="form-label font-weight-bold">Button 2 Label</label>
                                <input type="text" class="form-control" id="editBtnText2" name="btn_text_2">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="editBtnLink2" class="form-label font-weight-bold">Button 2 URL / Link</label>
                                <input type="text" class="form-control" id="editBtnLink2" name="btn_link_2">
                            </div>
                        </div>

                        <div class="row border-top pt-3">
                            <div class="col-md-6 mb-3">
                                <label for="editSortOrder" class="form-label font-weight-bold">Sort Display Order</label>
                                <input type="number" class="form-control" id="editSortOrder" name="sort_order">
                            </div>
                            <div class="col-md-6 mb-3 d-flex align-items-center pt-4">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editIsActive" name="is_active" value="1">
                                    <label class="form-check-label font-weight-bold ms-2" for="editIsActive">Publish & Show on Homepage Carousel</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i> Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="deleteModalLabel">Delete Hero Banner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">Are you sure you want to delete the banner "<strong id="deleteTitle"></strong>"?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash me-1"></i> Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    </div>
    <!-- content-wrapper ends -->
    @include('partials.admin.footer')
  </div>
  <!-- main-panel ends -->
@endsection
