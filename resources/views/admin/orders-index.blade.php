
@extends('layouts.admin')

@push('styles')
    <!-- base:css -->
    <link rel="stylesheet" href="/admin_resources/vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="/admin_resources/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/admin_resources/css/vertical-layout-light/style.css">
    <!-- DataTables CSS -->

    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="/admin_resources/css/small-box.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        .orders-container {
            font-family: 'Inter', sans-serif;
        }
        .premium-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #f0f0f0;
            overflow: hidden;
        }
        .premium-card .card-header {
            background: transparent;
            border-bottom: 2px dashed #eee;
            padding: 20px 25px;
        }
        .premium-card .card-title {
            font-weight: 800;
            color: #333;
            font-size: 1.25rem;
            text-transform: capitalize;
        }
        .filter-pills .btn {
            border-radius: 20px !important;
            padding: 6px 16px;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }
        .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter {
            padding: 20px 25px 10px 25px;
        }
        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0;
            width: 100% !important;
            border: none !important;
        }
        table.dataTable thead th {
            border-bottom: 2px solid #eee !important;
            border-top: none !important;
            color: #555;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            padding: 16px 20px !important;
        }
        table.dataTable tbody td {
            padding: 16px 20px !important;
            vertical-align: middle;
            border-bottom: 1px solid #f5f5f5 !important;
            border-top: none !important;
            color: #334155;
        }
        table.dataTable tbody tr:hover {
            background-color: #f8fafc !important;
        }
        .dataTables_info, .dataTables_paginate {
            padding: 20px 25px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            border: 1px solid #eee !important;
            background: white !important;
            color: #333 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #28a745 !important;
            color: white !important;
            border-color: #28a745 !important;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f8f9fa !important;
            color: #333 !important;
            border-color: #ddd !important;
        }
    </style>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(function () {
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('admin.orders.index', ['filter' => $filter]) }}",
          columns: [
            { data: 'order_no', name: 'order_no' },
            { data: 'details', name: 'details', orderable: false, searchable: false },
            { data: 'items_preview', name: 'items_preview', orderable: false, searchable: false },
            { data: 'total_price', name: 'total_price' },
            { data: 'payment', name: 'payment', orderable: false, searchable: false },
            { data: 'status', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
          ]
      });
    });
 
    $(document).ready(function() {
        $('#deleteModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);  
            var id = button.data('id');  
            var actionUrl = "{{ route('admin.orders.destroy', ':id') }}".replace(':id', id);
            $('#deleteForm').attr('action', actionUrl);
        });

        // Auto-print polling
        setInterval(function() {
            $.ajax({
                url: "{{ route('admin.orders.unprinted') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success && response.order_id) {
                        let receiptUrl = "{{ url('admin/orders') }}/" + response.order_id + "/receipt?kitchen=1";
                        let printWindow = window.open(receiptUrl, '_blank', 'width=400,height=600');
                        
                        $.ajax({
                            url: "{{ url('admin/orders') }}/" + response.order_id + "/mark-printed",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}"
                            }
                        });
                        
                        $('.data-table').DataTable().ajax.reload(null, false);
                    }
                }
            });
        }, 10000); // Poll every 10 seconds
    });
</script>
@endpush

@section('title', 'Admin - Manage Orders')

@section('content')

<div class="main-panel orders-container">
    <div class="content-wrapper">
 
    @include('partials.message-bag')

    @include('partials.order-stats')

      <div class="premium-card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0">{{ ucfirst($filter ?: 'All') }} Orders</h5>
            <div class="filter-pills d-flex flex-wrap gap-1">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ empty($filter) ? 'btn-success font-weight-bold' : 'btn-light' }}">All Orders</a>
                <a href="{{ route('admin.orders.index', ['filter' => 'pending']) }}" class="btn btn-sm {{ $filter == 'pending' ? 'btn-warning font-weight-bold text-dark' : 'btn-light' }}">Pending</a>
                <a href="{{ route('admin.orders.index', ['filter' => 'instore']) }}" class="btn btn-sm {{ $filter == 'instore' ? 'btn-primary font-weight-bold' : 'btn-light' }}">In-Store</a>
                <a href="{{ route('admin.orders.index', ['filter' => 'delivery']) }}" class="btn btn-sm {{ $filter == 'delivery' ? 'btn-info font-weight-bold' : 'btn-light' }}">Delivery</a>
                <a href="{{ route('admin.orders.index', ['filter' => 'completed']) }}" class="btn btn-sm {{ $filter == 'completed' ? 'btn-success font-weight-bold' : 'btn-light' }}">Completed</a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive pb-4">
                <table class="table data-table" id="orders-table">
                    <thead>
                        <tr>
                            <th>Order No.</th>
                            <th>Service Details</th>
                            <th>Items</th>
                            <th>Total Price</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th style="min-width: 210px;">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @if ($loggedInUser->role == "global_admin")
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold" id="deleteModalLabel">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        Are you sure you want to delete this order? This action cannot be undone.
                    </div>
                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4">Delete Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    @endif

    </div>
    <!-- content-wrapper ends -->
    @include('partials.admin.footer')
  </div>
  <!-- main-panel ends -->
@endsection



 