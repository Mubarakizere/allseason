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
    $(document).ready(function () {
       $('.edit-btn').on('click', function () {
           let id = $(this).data('id');
           let status = $(this).data('status');
           let payment = $(this).data('payment');
   
           $('#editStatus').val(status);
           $('#editPayment').val(payment);
   
           let actionUrl = "{{ route('admin.room-bookings.update', ':id') }}".replace(':id', id);
           $('#editForm').attr('action', actionUrl);
       });

       $('.delete-btn').on('click', function() {
           let id = $(this).data('id');
           let actionUrl = "{{ route('admin.room-bookings.destroy', ':id') }}".replace(':id', id);
           $('#deleteForm').attr('action', actionUrl);
       });
   });
</script>
@endpush

@section('title', 'Admin - Room Bookings')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">
      @include('partials.message-bag')

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Room Bookings ({{ $bookings->count() }})</span>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Room & Dates</th>
                            <th>Total/Deposit</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                        <tr>
                            <td>
                                <strong>{{ $booking->customer_name }}</strong><br>
                                {{ $booking->customer_email }}<br>
                                {{ $booking->customer_phone }}
                            </td>
                            <td>
                                <strong>{{ $booking->room->name ?? 'N/A' }}</strong> <br>
                                Check-in: <strong>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</strong><br>
                                Check-out: <strong>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</strong>
                            </td>
                            <td>
                                Total: {!! $site_settings->currency_symbol !!}{{ number_format($booking->total_price, 2) }}<br>
                                Deposit: {!! $site_settings->currency_symbol !!}{{ number_format($booking->deposit_amount, 2) }}
                            </td>
                            <td>
                                @if($booking->payment_status == 'unpaid')
                                    <span class="badge bg-warning text-dark">Unpaid</span>
                                @elseif($booking->payment_status == 'deposit_paid')
                                    <span class="badge bg-primary text-white">Deposit Paid</span>
                                @else
                                    <span class="badge bg-success text-white">Fully Paid</span>
                                @endif
                            </td>
                            <td>
                                @if($booking->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($booking->status == 'confirmed')
                                    <span class="badge bg-success text-white">Confirmed</span>
                                @else
                                    <span class="badge bg-danger text-white">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <button class="m-1 btn btn-success btn-sm edit-btn" 
                                    data-id="{{ $booking->id }}" 
                                    data-status="{{ $booking->status }}"
                                    data-payment="{{ $booking->payment_status }}"
                                    data-bs-toggle="modal" data-bs-target="#editModal"><i class="fa fa-edit"></i></button>

                                <button class="m-1 btn btn-danger btn-sm delete-btn" 
                                    data-id="{{ $booking->id }}" 
                                    data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No bookings available.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    
    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Booking Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Payment Status</label>
                            <select name="payment_status" id="editPayment" class="form-control" required>
                                <option value="unpaid">Unpaid</option>
                                <option value="deposit_paid">Deposit Paid</option>
                                <option value="fully_paid">Fully Paid</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Booking Status</label>
                            <select name="status" id="editStatus" class="form-control" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
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
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
          <form method="POST" id="deleteForm">
              @csrf
              @method('DELETE')
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Delete Booking</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                      <p>Are you sure you want to delete this booking?</p>
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
