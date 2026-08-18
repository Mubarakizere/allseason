@extends('layouts.admin')

@section('title', 'Manage Orders — All The Season Garden')

@push('styles')
<link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

<style>
    .orders-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Page Header */
    .orders-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    .orders-title-group h1 {
        font-size: 22px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px;
        letter-spacing: -0.02em;
    }
    .orders-title-group p {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    .orders-btn-pos {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        background: #dc2626;
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none !important;
        transition: background 0.15s ease;
    }
    .orders-btn-pos:hover {
        background: #b91c1c;
    }

    /* Card Container */
    .orders-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-top: 24px;
        overflow: hidden;
    }
    .orders-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    .orders-card-title {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }
    .filter-tab-link {
        padding: 5px 12px;
        border-radius: 20px;
        background: #f9fafb;
        color: #4b5563 !important;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none !important;
        border: 1px solid #e5e7eb;
        transition: all 0.15s ease;
    }
    .filter-tab-link:hover {
        background: #f3f4f6;
        color: #111827 !important;
    }
    .filter-tab-link.active {
        background: #111827;
        color: #ffffff !important;
        border-color: #111827;
    }

    /* DataTables Custom Styling */
    .dataTables_wrapper {
        padding: 0;
    }
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_filter {
        padding: 14px 20px 10px;
        font-size: 13px;
        color: #6b7280;
    }
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 5px 12px;
        font-size: 13px;
        outline: none;
        margin-left: 8px;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #dc2626;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 13px;
        outline: none;
    }
    table.dataTable {
        border-collapse: collapse !important;
        width: 100% !important;
        border: none !important;
        margin: 0 !important;
    }
    table.dataTable<thead>th {
        background: #f9fafb;
        border-bottom: 1px solid #e5e7eb !important;
        border-top: none !important;
        color: #374151;
        font-weight: 600;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 10px 18px !important;
    }
    table.dataTable<tbody>td {
        padding: 12px 18px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6 !important;
        border-top: none !important;
        color: #111827;
        font-size: 13px;
    }
    table.dataTable<tbody>tr:hover {
        background-color: #f9fafb !important;
    }
    .dataTables_info,
    .dataTables_paginate {
        padding: 12px 20px !important;
        font-size: 12.5px;
        color: #6b7280;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 6px !important;
        border: 1px solid #e5e7eb !important;
        background: #ffffff !important;
        color: #374151 !important;
        font-size: 12px !important;
        padding: 3px 9px !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background: #111827 !important;
        color: #ffffff !important;
        border-color: #111827 !important;
        box-shadow: none !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f3f4f6 !important;
        color: #111827 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.orders.index', ['filter' => $filter]) }}",
            columns: [
                { data: 'order_no', name: 'order_no', width: '12%' },
                { data: 'details', name: 'details', width: '22%' },
                { data: 'items_preview', name: 'items_preview', width: '12%' },
                { data: 'total_price', name: 'total_price', width: '14%' },
                { data: 'payment', name: 'payment', width: '14%' },
                { data: 'status', name: 'status', width: '12%' },
                { data: 'action', name: 'action', orderable: false, searchable: false, width: '14%' },
            ]
        });
    });

    $(document).ready(function() {
        var currencySymbol = "{!! $site_settings->currency_symbol ?? 'RWF' !!}";

        $(document).on('click', '.complete-order-btn', function() {
            var id = $(this).data('id');
            var orderNo = $(this).data('order-no');
            var total = parseFloat($(this).data('total')) || 0;
            var payment = $(this).data('payment') || 'Cash';
            var actionUrl = "{{ route('admin.orders.update', ':id') }}".replace(':id', id);

            $('#completeOrderForm').attr('action', actionUrl);
            $('#completeOrderNoLabel').text('#' + orderNo);
            $('#completeOrderTotalLabel').text(currencySymbol + total.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
            $('#completeOrderTotal').val(total);

            if (payment.indexOf('Split') !== -1 || payment.indexOf('Partial') !== -1) {
                $('#completePaymentMethod').val('Split');
            } else if (payment.toLowerCase().indexOf('momo') !== -1 || payment.toLowerCase().indexOf('mobile') !== -1) {
                $('#completePaymentMethod').val('MoMo Pay');
            } else if (payment.toLowerCase().indexOf('bank') !== -1 || payment.toLowerCase().indexOf('card') !== -1) {
                $('#completePaymentMethod').val('Bank / Card');
            } else {
                $('#completePaymentMethod').val('Cash');
            }

            toggleSplitPanel();
        });

        function toggleSplitPanel() {
            var val = $('#completePaymentMethod').val();
            if (val === 'Split') {
                $('#splitPaymentPanel').slideDown(150);
                calculateSplitAmounts();
            } else {
                $('#splitPaymentPanel').slideUp(150);
            }
        }

        function calculateSplitAmounts() {
            var totalOrder = parseFloat($('#completeOrderTotal').val()) || 0;
            var cash = parseFloat($('#split_cash').val()) || 0;
            var momo = parseFloat($('#split_momo').val()) || 0;
            var bank = parseFloat($('#split_bank').val()) || 0;

            var totalPaid = cash + momo + bank;
            var balance = totalPaid - totalOrder;

            $('#splitTotalPaidLabel').text(currencySymbol + totalPaid.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));

            if (balance >= 0) {
                $('#splitBalanceLabel').removeClass('text-danger').addClass('text-success').text('Change: ' + currencySymbol + balance.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
            } else {
                $('#splitBalanceLabel').removeClass('text-success').addClass('text-danger').text('Due: ' + currencySymbol + Math.abs(balance).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}));
            }
        }

        $('#completePaymentMethod').on('change', toggleSplitPanel);
        $(document).on('input change', '.split-calc-input', calculateSplitAmounts);

        @if ($loggedInUser->role == "global_admin")
            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);  
                var id = button.data('id');  
                var actionUrl = "{{ route('admin.orders.destroy', ':id') }}".replace(':id', id);
                $('#deleteForm').attr('action', actionUrl);
            });
        @endif

        // Auto-print polling every 10 seconds
        setInterval(function() {
            $.ajax({
                url: "{{ route('admin.orders.unprinted') }}",
                type: 'GET',
                success: function(response) {
                    if (response.success && response.order_id) {
                        let receiptUrl = "{{ url('admin/orders') }}/" + response.order_id + "/receipt?kitchen=1";
                        window.open(receiptUrl, '_blank', 'width=400,height=600');
                        
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
        }, 10000);
    });
</script>
@endpush

@section('content')
<div class="content-wrapper orders-wrap">
    
    @include('partials.message-bag')

    {{-- Page Header --}}
    <div class="orders-header">
        <div class="orders-title-group">
            <h1>Order Management</h1>
            <p>High-level overview of orders. Click <strong>View</strong> to inspect full details.</p>
        </div>
        <div>
            <a href="{{ route('admin.pos.index') }}" class="orders-btn-pos">
                <i class="fas fa-plus me-1"></i> New Order (POS)
            </a>
        </div>
    </div>

    {{-- Stats Row --}}
    @include('partials.order-stats')

    {{-- Orders Card & Table --}}
    <div class="orders-card">
        <div class="orders-card-header">
            <h3 class="orders-card-title">{{ ucfirst($filter ?: 'All') }} Orders</h3>
            <div class="filter-tabs">
                <a href="{{ route('admin.orders.index') }}" 
                   class="filter-tab-link {{ empty($filter) ? 'active' : '' }}">All</a>
                <a href="{{ route('admin.orders.index', ['filter' => 'pending']) }}" 
                   class="filter-tab-link {{ $filter == 'pending' ? 'active' : '' }}">Pending</a>
                <a href="{{ route('admin.orders.index', ['filter' => 'instore']) }}" 
                   class="filter-tab-link {{ $filter == 'instore' ? 'active' : '' }}">Dine-in</a>
                <a href="{{ route('admin.orders.index', ['filter' => 'delivery']) }}" 
                   class="filter-tab-link {{ $filter == 'delivery' ? 'active' : '' }}">Delivery</a>
                <a href="{{ route('admin.orders.index', ['filter' => 'completed']) }}" 
                   class="filter-tab-link {{ $filter == 'completed' ? 'active' : '' }}">Completed</a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table data-table" id="orders-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Details</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Complete Order & Payment Modal --}}
    <div class="modal fade" id="completeOrderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-check-circle text-success me-1"></i> Complete Order & Select Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="completeOrderForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="completed">
                    <input type="hidden" id="completeOrderTotal" value="0">
                    <div class="modal-body py-3">
                        <div class="p-3 bg-light rounded border mb-3 text-center">
                            <div class="text-muted small">Order Reference: <strong id="completeOrderNoLabel">#--</strong></div>
                            <div class="fs-5 font-weight-bold text-dark mt-1">Total Amount: <span id="completeOrderTotalLabel" class="text-success">{!! $site_settings->currency_symbol !!}0.00</span></div>
                        </div>

                        <div class="mb-3">
                            <label for="completePaymentMethod" class="fw-semibold mb-1" style="font-size: 13px;">Payment Method *</label>
                            <select class="form-select" id="completePaymentMethod" name="payment_method" required style="height: 42px; font-size: 13px;">
                                <option value="Cash">Cash</option>
                                <option value="MoMo Pay">MoMo Pay (Mobile Money)</option>
                                <option value="Bank / Card">Bank Transfer / Card</option>
                                <option value="Split">Split / Partial Payment (Multiple Methods)</option>
                            </select>
                        </div>

                        {{-- Split Payment Panel --}}
                        <div id="splitPaymentPanel" class="p-3 border rounded bg-white mb-3" style="display: none;">
                            <label class="fw-bold mb-2 text-dark" style="font-size: 12px;"><i class="fas fa-coins text-warning me-1"></i> Specify Amount Paid by Each Method:</label>
                            <div class="row g-2">
                                <div class="col-12 mb-1">
                                    <label class="small text-muted mb-0">Cash Paid ({!! $site_settings->currency_symbol !!}):</label>
                                    <input type="number" step="0.01" min="0" name="split_cash" id="split_cash" class="form-control form-control-sm split-calc-input" value="0.00">
                                </div>
                                <div class="col-12 mb-1">
                                    <label class="small text-muted mb-0">MoMo Pay Paid ({!! $site_settings->currency_symbol !!}):</label>
                                    <input type="number" step="0.01" min="0" name="split_momo" id="split_momo" class="form-control form-control-sm split-calc-input" value="0.00">
                                </div>
                                <div class="col-12 mb-1">
                                    <label class="small text-muted mb-0">Bank / Card Paid ({!! $site_settings->currency_symbol !!}):</label>
                                    <input type="number" step="0.01" min="0" name="split_bank" id="split_bank" class="form-control form-control-sm split-calc-input" value="0.00">
                                </div>
                            </div>
                            <div class="mt-2 pt-2 border-top d-flex justify-content-between align-items-center" style="font-size: 12px;">
                                <span>Total Paid: <strong id="splitTotalPaidLabel">{!! $site_settings->currency_symbol !!}0.00</strong></span>
                                <span>Balance / Change: <strong id="splitBalanceLabel" class="text-danger">{!! $site_settings->currency_symbol !!}0.00</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 justify-content-center">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success px-4 font-weight-bold"><i class="fas fa-check me-1"></i> Complete Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if ($loggedInUser->role == "global_admin")
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0" style="border-radius: 10px;">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title font-weight-bold">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4" style="font-size: 13.5px; color: #4b5563;">
                        Are you sure you want to delete this order? This action cannot be undone.
                    </div>
                    <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
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
@endsection