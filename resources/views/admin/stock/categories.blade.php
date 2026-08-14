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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
       $('.edit-btn').on('click', function () {
           let id = $(this).data('id');
           $('#editName').val($(this).data('name'));
           $('#editDescription').val($(this).data('description'));
   
           let actionUrl = "{{ route('admin.stock-categories.update', ':id') }}".replace(':id', id);
           $('#editForm').attr('action', actionUrl);
       });

       $('.delete-btn').on('click', function() {
           let id = $(this).data('id');
           let actionUrl = "{{ route('admin.stock-categories.destroy', ':id') }}".replace(':id', id);
           $('#deleteForm').attr('action', actionUrl);
       });
   });
</script>
@endpush

@section('title', 'Admin - Stock - Categories')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      @include('partials.message-bag')

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Stock Categories ({{ $categories->count() }})</span>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    Add New Category
                </button>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ Str::limit($category->description, 50) }}</td>
                            <td>
                                <button class="m-2 btn btn-success btn-sm edit-btn" 
                                    data-id="{{ $category->id }}" 
                                    data-name="{{ $category->name }}" 
                                    data-description="{{ $category->description }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal"><i class="fa fa-edit"></i></button>

                                <button class="m-2 btn btn-danger btn-sm delete-btn" 
                                    data-id="{{ $category->id }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">No categories available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
   
    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.stock-categories.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"></textarea>
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
    
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="editDescription" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
      <div class="modal-dialog">
          <form method="POST" id="deleteForm">
              @csrf
              @method('DELETE')
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Delete Category</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <p>Are you sure you want to delete this category?</p>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-danger">Delete</button>
                  </div>
              </div>
          </form>
      </div>
    </div>
    
    </div>
    @include('partials.admin.footer')
</div>
@endsection
