@extends('layouts.admin')

@section('title', 'Order #' . $order->order_no . ' — All The Season Garden')

@push('styles')
<style>
    .order-show-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Top Bar Actions */
    .show-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        background: #ffffff;
        color: #374151 !important;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        border: 1px solid #e5e7eb;
        transition: background 0.15s ease;
    }
    .btn-back:hover {
        background: #f9fafb;
        border-color: #d1d5db;
    }

    .top-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        cursor: pointer;
        border: 1px solid transparent;
        transition: all 0.15s ease;
    }
    .btn-kitchen {
        background: #fff7ed;
        color: #c2410c !important;
        border-color: #ffedd5;
    }
    .btn-kitchen:hover {
        background: #ffedd5;
    }
    .btn-receipt {
        background: #eff6ff;
        color: #1d4ed8 !important;
        border-color: #dbeafe;
    }
    .btn-receipt:hover {
        background: #dbeafe;
    }
    .btn-update {
        background: #111827;
        color: #ffffff !important;
    }
    .btn-update:hover {
        background: #1f2937;
    }
    .btn-delete-order {
        background: #fef2f2;
        color: #dc2626 !important;
        border-color: #fee2e2;
    }
    .btn-delete-order:hover {
        background: #fee2e2;
    }

    /* Main Order Card Header */
    .order-main-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 20px 24px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .order-title-group h2 {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .order-title-group p {
        font-size: 12.5px;
        color: #6b7280;
        margin: 0;
    }

    /* Badges */
    .badge-pill-custom {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-pending { background: #fef3c7; color: #b45309; }
    .badge-completed { background: #dcfce7; color: #15803d; }
    .badge-cancelled { background: #fee2e2; color: #b91c1c; }
    .badge-type { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }

    /* Metric Cards */
    .show-metric-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 16px 18px;
        height: 100%;
    }
    .show-metric-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .show-metric-value {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        letter-spacing: -0.02em;
    }

    /* Section Cards */
    .show-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .show-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .show-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Items Table */
    .items-table {
        width: 100%;
        border-collapse: collapse;
    }
    .items-table th {
        background: #f9fafb;
        padding: 11px 20px;
        font-size: 11.5px;
        font-weight: 600;
        color: #4b5563;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border-bottom: 1px solid #e5e7eb;
    }
    .items-table td {
        padding: 14px 20px;
        font-size: 13px;
        color: #111827;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    .items-table tr:last-child td {
        border-bottom: none;
    }
    .summary-tr {
        background: #f9fafb;
        font-size: 13px;
    }
    .summary-tr td {
        padding: 10px 20px;
        border-bottom: 1px solid #f3f4f6;
    }

    /* Details Grid Table */
    .details-table {
        width: 100%;
        border-collapse: collapse;
    }
    .details-table td {
        padding: 12px 20px;
        font-size: 13px;
        border-bottom: 1px solid #f3f4f6;
    }
    .details-table tr:last-child td {
        border-bottom: none;
    }
    .details-label {
        width: 40%;
        color: #6b7280;
        font-weight: 500;
    }
    .details-val {
        color: #111827;
        font-weight: 600;
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#copy_session_id').click(function() {
            var input = $('#session_id');
            input.select();
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            $('#copy-alert').fadeIn();
            setTimeout(function() { $('#copy-alert').fadeOut(); }, 3000);
        });
    });
</script>
@endpush

@section('content')
<div class="content-wrapper order-show-wrap">
    
    @include('partials.message-bag')

    {{-- Top Action Bar --}}
    <div class="show-topbar">
        <a href="{{ route('admin.orders.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
        
        <div class="top-actions">
            @if ($order->order_type === 'instore')
                <button type="button" onclick="window.open('{{ route('admin.orders.receipt', $order->id) }}?kitchen=1', '_blank', 'width=400,height=600')" class="action-btn btn-kitchen">
                    <i class="fas fa-fire"></i> Kitchen Ticket
                </button>
            @endif

            <button type="button" onclick="window.open('{{ route('admin.orders.receipt', $order->id) }}', '_blank', 'width=400,height=600')" class="action-btn btn-receipt">
                <i class="fas fa-print"></i> Receipt
            </button>

            @if ($order->status_online_pay == 'paid' || is_null($order->status_online_pay))
                @if ($order->status !== 'completed' && $order->status !== 'cancelled')
                    <button type="button" class="action-btn btn-update" data-bs-toggle="modal" data-bs-target="#updateModal">
                        <i class="fas fa-check-circle"></i> Update Status
                    </button>
                @endif
            @endif

            @if ($loggedInUser->role == "global_admin")
                <button type="button" class="action-btn btn-delete-order" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="fas fa-trash-alt"></i> Delete
                </button>
            @endif
        </div>
    </div>

    @if(!is_null($order->status_online_pay) && $order->status_online_pay == 'unpaid')
        <div class="alert alert-warning d-flex align-items-center border-0 mb-3" style="border-radius: 8px;">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <div><strong>Notice:</strong> Online payment for this order has not been confirmed yet.</div>
        </div>
    @endif

    {{-- Order Header Card --}}
    <div class="order-main-card">
        <div class="order-title-group">
            <div class="d-flex align-items-center gap-2 mb-1">
                <h2>Order #{{ $order->order_no }}</h2>
                <span class="badge-pill-custom badge-type">{{ ucfirst($order->order_type) }}</span>
            </div>
            <p>Placed on {{ $order->created_at->format('g:i A — j M, Y') }} ({{ $order->created_at->diffForHumans() }})</p>
        </div>

        <div>
            @switch($order->status)
                @case('pending')
                    <span class="badge-pill-custom badge-pending"><i class="fas fa-clock"></i> Pending</span>
                    @break
                @case('completed')
                    <span class="badge-pill-custom badge-completed"><i class="fas fa-check"></i> Completed</span>
                    @break
                @case('cancelled')
                    <span class="badge-pill-custom badge-cancelled"><i class="fas fa-times"></i> Cancelled</span>
                    @break
                @default
                    <span class="badge-pill-custom badge-type">{{ ucfirst($order->status) }}</span>
            @endswitch
        </div>
    </div>

    {{-- 4 Metric Cards --}}
    <div class="row g-3 mb-4">
        <!-- Total Price -->
        <div class="col-xl-3 col-sm-6">
            <div class="show-metric-card">
                <div class="show-metric-label"><i class="fas fa-wallet text-success"></i> Total Amount</div>
                <div class="show-metric-value text-success">
                    {!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->total_price + ($order->delivery_fee ?? 0), 2) }}
                </div>
                @if($order->discount_amount > 0)
                    <small class="text-muted d-block mt-1" style="font-size: 11px;">Discount: -{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->discount_amount, 2) }}</small>
                @endif
            </div>
        </div>

        <!-- Payment Method -->
        <div class="col-xl-3 col-sm-6">
            <div class="show-metric-card">
                <div class="show-metric-label"><i class="fas fa-credit-card text-primary"></i> Payment Method</div>
                <div class="show-metric-value" style="font-size: 17px;">
                    {{ $order->payment_method ?? 'Pending' }}
                </div>
                @if($order->status_online_pay)
                    <small class="d-block mt-1 text-muted" style="font-size: 11px;">
                        Pay status: <strong>{{ ucfirst($order->status_online_pay) }}</strong>
                    </small>
                @endif
            </div>
        </div>

        <!-- Location / Table -->
        <div class="col-xl-3 col-sm-6">
            <div class="show-metric-card">
                <div class="show-metric-label"><i class="fas fa-utensils text-warning"></i> Table / Service</div>
                <div class="show-metric-value" style="font-size: 17px;">
                    @if($order->order_type === 'instore')
                        {{ $order->restaurantTable ? $order->restaurantTable->name : 'Walk-in' }}
                    @else
                        {{ ucfirst($order->order_type) }}
                    @endif
                </div>
                @if($order->waiter)
                    <small class="text-muted d-block mt-1" style="font-size: 11px;">Waiter: {{ $order->waiter->name }}</small>
                @endif
            </div>
        </div>

        <!-- Customer -->
        <div class="col-xl-3 col-sm-6">
            <div class="show-metric-card">
                <div class="show-metric-label"><i class="fas fa-user text-info"></i> Customer</div>
                <div class="show-metric-value text-truncate" style="font-size: 17px;" title="{{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Guest' }}">
                    {{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Guest' }}
                </div>
                @if($order->customer && ($order->customer->phone || $order->customer->phone_number))
                    <small class="text-muted d-block mt-1" style="font-size: 11px;">{{ $order->customer->phone_number ?? $order->customer->phone }}</small>
                @endif
            </div>
        </div>
    </div>

    {{-- Order Items Table Card --}}
    <div class="show-card">
        <div class="show-card-header">
            <h3 class="show-card-title"><i class="fas fa-shopping-bag text-muted"></i> Order Items</h3>
            <span class="badge bg-light text-dark border font-weight-normal" style="font-size: 12px; padding: 4px 10px;">
                {{ $order->orderItems->sum('quantity') }} total items
            </span>
        </div>
        
        <div class="table-responsive">
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th>Unit Price</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->orderItems as $item)
                        @php
                            $unitPrice = $item->quantity > 0 ? $item->subtotal / $item->quantity : 0;
                        @endphp
                        <tr>
                            <td>
                                <div class="fw-semibold text-dark">{{ $item->menu_name }}</div>
                                @if($item->item_note)
                                    <small class="text-muted d-block mt-1" style="font-size: 11.5px;">
                                        <i class="fas fa-comment-alt text-info me-1"></i> {{ $item->item_note }}
                                    </small>
                                @endif
                            </td>
                            <td>{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($unitPrice, 2) }}</td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark border px-2 py-1">x {{ $item->quantity }}</span>
                            </td>
                            <td class="text-end fw-bold">{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach

                    <tr class="summary-tr">
                        <td colspan="3" class="text-end fw-semibold">Subtotal:</td>
                        <td class="text-end fw-semibold">{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->total_price + ($order->discount_amount ?? 0), 2) }}</td>
                    </tr>

                    @if(($order->discount_amount ?? 0) > 0)
                        <tr class="summary-tr text-danger">
                            <td colspan="3" class="text-end fw-semibold">Discount:</td>
                            <td class="text-end fw-semibold">-{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->discount_amount, 2) }}</td>
                        </tr>
                    @endif

                    @if(!is_null($order->delivery_fee))
                        <tr class="summary-tr">
                            <td colspan="3" class="text-end fw-semibold">Delivery Fee:</td>
                            <td class="text-end fw-semibold">{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->delivery_fee, 2) }}</td>
                        </tr>
                    @endif

                    <tr class="summary-tr" style="border-top: 2px solid #e5e7eb;">
                        <td colspan="3" class="text-end fw-bold" style="font-size: 14px;">Total Amount:</td>
                        <td class="text-end fw-bold text-success" style="font-size: 16px;">{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->total_price + ($order->delivery_fee ?? 0), 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        @if($order->additional_info)
            <div class="p-3 bg-light border-top" style="font-size: 12.5px;">
                <div class="fw-bold text-dark mb-1"><i class="fas fa-sticky-note text-warning me-1"></i> Order Notes / Instructions:</div>
                <div class="text-muted">{{ $order->additional_info }}</div>
            </div>
        @endif
    </div>

    {{-- Details Grid --}}
    <div class="row g-3 mb-4">
        
        <!-- Service Information -->
        <div class="col-lg-6">
            <div class="show-card h-100 mb-0">
                <div class="show-card-header">
                    <h3 class="show-card-title"><i class="fas fa-info-circle text-muted"></i> Service Information</h3>
                </div>
                <table class="details-table">
                    <tbody>
                        <tr>
                            <td class="details-label">Order Number</td>
                            <td class="details-val">#{{ $order->order_no }}</td>
                        </tr>
                        <tr>
                            <td class="details-label">Order Type</td>
                            <td class="details-val"><span class="badge bg-light text-dark border">{{ ucfirst($order->order_type) }}</span></td>
                        </tr>
                        <tr>
                            <td class="details-label">Created By</td>
                            <td class="details-val">{{ $order->createdByUser ? $order->createdByUser->first_name . ' ' . $order->createdByUser->last_name : 'System / Self-Order' }}</td>
                        </tr>
                        @if($order->waiter)
                            <tr>
                                <td class="details-label">Assigned Waiter</td>
                                <td class="details-val">{{ $order->waiter->name }}</td>
                            </tr>
                        @endif
                        @if($order->restaurantTable)
                            <tr>
                                <td class="details-label">Table Name</td>
                                <td class="details-val">{{ $order->restaurantTable->name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="details-label">Created Date</td>
                            <td class="details-val">{{ $order->created_at->format('g:i A — j M, Y') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer & Fulfillment Details -->
        <div class="col-lg-6">
            <div class="show-card h-100 mb-0">
                <div class="show-card-header">
                    <h3 class="show-card-title"><i class="fas fa-user text-muted"></i> Customer & Fulfillment</h3>
                </div>
                <table class="details-table">
                    <tbody>
                        <tr>
                            <td class="details-label">Customer Name</td>
                            <td class="details-val">{{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Guest Customer' }}</td>
                        </tr>
                        <tr>
                            <td class="details-label">Email Address</td>
                            <td class="details-val">{{ $order->customer->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="details-label">Phone Number</td>
                            <td class="details-val">{{ $order->customer->phone_number ?? $order->customer->phone ?? 'N/A' }}</td>
                        </tr>
                        @if($order->order_type === 'pickup' && $order->pickupAddress)
                            <tr>
                                <td class="details-label">Pickup Location</td>
                                <td class="details-val">{{ $order->pickupAddress->full_address ?? 'Store Location' }}</td>
                            </tr>
                        @elseif($order->deliveryAddressWithTrashed)
                            <tr>
                                <td class="details-label">Delivery Address</td>
                                <td class="details-val">{{ $order->deliveryAddressWithTrashed->full_address ?? 'N/A' }}</td>
                            </tr>
                        @else
                            <tr>
                                <td class="details-label">Fulfillment</td>
                                <td class="details-val text-muted fw-normal">In-Store Dining / Counter Service</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    @if (!is_null($order->session_id))
        <div class="alert alert-success alert-dismissible fade show border-0 mb-3" role="alert" id="copy-alert" style="display: none; border-radius: 8px;">
            <strong><i class="fas fa-check-circle me-1"></i> Payment Session ID copied to clipboard!</strong>
        </div>

        <div class="show-card mb-4">
            <div class="p-3">
                <label class="fw-bold mb-2 text-dark" style="font-size: 13px;">Online Payment Session ID</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="session_id" value="{{ $order->session_id }}" readonly style="font-size: 13px;">
                    <button id="copy_session_id" class="btn btn-secondary font-weight-bold px-3" type="button" style="font-size: 13px;">
                        <i class="fas fa-copy me-1"></i> Copy
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Update Order Status Modal --}}
    <div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Update Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                    @csrf
                    <div class="modal-body py-4">
                        <label for="orderStatus" class="fw-semibold mb-2" style="font-size: 13px;">Select New Status</label>
                        <select class="form-select" id="orderStatus" name="status" style="height: 42px; font-size: 13px;">
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 justify-content-center">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger px-4 font-weight-bold">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($loggedInUser->role == "global_admin")
        {{-- Delete Confirmation Modal --}}
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4" style="font-size: 13.5px; color: #4b5563;">
                        Are you sure you want to delete Order #{{ $order->order_no }}? This action cannot be undone.
                    </div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger px-4 font-weight-bold">Delete Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection