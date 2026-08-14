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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        let itemIndex = 1;
        $('#addItemBtn').on('click', function() {
            let row = `
                <div class="row mt-2 item-row">
                    <div class="col-md-7">
                        <select name="items[${itemIndex}][stock_item_id]" class="form-control" required>
                            <option value="">Select Item</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} (In Stock: {{ $item->quantity }} {{ $item->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" step="0.01" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Qty" required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-item-btn"><i class="fa fa-times"></i></button>
                    </div>
                </div>
            `;
            $('#itemsContainer').append(row);
            itemIndex++;
        });

        $(document).on('click', '.remove-item-btn', function() {
            $(this).closest('.item-row').remove();
        });

       $('.delete-btn').on('click', function() {
           let id = $(this).data('id');
           let actionUrl = "{{ route('admin.stock-issues.destroy', ':id') }}".replace(':id', id);
           $('#deleteForm').attr('action', actionUrl);
       });
   });
</script>
@endpush

@section('title', 'Admin - Stock - Issues')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      @include('partials.message-bag')

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Stock Issues (Out to Chef/Bar)</span>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                    Issue Stock
                </button>
            </div>
            <div class="card-body">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Department</th>
                            <th>Note</th>
                            <th>Issued By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($issues as $issue)
                        <tr>
                            <td>{{ $issue->id }}</td>
                            <td>{{ $issue->date }}</td>
                            <td>{{ $issue->department }}</td>
                            <td>{{ $issue->note }}</td>
                            <td>{{ $issue->createdBy->first_name ?? 'N/A' }}</td>
                            <td>
                                <button class="m-2 btn btn-danger btn-sm delete-btn" 
                                    data-id="{{ $issue->id }}" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No stock issues recorded.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
   
    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="{{ route('admin.stock-issues.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Issue Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Date</label>
                                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Department (Location)</label>
                                <select name="department" class="form-control" required>
                                    <option value="Kitchen">Kitchen (Chef)</option>
                                    <option value="Bar">Bar</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <input type="text" name="note" class="form-control">
                        </div>

                        <hr>
                        <h6>Items</h6>
                        <div id="itemsContainer">
                            <div class="row mt-2 item-row">
                                <div class="col-md-7">
                                    <select name="items[0][stock_item_id]" class="form-control" required>
                                        <option value="">Select Item</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }} (In Stock: {{ $item->quantity }} {{ $item->unit }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" step="0.01" name="items[0][quantity]" class="form-control" placeholder="Qty" required>
                                </div>
                                <div class="col-md-1">
                                    
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-3" id="addItemBtn">Add Item Row</button>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Issue Stock</button>
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
                      <h5 class="modal-title">Delete Issue</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <p>Are you sure you want to delete this issue? This will REVERT the stock, putting it back in the main store.</p>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-danger">Delete & Revert Stock</button>
                  </div>
              </div>
          </form>
      </div>
    </div>
    
    </div>
    @include('partials.admin.footer')
</div>
@endsection
