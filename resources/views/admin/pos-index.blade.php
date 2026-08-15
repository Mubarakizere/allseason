@extends('layouts.admin')

@section('title', 'Point of Sale (POS) — All The Season Garden')

@push('styles')
<style>
    /* ── POS Clean Layout ── */
    .pos-wrap {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }
    
    /* Category Navigation */
    .pos-categories {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 4px;
        margin-bottom: 16px;
        scrollbar-width: none;
    }
    .pos-categories::-webkit-scrollbar {
        display: none;
    }
    .cat-btn {
        padding: 8px 18px;
        border-radius: 20px;
        background: #ffffff;
        color: #4b5563;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.15s ease;
        flex-shrink: 0;
    }
    .cat-btn:hover {
        background: #f9fafb;
        color: #111827;
    }
    .cat-btn.active {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
    }

    /* Top Search & Actions */
    .pos-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .pos-search-wrap {
        position: relative;
        flex: 1;
        min-width: 240px;
    }
    .pos-search-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 14px;
    }
    .pos-search-input {
        width: 100%;
        height: 42px;
        padding: 0 16px 0 38px;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        font-size: 13.5px;
        color: #111827;
        outline: none;
        transition: border-color 0.15s ease;
    }
    .pos-search-input:focus {
        border-color: #dc2626;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08);
    }

    /* Menu Cards Grid */
    .pos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 16px;
    }
    .pos-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        transition: border-color 0.15s ease, box-shadow 0.15s ease, transform 0.1s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    .pos-card:hover {
        border-color: #d1d5db;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }
    .pos-card:active {
        transform: scale(0.98);
    }
    .pos-card.in-cart {
        border-color: #dc2626;
        box-shadow: 0 0 0 2px rgba(220, 38, 38, 0.15);
    }
    .pos-badge {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #dc2626;
        color: #ffffff;
        font-size: 11px;
        font-weight: 700;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        border: 2px solid #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }
    .pos-img {
        width: 100%;
        height: 125px;
        object-fit: cover;
        background: #f3f4f6;
    }
    .pos-card-body {
        padding: 12px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .pos-card-title {
        font-size: 13.5px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 6px;
        line-height: 1.35;
    }
    .pos-card-price {
        font-size: 14px;
        font-weight: 700;
        color: #dc2626;
        margin-top: auto;
    }

    /* Cart Sidebar */
    .cart-box {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 20px;
        position: sticky;
        top: 16px;
        display: flex;
        flex-direction: column;
        max-height: calc(100vh - 40px);
    }
    .cart-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding-bottom: 14px;
        border-bottom: 1px solid #f3f4f6;
        margin-bottom: 14px;
    }
    .cart-top h3 {
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .cart-body {
        flex: 1;
        overflow-y: auto;
        padding-right: 4px;
    }
    .cart-body::-webkit-scrollbar {
        width: 4px;
    }
    .cart-body::-webkit-scrollbar-thumb {
        background: #e5e7eb;
        border-radius: 4px;
    }

    /* Cart Item Row */
    .cart-item-row {
        padding: 10px 0;
        border-bottom: 1px solid #f9fafb;
    }
    .cart-item-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
    }
    .cart-item-name {
        font-size: 13px;
        font-weight: 600;
        color: #111827;
    }
    .cart-item-price {
        font-size: 12.5px;
        color: #6b7280;
    }
    .cart-item-ctrls {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .qty-stepper {
        display: inline-flex;
        align-items: center;
        background: #f3f4f6;
        border-radius: 6px;
        padding: 2px;
    }
    .qty-btn {
        width: 26px;
        height: 26px;
        border-radius: 4px;
        border: none;
        background: #ffffff;
        color: #374151;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .qty-btn:active {
        background: #e5e7eb;
    }
    .qty-val {
        padding: 0 10px;
        font-size: 13px;
        font-weight: 700;
        color: #111827;
    }
    .cart-remove-btn {
        background: transparent;
        border: none;
        color: #9ca3af;
        font-size: 13px;
        cursor: pointer;
        padding: 4px 6px;
        transition: color 0.15s;
    }
    .cart-remove-btn:hover {
        color: #ef4444;
    }

    /* Cart Summary */
    .cart-summary-box {
        background: #f9fafb;
        border: 1px solid #f3f4f6;
        border-radius: 8px;
        padding: 14px;
        margin-top: 14px;
    }
    .cart-summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: #4b5563;
        margin-bottom: 6px;
    }
    .cart-total-row {
        display: flex;
        justify-content: space-between;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
        border-top: 1px dashed #e5e7eb;
        padding-top: 8px;
        margin-top: 8px;
    }

    /* Form Selects */
    .pos-select {
        width: 100%;
        height: 40px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
        background: #ffffff;
        padding: 0 12px;
        font-size: 13px;
        color: #111827;
        margin-bottom: 10px;
        outline: none;
    }
    .pos-select:focus {
        border-color: #dc2626;
    }

    /* Quick Cash Buttons */
    .cash-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 4px;
        margin-bottom: 8px;
    }
    .cash-btn {
        padding: 5px 2px;
        font-size: 11px;
        font-weight: 600;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 4px;
        color: #374151;
        cursor: pointer;
        text-align: center;
    }
    .cash-btn:hover {
        background: #f3f4f6;
    }

    .btn-complete-order {
        width: 100%;
        height: 46px;
        background: #dc2626;
        color: #ffffff;
        font-weight: 700;
        font-size: 14px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin-top: 12px;
        transition: background 0.15s ease;
    }
    .btn-complete-order:hover {
        background: #b91c1c;
    }

    .btn-clear-order {
        width: 100%;
        padding: 8px;
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fee2e2;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        margin-bottom: 10px;
    }
    .btn-clear-order:hover {
        background: #fee2e2;
    }

    /* Mobile Floating Bar */
    .mobile-pos-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #0f1117;
        color: #ffffff;
        padding: 12px 20px;
        z-index: 1040;
        display: none;
        align-items: center;
        justify-content: space-between;
    }
    @media (max-width: 991px) {
        .mobile-pos-bar { display: flex; }
        .cart-box { position: relative; top: 0; max-height: none; margin-top: 24px; }
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>  
$(document).ready(function () {
    var rawSubtotal = 0;
    var currencySymbol = '{!! html_entity_decode($site_settings->currency_symbol ?? "RWF") !!} ';

    // Mobile view order scroll
    $('#mobile-view-order-btn').click(function() {
        $('html, body').animate({
            scrollTop: $('#cart-section').offset().top - 60
        }, 300);
    });

    // Search and Category Filter
    function filterMenu() {
        var catId = $('.cat-btn.active').data('id');
        var query = $('#menu-search').val().toLowerCase();

        $('.pos-card-item').each(function() {
            var itemCat = $(this).data('category');
            var itemName = $(this).find('.pos-card-title').text().toLowerCase();

            var catMatch = (catId === 'all' || itemCat == catId);
            var searchMatch = itemName.includes(query);

            if (catMatch && searchMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $('.cat-btn').click(function() {
        $('.cat-btn').removeClass('active');
        $(this).addClass('active');
        filterMenu();
    });

    $('#menu-search').on('keyup', filterMenu);

    // Cart Operations
    function addToCart(id, name, price) {
        $.post('{{ route('admin.cart.add') }}', { _token: "{{ csrf_token() }}", cartkey: 'admin', id: id, name: name, price: price }, function (data) {
            if (data.success) updateCartUI(data.cart);
        });
    }

    function removeFromCart(id) {
        $.post('{{ route('admin.cart.remove') }}', { _token: "{{ csrf_token() }}", cartkey: 'admin', id: id }, function (data) {
            if (data.success) updateCartUI(data.cart);
        });
    }

    function updateCartQuantity(id, quantity) {
        if (quantity <= 0) {
            removeFromCart(id);
            return;
        }
        $.post('{{ route('admin.cart.update')  }}', { _token: "{{ csrf_token() }}", cartkey: 'admin', id: id, quantity: quantity }, function (data) {
            if (data.success) updateCartUI(data.cart);
        });
    }

    $('#clear-cart').click(function (e) {
        e.preventDefault();
        $.post('{{ route('admin.cart.clear') }}', { _token: "{{ csrf_token() }}", cartkey: 'admin' }, function (data) {
            if (data.success) updateCartUI([]);
        });
    });

    function calculateTotals() {
        var discountVal = parseFloat($('#discount_value').val()) || 0;
        var discountType = $('#discount_type').val();
        var discountAmount = 0;

        if (discountType === 'percent') {
            discountAmount = (rawSubtotal * Math.min(100, Math.max(0, discountVal))) / 100;
        } else {
            discountAmount = Math.min(rawSubtotal, Math.max(0, discountVal));
        }

        var netTotal = Math.max(0, rawSubtotal - discountAmount);

        if (discountAmount > 0) {
            $('#discount-row').show();
            $('#cart-discount').text('-' + currencySymbol + discountAmount.toFixed(2));
        } else {
            $('#discount-row').hide();
        }

        var totalText = currencySymbol + netTotal.toFixed(2);

        $('#cart-subtotal').text(currencySymbol + rawSubtotal.toFixed(2));
        $('#cart-total').text(totalText);
        $('#mobile-cart-total').text(totalText);
        $('#total').val(netTotal.toFixed(2));
        $('#discount_amount').val(discountAmount.toFixed(2));

        calculateCashChange();
        checkCheckoutBtn();
    }

    function updateCartUI(cart) {
        var cartContainer = $('#cart-container');
        cartContainer.empty(); 

        rawSubtotal = 0;
        var totalItemsCount = 0;
        var cartQtyMap = {};

        $.each(cart, function (index, item) {
            var qty = parseInt(item.quantity) || 1;
            totalItemsCount += qty;
            cartQtyMap[item.id] = qty;
            var itemSubtotal = qty * parseFloat(item.price);
            rawSubtotal += itemSubtotal;

            cartContainer.append(`
                <div class="cart-item-row">
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">${currencySymbol}${(parseFloat(item.price)).toFixed(2)}</div>
                    </div>
                    <div class="cart-item-ctrls">
                        <div class="qty-stepper">
                            <button type="button" class="qty-btn btn-qty-minus" data-id="${item.id}" data-qty="${qty}">-</button>
                            <span class="qty-val">${qty}</span>
                            <button type="button" class="qty-btn btn-qty-plus" data-id="${item.id}" data-qty="${qty}">+</button>
                        </div>
                        <button type="button" class="cart-remove-btn" data-id="${item.id}" title="Remove item"><i class="fas fa-trash-alt"></i></button>
                    </div>
                    <div class="mt-2">
                        <input type="text" class="pos-select item-note-input" data-id="${item.id}" placeholder="Note (e.g. No chilli)" value="${item.item_note || ''}" style="height: 30px; font-size: 11.5px; margin: 0;">
                    </div>
                </div>
            `);
        });

        // Update item badges on cards
        $('.pos-badge').hide().text('0');
        $('.pos-card').removeClass('in-cart');

        $('.pos-card-item').each(function() {
            var card = $(this).find('.pos-card');
            var id = card.data('id');
            if (cartQtyMap[id] && cartQtyMap[id] > 0) {
                $('#card-badge-' + id).text(cartQtyMap[id]).show();
                card.addClass('in-cart');
            }
        });

        $('#mobile-cart-count').text(totalItemsCount + (totalItemsCount === 1 ? ' item' : ' items'));

        if (rawSubtotal > 0) {
            $('#clear-cart').show();  
            $('#checkout-form-container').show();
        } else {
            $('#clear-cart').hide();  
            $('#checkout-form-container').hide();
            $('#checkout-btn').hide();         
        }

        calculateTotals();
        
        $('.btn-qty-minus').click(function () {
            var id = $(this).data('id');
            var currentQty = parseInt($(this).data('qty')) || 1;
            updateCartQuantity(id, currentQty - 1);
        });

        $('.btn-qty-plus').click(function () {
            var id = $(this).data('id');
            var currentQty = parseInt($(this).data('qty')) || 1;
            updateCartQuantity(id, currentQty + 1);
        });

        $('.cart-remove-btn').click(function () {
            var id = $(this).data('id');
            removeFromCart(id);
        });

        $('.item-note-input').change(function () {
            var id = $(this).data('id');
            var itemNote = $(this).val();
            $.post('{{ route('admin.cart.update-note') }}', { _token: "{{ csrf_token() }}", cartkey: 'admin', id: id, item_note: itemNote });
        });
    }

    $('.add-to-cart-trigger').click(function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var price = $(this).data('price');
        addToCart(id, name, price);
    });

    $.get('{{ route('admin.cart.view') }}', { cartkey: 'admin' }, function (data) {
        updateCartUI(data.cart);
    });

    $('#discount_value, #discount_type').on('input change', calculateTotals);

    function calculateCashChange() {
        var method = $('#payment_method').val();
        if (method === 'Cash') {
            $('#cash-calculator-box').show();
            var total = parseFloat($('#total').val()) || 0;
            var tendered = parseFloat($('#amount_tendered').val()) || 0;
            var change = Math.max(0, tendered - total);
            $('#change_due_display').text(currencySymbol + change.toFixed(2));
            $('#change_due').val(change.toFixed(2));
        } else {
            $('#cash-calculator-box').hide();
            $('#amount_tendered').val('');
            $('#change_due').val(0);
        }
    }

    $('#payment_method, #amount_tendered').on('change input', function() {
        calculateCashChange();
        checkCheckoutBtn();
    });

    $('.cash-btn').click(function() {
        var val = $(this).data('val');
        var total = parseFloat($('#total').val()) || 0;
        if (val === 'exact') {
            $('#amount_tendered').val(total.toFixed(2));
        } else {
            $('#amount_tendered').val(parseFloat(val).toFixed(2));
        }
        calculateCashChange();
    });

    function checkCheckoutBtn() {
        var total = parseFloat($('#total').val()) || 0;
        if ($('#waiter_id').val() !== "" && $('#restaurant_table_id').val() !== "" && total >= 0 && rawSubtotal > 0) {
            $('#checkout-btn').show();   
        } else {
            $('#checkout-btn').hide();   
        }
    }

    $('#waiter_id, #restaurant_table_id').on('change', checkCheckoutBtn);

    $('#restaurant_table_id').on('change', function() {
        var tableId = $(this).val();
        $('#open-order-banner').hide().empty();
        
        if (tableId) {
            $.get("{{ url('admin/orders/table') }}/" + tableId, function(response) {
                if (response.success && response.order) {
                    var itemsCount = response.order.order_items ? response.order.order_items.length : 0;
                    $('#open-order-banner').html('<strong><i class="fas fa-info-circle"></i> Adding to open order #' + response.order.order_no + '</strong><br><small>' + itemsCount + ' item(s) already ordered.</small>').show();
                    
                    if (response.order.waiter_id && !$('#waiter_id').val()) {
                        $('#waiter_id').val(response.order.waiter_id).trigger('change');
                    }
                }
            });
        }
    });

    $('#checkout-btn').click(function(e) {
        e.preventDefault();
        $('#confirmationModal').modal('show');
    });

    $('#confirmSubmit').click(function() {
        var formData = $('#checkout-form').serialize();
        $('#confirmSubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');

        $.ajax({
            url: "{{ route('admin.order.store') }}",
            type: "POST",
            data: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                $('#confirmationModal').modal('hide');
                $('#confirmSubmit').prop('disabled', false).text('Confirm Order');

                if (response.success) {
                    if (response.customer_receipt_url) {
                        window.open(response.customer_receipt_url, '_blank', 'width=400,height=600');
                    }
                    if (response.kitchen_ticket_url) {
                        setTimeout(function() {
                            window.open(response.kitchen_ticket_url, '_blank', 'width=400,height=600');
                        }, 400);
                    }

                    $('#pos-alert-container').html(
                        '<div class="alert alert-success alert-dismissible fade show mb-3 border-0" role="alert" style="border-radius: 8px;">' +
                        '<strong><i class="fas fa-check-circle"></i> ' + response.message + '</strong>' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>'
                    );

                    updateCartUI([]);
                    $('#checkout-form')[0].reset();
                    $('#discount_value').val('');
                    $('#open-order-banner').hide();
                    calculateCashChange();
                    checkCheckoutBtn();
                }
            },
            error: function(xhr) {
                $('#confirmSubmit').prop('disabled', false).text('Confirm Order');
                var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Error creating order.';
                alert('Error: ' + msg);
            }
        });
    });
});
</script>
@endpush

@section('content')
<div class="content-wrapper pos-wrap">
    
    <div id="pos-alert-container">
        @include('partials.message-bag')
    </div>

    <div class="row">
        {{-- Left: Menu catalog --}}
        <div class="col-xl-8 col-lg-7">
            
            <div class="pos-topbar">
                <div class="pos-categories">
                    <button type="button" class="cat-btn active" data-id="all">All Items</button>
                    @foreach($categories as $category)
                        <button type="button" class="cat-btn" data-id="{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>
                <div class="pos-search-wrap">
                    <i class="fas fa-search"></i>
                    <input type="text" id="menu-search" class="pos-search-input" placeholder="Search dishes, drinks...">
                </div>
            </div>

            <div class="pos-grid">
                @forelse ($menus as $menu)
                    <div class="pos-card-item" data-category="{{ $menu->category_id }}">
                        <div class="pos-card add-to-cart-trigger" 
                             data-id="{{ $menu->id }}"
                             data-name="{{ $menu->name }}"
                             data-price="{{ $menu->price }}">
                            <div class="pos-badge" id="card-badge-{{ $menu->id }}" style="display: none;">0</div>
                            <img src="{{ $menu->image_url }}" 
                                 class="pos-img" 
                                 alt="{{ $menu->name }}"
                                 onerror="this.onerror=null;this.src='/assets/images/placeholder.jpg';">
                            <div class="pos-card-body">
                                <div class="pos-card-title">{{ $menu->name }}</div>
                                <div class="pos-card-price">{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($menu->price, 2) }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-5">
                        <p class="mb-0">No menu items available.</p>
                    </div>
                @endforelse
            </div>

        </div>

        {{-- Right: Cart Panel --}}
        <div class="col-xl-4 col-lg-5" id="cart-section">
            <div class="cart-box">
                <div class="cart-top">
                    <h3>Current Order</h3>
                    <i class="fas fa-shopping-bag text-muted"></i>
                </div>

                <div class="cart-body">
                    <div id="open-order-banner" style="display: none;" class="alert alert-info py-2 px-3 mb-3 border-0" style="border-radius: 6px; font-size: 12px;"></div>

                    <div id="cart-container">
                        {{-- Populated via JS --}}
                    </div>

                    <button id="clear-cart" style="display: none;" class="btn-clear-order">
                        <i class="fas fa-trash-alt me-1"></i> Clear Order
                    </button>

                    <div class="cart-summary-box">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="m-0 font-weight-bold" style="font-size: 12px;">Discount</label>
                            <div class="d-flex align-items-center gap-1">
                                <select id="discount_type" class="pos-select" style="width: 60px; height: 28px; padding: 2px 4px; margin: 0; font-size: 11.5px;">
                                    <option value="amount">{!! html_entity_decode($site_settings->currency_symbol) !!}</option>
                                    <option value="percent">%</option>
                                </select>
                                <input type="number" id="discount_value" class="pos-select" placeholder="0" min="0" step="0.01" style="width: 75px; height: 28px; margin: 0; font-size: 11.5px;">
                            </div>
                        </div>
                        <div class="cart-summary-row">
                            <span>Subtotal</span>
                            <span id="cart-subtotal">{!! html_entity_decode($site_settings->currency_symbol) !!}0.00</span>
                        </div>
                        <div id="discount-row" class="cart-summary-row text-danger" style="display: none;">
                            <span>Discount</span>
                            <span id="cart-discount">-{!! html_entity_decode($site_settings->currency_symbol) !!}0.00</span>
                        </div>
                        <div class="cart-total-row">
                            <span>Total</span>
                            <span id="cart-total">{!! html_entity_decode($site_settings->currency_symbol) !!}0.00</span>
                        </div>
                    </div>

                    <div id="checkout-form-container" style="display: none; margin-top: 14px;">
                        <form id="checkout-form" method="POST" action="{{ route('admin.order.store') }}">
                            <input type="hidden" id="total" value="0">
                            <input type="hidden" id="discount_amount" name="discount_amount" value="0">
                            <input type="hidden" name="cartkey" value="admin">
                            @csrf
                            
                            <select class="pos-select" id="waiter_id" name="waiter_id" required>
                                <option value="">Select Waiter *</option>
                                @foreach($waiters as $waiter)
                                    <option value="{{ $waiter->id }}">{{ $waiter->name }}</option>
                                @endforeach
                            </select>
                            
                            <select class="pos-select" id="restaurant_table_id" name="restaurant_table_id" required>
                                <option value="">Select Table *</option>
                                @foreach($tables as $table)
                                    @php
                                        $isOccupied = $table->orders && $table->orders->count() > 0;
                                        $openOrder = $isOccupied ? $table->orders->first() : null;
                                    @endphp
                                    <option value="{{ $table->id }}">
                                        {{ $isOccupied ? '🔴' : '🟢' }} {{ $table->name }} {{ $isOccupied ? '(Occupied - #' . $openOrder->order_no . ')' : '(Available)' }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="mb-2">
                                <label class="fw-bold mb-1" style="font-size: 12px; color: #374151;">Payment Method</label>
                                <select class="pos-select" id="payment_method" name="payment_method" required>
                                    <option value="Cash">Cash</option>
                                    <option value="Card">Card</option>
                                    <option value="Mobile Pay">Mobile Pay</option>
                                    <option value="Pending">Pay Later / Pending</option>
                                </select>
                            </div>

                            <div id="cash-calculator-box" class="p-2 border rounded bg-white mb-2" style="font-size: 12px;">
                                <label class="fw-bold mb-1">Cash Tendered</label>
                                <div class="cash-grid">
                                    <button type="button" class="cash-btn" data-val="exact">Exact</button>
                                    <button type="button" class="cash-btn" data-val="1000">1,000</button>
                                    <button type="button" class="cash-btn" data-val="2000">2,000</button>
                                    <button type="button" class="cash-btn" data-val="5000">5,000</button>
                                    <button type="button" class="cash-btn" data-val="10000">10,000</button>
                                    <button type="button" class="cash-btn" data-val="20000">20,000</button>
                                    <button type="button" class="cash-btn" data-val="50000">50,000</button>
                                </div>
                                <input type="number" class="pos-select mb-1" id="amount_tendered" name="amount_tendered" step="0.01" placeholder="Tendered amount..." style="height: 34px;">
                                <div class="d-flex justify-content-between fw-bold text-success mt-1">
                                    <span>Change Due:</span>
                                    <span id="change_due_display">{!! html_entity_decode($site_settings->currency_symbol) !!}0.00</span>
                                </div>
                                <input type="hidden" id="change_due" name="change_due" value="0">
                            </div>

                            <textarea class="pos-select" id="additional_info" name="additional_info" rows="2" placeholder="Special instructions or notes..." style="height: auto; padding: 8px 12px;"></textarea>
                        </form>
                    </div>
                </div>

                <button type="button" style="display:none;" id="checkout-btn" class="btn-complete-order">
                    Complete Order
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Floating Bar --}}
    <div class="mobile-pos-bar">
        <div>
            <div style="font-size: 12px; opacity: 0.8;" id="mobile-cart-count">0 items</div>
            <div style="font-size: 16px; font-weight: 700;" id="mobile-cart-total">{!! html_entity_decode($site_settings->currency_symbol) !!}0.00</div>
        </div>
        <button type="button" id="mobile-view-order-btn" class="btn btn-danger btn-sm font-weight-bold px-3" style="border-radius: 20px;">
            View Order <i class="fas fa-arrow-down ms-1"></i>
        </button>
    </div>

    {{-- Confirmation Modal --}}
    <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0" style="border-radius: 10px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title font-weight-bold">Confirm Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <h4 class="text-dark mb-2">Send this order to the kitchen?</h4>
                    <p class="text-muted mb-0" style="font-size: 13px;">The order will be created and KOT / receipt will be generated.</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                    <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmSubmit">Confirm Order</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection