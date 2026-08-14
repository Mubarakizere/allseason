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
           $('#editContactName').val($(this).data('contact_name'));
           $('#editPhone').val($(this).data('phone'));
           $('#editEmail').val($(this).data('email'));
           $('#editAddress').val($(this).data('address'));
   
           let actionUrl = "{{ route('admin.suppliers.update', ':id') }}".replace(':id', id);
           $('#editForm').attr('action', actionUrl);
       });

       $('.delete-btn').on('click', function() {
           let id = $(this).data('id');
           let actionUrl = "{{ route('admin.suppliers.destroy', ':id') }}".replace(':id', id);
           $('#deleteForm').attr('action', actionUrl);
       });
   });
</script>
@endpush

@section('title', 'Admin - Stock - Suppliers')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      @include('partials.message-bag')

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Suppliers ({{ $suppliers->count() }})</span>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    Add New Supplier
                </button>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->contact_name ?? 'N/A' }}</td>
                            <td>{{ $supplier->phone ?? 'N/A' }}</td>
                            <td>{{ $supplier->email ?? 'N/A' }}</td>
                            <td>
                                <button class="m-2 btn btn-success btn-sm edit-btn" 
                                    data-id="{{ $supplier->id }}" 
                                    data-name="{{ $supplier->name }}" 
                                    data-contact_name="{{ $supplier->contact_name }}" 
                                    data-phone="{{ $supplier->phone }}" 
                                    data-email="{{ $supplier->email }}" 
                                    data-address="{{ $supplier->address }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal"><i class="fa fa-edit"></i></button>

                                <button class="m-2 btn btn-danger btn-sm delete-btn" 
                                    data-id="{{ $supplier->id }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No suppliers available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
   
    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.suppliers.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Supplier Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Name</label>
                            <input type="text" name="contact_name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control"></textarea>
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
                        <h5 class="modal-title">Edit Supplier</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Supplier Name</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Name</label>
                            <input type="text" name="contact_name" id="editContactName" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" id="editPhone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" id="editEmail" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" id="editAddress" class="form-control"></textarea>
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
                      <h5 class="modal-title">Delete Supplier</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <p>Are you sure you want to delete this supplier?</p>
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
