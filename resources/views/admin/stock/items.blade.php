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
           $('#editCategory').val($(this).data('category_id'));
           $('#editSku').val($(this).data('sku'));
           $('#editUnit').val($(this).data('unit'));
           $('#editAlertQuantity').val($(this).data('alert_quantity'));
           $('#editCostPrice').val($(this).data('cost_price'));
           $('#editDescription').val($(this).data('description'));
   
           let actionUrl = "{{ route('admin.stock-items.update', ':id') }}".replace(':id', id);
           $('#editForm').attr('action', actionUrl);
       });

       $('.delete-btn').on('click', function() {
           let id = $(this).data('id');
           let actionUrl = "{{ route('admin.stock-items.destroy', ':id') }}".replace(':id', id);
           $('#deleteForm').attr('action', actionUrl);
       });
   });
</script>
@endpush

@section('title', 'Admin - Stock - Items')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      @include('partials.message-bag')

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Stock Items ({{ $items->count() }})</span>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    Add New Item
                </button>
            </div>
            <div class="card-body">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Category</th>
                            <th>SKU</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Alert Qty</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->category->name ?? 'None' }}</td>
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->unit }}</td>
                            <td>
                                @if($item->quantity <= $item->alert_quantity)
                                    <span class="badge badge-danger">{{ $item->quantity }}</span>
                                @else
                                    <span class="badge badge-success">{{ $item->quantity }}</span>
                                @endif
                            </td>
                            <td>{{ $item->alert_quantity }}</td>
                            <td>
                                <button class="m-2 btn btn-success btn-sm edit-btn" 
                                    data-id="{{ $item->id }}" 
                                    data-name="{{ $item->name }}" 
                                    data-category_id="{{ $item->stock_category_id }}" 
                                    data-sku="{{ $item->sku }}" 
                                    data-unit="{{ $item->unit }}" 
                                    data-alert_quantity="{{ $item->alert_quantity }}" 
                                    data-cost_price="{{ $item->cost_price }}" 
                                    data-description="{{ $item->description }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editModal"><i class="fa fa-edit"></i></button>

                                <button class="m-2 btn btn-danger btn-sm delete-btn" 
                                    data-id="{{ $item->id }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No stock items available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
   
    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.stock-items.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Stock Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="stock_category_id" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>SKU</label>
                                <input type="text" name="sku" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Unit (pcs, kg, L)</label>
                                <input type="text" name="unit" class="form-control" value="pcs" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Initial Quantity</label>
                                <input type="number" step="0.01" name="quantity" class="form-control" value="0">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Alert Quantity</label>
                                <input type="number" step="0.01" name="alert_quantity" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Cost Price</label>
                            <input type="number" step="0.01" name="cost_price" class="form-control" value="0">
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
                        <h5 class="modal-title">Edit Stock Item</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="editName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="stock_category_id" id="editCategory" class="form-control">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>SKU</label>
                                <input type="text" name="sku" id="editSku" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Unit (pcs, kg, L)</label>
                                <input type="text" name="unit" id="editUnit" class="form-control" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Alert Quantity</label>
                                <input type="number" step="0.01" name="alert_quantity" id="editAlertQuantity" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Cost Price</label>
                                <input type="number" step="0.01" name="cost_price" id="editCostPrice" class="form-control">
                            </div>
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
                      <h5 class="modal-title">Delete Stock Item</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <p>Are you sure you want to delete this stock item?</p>
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
