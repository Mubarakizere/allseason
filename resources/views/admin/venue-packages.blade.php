@extends('layouts.admin')

@push('styles')
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
    // jQuery logic removed since we are using specific modals per package
</script>
@endpush

@section('title', 'Admin - Venue Packages')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      @include('partials.message-bag')

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Venue Packages</span>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    Add New Package
                </button>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Venue</th>
                            <th>Package Name</th>
                            <th>Price</th>
                            <th>Features</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($venues as $venue)
                            @foreach ($venue->packages as $package)
                            <tr>
                                <td>{{ $venue->name }}</td>
                                <td>{{ $package->name }}</td>
                                <td>{!! $site_settings->currency_symbol !!}{{ number_format($package->price, 2) }}</td>
                                <td>
                                    <ul class="mb-0 pl-3" style="list-style-type: disc;">
                                    @foreach($package->features as $feature)
                                        <li>{{ $feature->name }}</li>
                                    @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <button class="m-2 btn btn-success btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editModal{{ $package->id }}"><i class="fa fa-edit"></i></button>
                                    <button class="m-2 btn btn-danger btn-sm delete-btn" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $package->id }}"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            
                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $package->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Package</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="{{ route('admin.venue-packages.update', $package->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label>Package Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $package->name }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Price</label>
                                                    <input type="number" name="price" class="form-control" value="{{ $package->price }}" step="0.01" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Features (Comma separated)</label>
                                                    <textarea name="features" class="form-control" rows="3">{{ implode(', ', $package->features->pluck('name')->toArray()) }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Add More Images</label>
                                                    <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                                                </div>
                                                <div class="text-end mb-3">
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                            
                                            <hr>
                                            <h5>Manage Existing Images</h5>
                                            <div class="row mt-3">
                                                @foreach($package->images as $image)
                                                <div class="col-md-3 mb-3 text-center">
                                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail mb-2" style="height: 100px; object-fit: cover;">
                                                    <form action="{{ route('admin.venue-packages.delete-image', $image->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</button>
                                                    </form>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $package->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form method="POST" action="{{ route('admin.venue-packages.destroy', $package->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Delete Package</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete this package?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </div>

                            @endforeach
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No venues or packages available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.venue-packages.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Package</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Venue</label>
                            <select name="venue_id" class="form-control" required>
                                <option value="">Select Venue...</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Package Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" class="form-control" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Features (Comma separated)</label>
                            <textarea name="features" class="form-control" rows="3" placeholder="e.g. 50 Chairs, Catering not included"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Images</label>
                            <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    </div>
    @include('partials.admin.footer')
</div>
@endsection
