
@extends('layouts.admin')

@push('styles')
    <!-- base:css -->
    <link rel="stylesheet" href="/admin_resources/vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="/admin_resources/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/admin_resources/css/vertical-layout-light/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        .order-show-container {
            font-family: 'Inter', sans-serif;
        }
        .order-header-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
            margin-bottom: 24px;
        }
        .metric-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            border: 1px solid #f1f5f9;
            height: 100%;
            transition: transform 0.2s ease;
        }
        .metric-card:hover {
            transform: translateY(-3px);
        }
        .metric-label {
            font-size: 0.82rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }
        .metric-value {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
        }
        .detail-card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            border: 1px solid #f0f0f0;
            overflow: hidden;
            margin-bottom: 24px;
        }
        .detail-card .card-header {
            background: #ffffff;
            border-bottom: 2px dashed #e2e8f0;
            padding: 18px 24px;
        }
        .detail-card .card-title {
            font-weight: 800;
            color: #1e293b;
            font-size: 1.1rem;
            margin: 0;
        }
        .detail-table th {
            font-weight: 700;
            color: #475569;
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0 !important;
            padding: 14px 20px !important;
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        .detail-table td {
            padding: 14px 20px !important;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        .table-summary-row {
            background-color: #f8fafc;
        }
        .badge-status {
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#copy_session_id').click(function() {
            var sessionIdInput = $('#session_id');
            sessionIdInput.select();
            document.execCommand('copy');
            window.getSelection().removeAllRanges();
            $('#copy-alert').fadeIn();
            setTimeout(function() {
                $('#copy-alert').fadeOut();
            }, 3000);
        });
    });
</script>
@endpush

@section('title', 'Admin - View Order #' . $order->order_no)

@section('content')
<div class="main-panel order-show-container">
    <div class="content-wrapper">
 
        @include('partials.message-bag')

        <!-- Back Button & Header Actions -->
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm font-weight-bold">
                <i class="fa fa-arrow-left mr-1"></i> Back to Orders
            </a>
            
            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
                @if ($order->order_type === 'instore')
                    <button type="button" onclick="window.open('{{ route('admin.orders.receipt', $order->id) }}?kitchen=1', '_blank', 'width=400,height=600')" class="btn btn-warning btn-sm font-weight-bold text-dark">
                        <i class="fa fa-fire mr-1"></i> Kitchen Ticket
                    </button>
                @endif

                <button type="button" onclick="window.open('{{ route('admin.orders.receipt', $order->id) }}', '_blank', 'width=400,height=600')" class="btn btn-info btn-sm font-weight-bold text-white">
                    <i class="fa fa-print mr-1"></i> Customer Receipt
                </button>

                @if ($order->status_online_pay == 'paid' || is_null($order->status_online_pay))
                    @if ($order->status !== 'completed' && $order->status !== 'cancelled')
                        <button class="btn btn-success btn-sm font-weight-bold" data-bs-toggle="modal" data-bs-target="#updateModal">
                            <i class="fa fa-check-circle mr-1"></i> Update Status
                        </button>
                    @endif
                @endif

                @if ($loggedInUser->role == "global_admin")
                    <button type="button" class="btn btn-outline-danger btn-sm font-weight-bold" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fa fa-trash mr-1"></i> Delete Order
                    </button>
                @endif
            </div>
        </div>

        @if(!is_null($order->status_online_pay) && $order->status_online_pay == 'unpaid')
            <div class="alert alert-danger d-flex align-items-center border-0 mb-4" role="alert" style="border-radius: 10px;">
                <i class="fa fa-exclamation-triangle fa-lg mr-2"></i>
                <div class="font-weight-bold">
                    Notice: Online payment for this order has not been confirmed yet.
                </div>
            </div>
        @endif

        <!-- Order Overview Banner -->
        <div class="order-header-card d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <h3 class="font-weight-bold m-0 text-dark">Order #{{ $order->order_no }}</h3>
                    <span class="badge badge-light border font-weight-bold px-3 py-1">{{ ucfirst($order->order_type) }} Order</span>
                </div>
                <small class="text-muted">Placed on {{ $order->created_at->format('g:i A - j M, Y') }} ({{ $order->created_at->diffForHumans() }})</small>
            </div>

            <div>
                @switch($order->status)
                    @case('pending')
                        <span class="badge badge-warning text-dark badge-status"><i class="fa fa-clock-o mr-1"></i> Pending Order</span>
                        @break
                    @case('completed')
                        <span class="badge badge-success text-white badge-status"><i class="fa fa-check mr-1"></i> Completed Order</span>
                        @break
                    @case('cancelled')
                        <span class="badge badge-danger text-white badge-status"><i class="fa fa-times mr-1"></i> Cancelled Order</span>
                        @break
                    @default
                        <span class="badge badge-secondary badge-status">{{ ucfirst($order->status) }}</span>
                @endswitch
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                <div class="metric-card">
                    <div class="metric-label"><i class="fa fa-money text-success mr-1"></i> Total Price</div>
                    <div class="metric-value text-success">
                        {!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->total_price + ($order->delivery_fee ?? 0), 2) }}
                    </div>
                    @if($order->discount_amount > 0)
                        <small class="text-muted d-block mt-1">Discount Applied: -{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->discount_amount, 2) }}</small>
                    @endif
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
                <div class="metric-card">
                    <div class="metric-label"><i class="fa fa-credit-card text-info mr-1"></i> Payment Method</div>
                    <div class="metric-value" style="font-size: 1.15rem;">
                        {{ $order->payment_method ?? 'Pending' }}
                    </div>
                    @if($order->status_online_pay)
                        <span class="badge {{ $order->status_online_pay == 'paid' ? 'badge-success' : 'badge-danger' }} mt-1">
                            {{ ucfirst($order->status_online_pay) }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
                <div class="metric-card">
                    <div class="metric-label"><i class="fa fa-table text-primary mr-1"></i> Table / Location</div>
                    <div class="metric-value" style="font-size: 1.15rem;">
                        @if($order->order_type === 'instore')
                            {{ $order->restaurantTable ? $order->restaurantTable->name : 'N/A Table' }}
                        @else
                            {{ ucfirst($order->order_type) }}
                        @endif
                    </div>
                    @if($order->waiter)
                        <small class="text-muted d-block mt-1">Waiter: {{ $order->waiter->name }}</small>
                    @endif
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="metric-card">
                    <div class="metric-label"><i class="fa fa-user text-warning mr-1"></i> Customer</div>
                    <div class="metric-value text-truncate" style="font-size: 1.15rem;" title="{{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Guest Customer' }}">
                        {{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Guest Customer' }}
                    </div>
                    @if($order->customer && $order->customer->phone)
                        <small class="text-muted d-block mt-1"><i class="fa fa-phone mr-1"></i> {{ $order->customer->phone }}</small>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items Table Card -->
        <div class="detail-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title"><i class="fa fa-shopping-basket text-primary mr-2"></i> Order Items</h5>
                <span class="badge badge-light border font-weight-bold">{{ $order->orderItems->sum('quantity') }} Total Items</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table detail-table mb-0">
                        <thead>
                            <tr>
                                <th>Item Name</th>
                                <th>Unit Price</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->orderItems as $item)
                                @php
                                    $unitPrice = $item->quantity > 0 ? $item->subtotal / $item->quantity : 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="font-weight-bold text-dark">{{ $item->menu_name }}</div>
                                        @if($item->item_note)
                                            <small class="text-muted d-block mt-1" style="font-size: 0.82rem;">
                                                <i class="fa fa-comment-o text-info mr-1"></i> Note: {{ $item->item_note }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($unitPrice, 2) }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-light border font-weight-bold px-3 py-1">x {{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-right font-weight-bold text-dark">{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach

                            <tr class="table-summary-row">
                                <td colspan="3" class="text-right font-weight-bold">Subtotal:</td>
                                <td class="text-right font-weight-bold">{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->total_price + ($order->discount_amount ?? 0), 2) }}</td>
                            </tr>

                            @if(($order->discount_amount ?? 0) > 0)
                                <tr class="table-summary-row text-danger">
                                    <td colspan="3" class="text-right font-weight-bold">Discount:</td>
                                    <td class="text-right font-weight-bold">-{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->discount_amount, 2) }}</td>
                                </tr>
                            @endif

                            @if(!is_null($order->delivery_fee))
                                <tr class="table-summary-row">
                                    <td colspan="3" class="text-right font-weight-bold">Delivery Fee:</td>
                                    <td class="text-right font-weight-bold">{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->delivery_fee, 2) }}</td>
                                </tr>
                            @endif

                            <tr class="table-summary-row" style="border-top: 2px solid #cbd5e1;">
                                <td colspan="3" class="text-right font-weight-bold" style="font-size: 1.1rem; color: #0f172a;">GRAND TOTAL:</td>
                                <td class="text-right font-weight-bold text-success" style="font-size: 1.25rem;">{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($order->total_price + ($order->delivery_fee ?? 0), 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($order->additional_info)
                    <div class="p-3 bg-light border-top">
                        <div class="font-weight-bold text-dark mb-1" style="font-size: 0.88rem;">
                            <i class="fa fa-sticky-note text-warning mr-1"></i> Order Notes / Instructions:
                        </div>
                        <div class="text-muted" style="font-size: 0.9rem;">
                            {{ $order->additional_info }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Service & Customer Information Grid -->
        <div class="row">
            <!-- Service Information Card -->
            <div class="col-lg-6 mb-4">
                <div class="detail-card h-100 mb-0">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fa fa-info-circle text-info mr-2"></i> Service Information</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table detail-table mb-0">
                            <tbody>
                                <tr>
                                    <td style="width: 40%;" class="font-weight-bold text-muted">Order Number</td>
                                    <td class="font-weight-bold text-dark">#{{ $order->order_no }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Order Type</td>
                                    <td><span class="badge badge-light border font-weight-bold">{{ ucfirst($order->order_type) }}</span></td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Created By</td>
                                    <td>{{ $order->createdByUser ? $order->createdByUser->first_name . ' ' . $order->createdByUser->last_name : 'System / Self-Order' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Last Updated By</td>
                                    <td>{{ $order->updatedByUser ? $order->updatedByUser->first_name . ' ' . $order->updatedByUser->last_name : 'N/A' }}</td>
                                </tr>
                                @if($order->waiter)
                                    <tr>
                                        <td class="font-weight-bold text-muted">Assigned Waiter</td>
                                        <td class="font-weight-bold text-dark">{{ $order->waiter->name }}</td>
                                    </tr>
                                @endif
                                @if($order->restaurantTable)
                                    <tr>
                                        <td class="font-weight-bold text-muted">Table Name</td>
                                        <td class="font-weight-bold text-dark">{{ $order->restaurantTable->name }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="font-weight-bold text-muted">Created Date</td>
                                    <td>{{ $order->created_at->format('g:i A - j M, Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Last Updated Date</td>
                                    <td>{{ $order->updated_at->format('g:i A - j M, Y') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Customer & Delivery Information Card -->
            <div class="col-lg-6 mb-4">
                <div class="detail-card h-100 mb-0">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fa fa-user-circle text-success mr-2"></i> Customer & Delivery Details</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table detail-table mb-0">
                            <tbody>
                                <tr>
                                    <td style="width: 40%;" class="font-weight-bold text-muted">Customer Name</td>
                                    <td class="font-weight-bold text-dark">
                                        {{ $order->customer ? $order->customer->first_name . ' ' . $order->customer->last_name : 'Guest Customer' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Email Address</td>
                                    <td>{{ $order->customer->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold text-muted">Phone Number</td>
                                    <td>{{ $order->customer->phone_number ?? $order->customer->phone ?? 'N/A' }}</td>
                                </tr>

                                @if($order->order_type === 'pickup' && $order->pickupAddress)
                                    <tr>
                                        <td class="font-weight-bold text-muted">Pickup Location</td>
                                        <td>{{ $order->pickupAddress->full_address ?? 'Store Location' }}</td>
                                    </tr>
                                @elseif($order->deliveryAddressWithTrashed)
                                    <tr>
                                        <td class="font-weight-bold text-muted">Delivery Address</td>
                                        <td>{{ $order->deliveryAddressWithTrashed->full_address ?? 'N/A' }}</td>
                                    </tr>
                                    @if(!is_null($order->delivery_distance))
                                        <tr>
                                            <td class="font-weight-bold text-muted">Delivery Distance</td>
                                            <td>{{ $order->delivery_distance }} miles</td>
                                        </tr>
                                    @endif
                                @else
                                    <tr>
                                        <td class="font-weight-bold text-muted">Fulfillment</td>
                                        <td class="text-muted">In-Store Dining / Counter Service</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if (!is_null($order->session_id))
            <div class="alert alert-success alert-dismissible fade show border-0 mb-3" role="alert" id="copy-alert" style="display: none; border-radius: 10px;">
                <strong><i class="fa fa-check-circle mr-1"></i> Payment Session ID copied to clipboard!</strong>
            </div>

            <div class="detail-card mb-4">
                <div class="card-body">
                    <label class="font-weight-bold mb-2 text-dark">Online Payment Session ID</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="session_id" value="{{ $order->session_id }}" readonly style="border-radius: 8px 0 0 8px;">
                        <button id="copy_session_id" class="btn btn-secondary font-weight-bold px-3" type="button" style="border-radius: 0 8px 8px 0;">
                            <i class="fa fa-copy mr-1"></i> Copy
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Update Order Modal -->
        <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0" style="border-radius: 14px;">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold" id="updateModalLabel">Update Order Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                        @csrf
                        <div class="modal-body py-4">
                            <div class="form-group mb-0">
                                <label for="orderStatus" class="font-weight-bold mb-2">Select New Status</label>
                                <select class="form-control" id="orderStatus" name="status" style="height: 44px; border-radius: 8px;">
                                    <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0 justify-content-center pb-4">
                            <button type="button" class="btn btn-light px-4 mr-2" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success px-4 font-weight-bold">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if ($loggedInUser->role == "global_admin")
            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0" style="border-radius: 14px;">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title font-weight-bold" id="deleteModalLabel">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            Are you sure you want to delete Order #{{ $order->order_no }}? This action cannot be undone.
                        </div>
                        <div class="modal-footer justify-content-center border-0 pb-4">
                            <button type="button" class="btn btn-secondary px-4 mr-2" data-bs-dismiss="modal">Cancel</button>
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
    @include('partials.admin.footer')
</div>
@endsection