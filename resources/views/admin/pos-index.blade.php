@extends('layouts.admin')

@push('styles')
    <!-- base:css -->
    <link rel="stylesheet" href="/admin_resources/vendors/typicons.font/font/typicons.css">
    <link rel="stylesheet" href="/admin_resources/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/admin_resources/css/vertical-layout-light/style.css">

    <style>
        /* POS Premium Custom Styles */
        .pos-container {
            font-family: 'Inter', sans-serif;
            user-select: none;
        }

        .category-pills {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 5px;
            scrollbar-width: none; 
            -webkit-overflow-scrolling: touch;
        }
        .category-pills::-webkit-scrollbar {
            display: none; 
        }
        .category-pill {
            padding: 10px 22px;
            border-radius: 50px;
            background: #ffffff;
            color: #555;
            font-weight: 700;
            font-size: 0.95rem;
            border: 1px solid #e2e8f0;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.15s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            flex-shrink: 0;
            -webkit-tap-highlight-color: transparent;
        }
        .category-pill:hover {
            background: #f8f9fa;
        }
        .category-pill:active {
            transform: scale(0.95);
        }
        .category-pill.active {
            background: #28a745;
            color: white;
            border-color: #28a745;
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.35);
        }

        .pos-header-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .search-container {
            flex: 1;
            min-width: 220px;
            max-width: 350px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        .menu-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.04);
            border: 2px solid #f0f0f0;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
            display: flex;
            flex-direction: column;
            position: relative;
            height: 100%;
            -webkit-tap-highlight-color: transparent;
        }
        .menu-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }
        .menu-card:active {
            transform: scale(0.95) !important;
        }
        .menu-card.in-cart {
            border-color: #28a745;
            box-shadow: 0 6px 18px rgba(40, 167, 69, 0.2);
            background: #fdfefe;
        }
        .menu-card-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #28a745;
            color: white;
            font-weight: 800;
            font-size: 0.85rem;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.4);
            z-index: 2;
            border: 2px solid #ffffff;
            animation: badgePop 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        @keyframes badgePop {
            0% { transform: scale(0.5); }
            100% { transform: scale(1); }
        }

        .menu-img {
            width: 100%;
            height: 145px;
            object-fit: cover;
        }
        .menu-card-body {
            padding: 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .menu-card-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
            line-height: 1.3;
        }
        .menu-card-price {
            font-size: 1.15rem;
            font-weight: 800;
            color: #28a745;
            margin-top: auto;
        }
        .add-to-cart-overlay {
            position: absolute;
            bottom: 12px;
            right: 12px;
            background: #28a745;
            color: white;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 3px 8px rgba(40, 167, 69, 0.3);
            opacity: 0.9;
            transition: opacity 0.2s ease, transform 0.15s ease;
        }
        .menu-card:hover .add-to-cart-overlay {
            opacity: 1;
            transform: scale(1.1);
        }

        /* Sticky Cart Panel Container */
        .cart-panel {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            border: 1px solid #f0f0f0;
            position: sticky;
            top: 80px;
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 100px);
            overflow: hidden;
        }

        .cart-panel-body {
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow-y: auto;
            padding-right: 5px;
        }
        .cart-panel-body::-webkit-scrollbar {
            width: 5px;
        }
        .cart-panel-body::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 10px;
        }

        .cart-header {
            border-bottom: 2px dashed #eee;
            padding-bottom: 15px;
            margin-bottom: 15px;
            flex-shrink: 0;
        }
        .cart-items-container {
            margin-bottom: 15px;
            padding-right: 2px;
        }
        .cart-item {
            display: flex;
            flex-direction: column;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        .cart-item-details {
            flex: 1;
            padding-right: 8px;
        }
        .cart-item-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.98rem;
            margin-bottom: 3px;
        }
        .cart-item-price {
            font-size: 0.9rem;
            font-weight: 600;
            color: #64748b;
        }
        .cart-item-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Touch Stepper for Cart Quantity */
        .touch-qty-stepper {
            display: inline-flex;
            align-items: center;
            background: #f1f5f9;
            border-radius: 30px;
            padding: 3px;
            border: 1px solid #e2e8f0;
        }
        .btn-touch-qty {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            border: none;
            background: #ffffff;
            color: #1e293b;
            font-weight: 800;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.08);
            transition: all 0.15s ease;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        .btn-touch-qty:active {
            transform: scale(0.85);
        }
        .btn-touch-qty-minus:active {
            background: #ef4444;
            color: white;
        }
        .btn-touch-qty-plus:active {
            background: #28a745;
            color: white;
        }
        .touch-qty-val {
            min-width: 28px;
            text-align: center;
            font-weight: 800;
            font-size: 0.98rem;
            color: #0f172a;
        }

        .cart-item-remove {
            color: #ef4444;
            background: #fee2e2;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.15s ease;
            -webkit-tap-highlight-color: transparent;
        }
        .cart-item-remove:active {
            background: #dc3545;
            color: white;
            transform: scale(0.9);
        }

        .cart-totals {
            background: #f8fafc;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 15px;
            border: 1px solid #e2e8f0;
        }
        .cart-total-row {
            display: flex;
            justify-content: space-between;
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
        }

        .checkout-form .form-control {
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 10px 14px;
            height: 44px;
            font-size: 0.95rem;
            margin-bottom: 10px;
            background-color: #ffffff;
        }
        .checkout-form textarea.form-control {
            height: auto;
        }

        .btn-checkout {
            background: #007bff;
            color: white;
            font-weight: 800;
            font-size: 1.05rem;
            padding: 14px;
            border-radius: 10px;
            width: 100%;
            border: none;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(0, 123, 255, 0.35);
            flex-shrink: 0;
            -webkit-tap-highlight-color: transparent;
        }
        .btn-checkout:active {
            transform: scale(0.98);
            background: #0056b3;
        }

        .btn-clear-cart {
            background: #fff5f5;
            color: #dc3545;
            border: 1px solid #fed7d7;
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            font-weight: 700;
            transition: all 0.2s;
            margin-bottom: 12px;
            -webkit-tap-highlight-color: transparent;
        }
        .btn-clear-cart:active {
            background: #dc3545;
            color: white;
            transform: scale(0.98);
        }

        .quick-cash-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(65px, 1fr));
            gap: 6px;
        }
        .quick-cash-btn {
            padding: 8px 6px;
            font-size: 0.82rem;
            font-weight: 700;
            border-radius: 8px;
            width: 100%;
            text-align: center;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            transition: all 0.15s ease;
            user-select: none;
            -webkit-tap-highlight-color: transparent;
        }
        .quick-cash-btn:active {
            background: #28a745;
            color: #ffffff;
            border-color: #28a745;
            transform: scale(0.95);
        }

        /* Mobile Sticky Floating Cart Bar */
        .mobile-cart-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.12);
            padding: 12px 20px;
            z-index: 1040;
            border-top: 1px solid #eee;
            display: none;
        }

        /* Responsive Viewport Breakpoints */
        @media (max-width: 1199.98px) {
            .menu-grid {
                grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
                gap: 15px;
            }
            .menu-img {
                height: 130px;
            }
        }

        @media (max-width: 991.98px) {
            .cart-panel {
                position: relative;
                top: 0;
                max-height: none;
                height: auto;
                overflow: visible;
                margin-top: 25px;
            }
            .cart-panel-body {
                overflow: visible;
            }
            .mobile-cart-bar {
                display: block;
            }
            .pos-container .content-wrapper {
                padding-bottom: 85px !important;
            }
        }

        @media (max-width: 767.98px) {
            .pos-header-actions {
                flex-direction: column-reverse;
                align-items: stretch;
            }
            .search-container {
                max-width: 100%;
                width: 100%;
            }
            .menu-grid {
                grid-template-columns: repeat(auto-fill, minmax(145px, 1fr));
                gap: 10px;
            }
            .menu-img {
                height: 110px;
            }
            .menu-card-body {
                padding: 10px;
            }
            .menu-card-title {
                font-size: 0.92rem;
            }
            .menu-card-price {
                font-size: 1rem;
            }
            .add-to-cart-overlay {
                width: 30px;
                height: 30px;
                bottom: 10px;
                right: 10px;
            }
            .content-wrapper {
                padding: 15px 10px !important;
            }
        }

        @media (max-width: 480px) {
            .menu-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }
            .category-pill {
                padding: 8px 16px;
                font-size: 0.88rem;
            }
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
$(document).ready(function () {
    var rawSubtotal = 0;
    var currencySymbol = '{!! html_entity_decode($site_settings->currency_symbol ?? "RWF") !!} ';

    // Mobile scroll to cart action
    $('#mobile-view-order-btn').click(function() {
        $('html, body').animate({
            scrollTop: $('#cart-panel-section').offset().top - 70
        }, 300);
    });

    // Search and Category Filtering
    function filterMenu() {
        var categoryId = $('.category-pill.active').data('id');
        var searchQuery = $('#menu-search').val().toLowerCase();

        $('.menu-card-wrapper').each(function() {
            var itemCategory = $(this).data('category');
            var itemName = $(this).find('.menu-card-title').text().toLowerCase();

            var categoryMatch = (categoryId === 'all' || itemCategory == categoryId);
            var searchMatch = itemName.includes(searchQuery);

            if (categoryMatch && searchMatch) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }

    $('.category-pill').click(function() {
        $('.category-pill').removeClass('active');
        $(this).addClass('active');
        filterMenu();
    });

    $('#menu-search').on('keyup', function() {
        filterMenu();
    });

    // Cart Logic
    function addToCart(id, name, price) {
        $.post('{{ route('admin.cart.add') }}', {  _token: "{{ csrf_token() }}", cartkey: 'admin', id: id, name: name, price: price }, function (data) {
            if (data.success) {
                updateCartUI(data.cart);
            }
        });
    }

    function removeFromCart(id) {
        $.post('{{ route('admin.cart.remove') }}', {  _token: "{{ csrf_token() }}", cartkey: 'admin', id: id }, function (data) {
            if (data.success) {
                updateCartUI(data.cart);
            }
        });
    }

    function updateCartQuantity(id, quantity) {
        if (quantity <= 0) {
            removeFromCart(id);
            return;
        }
        $.post('{{ route('admin.cart.update')  }}', { _token: "{{ csrf_token() }}", cartkey: 'admin', id: id, quantity: quantity }, function (data) {
            if (data.success) {
                updateCartUI(data.cart);
            }
        });
    }

    $('#clear-cart').click(function (e) {
        e.preventDefault();
        $.post('{{ route('admin.cart.clear') }}', { _token: "{{ csrf_token() }}", cartkey: 'admin' }, function (data) {
            if (data.success) {
                updateCartUI([]);
            }
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

        var subtotalText = currencySymbol + rawSubtotal.toFixed(2);
        var totalText = currencySymbol + netTotal.toFixed(2);

        $('#cart-subtotal').text(subtotalText);
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
                <div class="cart-item">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="cart-item-details">
                            <div class="cart-item-name">${item.name}</div>
                            <div class="cart-item-price">${currencySymbol}${(parseFloat(item.price)).toFixed(2)}</div>
                        </div>
                        <div class="cart-item-actions">
                            <div class="touch-qty-stepper">
                                <button type="button" class="btn-touch-qty btn-touch-qty-minus" data-id="${item.id}" data-qty="${qty}">-</button>
                                <span class="touch-qty-val">${qty}</span>
                                <button type="button" class="btn-touch-qty btn-touch-qty-plus" data-id="${item.id}" data-qty="${qty}">+</button>
                            </div>
                            <button type="button" class="cart-item-remove" data-id="${item.id}" title="Remove item"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                    <div class="mt-2">
                        <input type="text" class="form-control form-control-sm item-note-input" data-id="${item.id}" placeholder="Note (e.g. No onions)" value="${item.item_note || ''}" style="font-size: 0.8rem; padding: 4px 10px; height: 30px; border-radius: 6px;">
                    </div>
                </div>
            `);
        });

        // Update live badges on menu cards
        $('.menu-card-badge').hide().text('0');
        $('.menu-card').removeClass('in-cart');

        $('.menu-card-wrapper').each(function() {
            var menuCard = $(this).find('.menu-card');
            var id = menuCard.data('id');
            if (cartQtyMap[id] && cartQtyMap[id] > 0) {
                $('#menu-badge-' + id).text(cartQtyMap[id]).show();
                menuCard.addClass('in-cart');
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
        
        $('.btn-touch-qty-minus').click(function () {
            var id = $(this).data('id');
            var currentQty = parseInt($(this).data('qty')) || 1;
            updateCartQuantity(id, currentQty - 1);
        });

        $('.btn-touch-qty-plus').click(function () {
            var id = $(this).data('id');
            var currentQty = parseInt($(this).data('qty')) || 1;
            updateCartQuantity(id, currentQty + 1);
        });

        $('.cart-item-remove').click(function () {
            var id = $(this).data('id');
            removeFromCart(id);
        });

        $('.item-note-input').change(function () {
            var id = $(this).data('id');
            var itemNote = $(this).val();
            $.post('{{ route('admin.cart.update-note') }}', { _token: "{{ csrf_token() }}", cartkey: 'admin', id: id, item_note: itemNote });
        });
    }

    $('.add-to-cart').click(function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var price = $(this).data('price');
        addToCart(id, name, price);
    });

    $.get('{{ route('admin.cart.view') }}', { cartkey: 'admin' }, function (data) {
        updateCartUI(data.cart);
    });

    // Discount calculations
    $('#discount_value, #discount_type').on('input change', function() {
        calculateTotals();
    });

    // Payment & Cash Calculator
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

    $('.quick-cash-btn').click(function() {
        var val = $(this).data('val');
        var total = parseFloat($('#total').val()) || 0;
        if (val === 'exact') {
            $('#amount_tendered').val(total.toFixed(2));
        } else {
            $('#amount_tendered').val(parseFloat(val).toFixed(2));
        }
        calculateCashChange();
    });

    // Checkout validations
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
                    $('#open-order-banner').html('<strong><i class="fa fa-info-circle"></i> Adding to open order: #' + response.order.order_no + '</strong><br><small>' + itemsCount + ' item(s) already ordered for this table.</small>').show();
                    
                    if (response.order.waiter_id && !$('#waiter_id').val()) {
                        $('#waiter_id').val(response.order.waiter_id).trigger('change');
                    }
                }
            });
        }
    });

    $('#checkout-btn').click(function(event) {
        event.preventDefault();
        $('#confirmationModal').modal('show');
    });

    // Continuous AJAX Checkout Submit
    $('#confirmSubmit').click(function() {
        var formData = $('#checkout-form').serialize();
        $('#confirmSubmit').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');

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
                    // Open instant customer receipt & kitchen ticket popups
                    if (response.customer_receipt_url) {
                        window.open(response.customer_receipt_url, '_blank', 'width=400,height=600');
                    }
                    if (response.kitchen_ticket_url) {
                        setTimeout(function() {
                            window.open(response.kitchen_ticket_url, '_blank', 'width=400,height=600');
                        }, 400);
                    }

                    // Show success banner
                    $('#pos-alert-container').html(
                        '<div class="alert alert-success alert-dismissible fade show mb-3 border-0" role="alert" style="border-radius: 8px;">' +
                        '<strong><i class="fa fa-check-circle"></i> ' + response.message + '</strong>' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                        '</div>'
                    );

                    // Reset form & cart UI without reloading page
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

@section('title', 'Admin - Point of Sale')

@section('content')
<div class="main-panel pos-container">
    <div class="content-wrapper" style="padding-top: 20px;">
        <div id="pos-alert-container">
            @include('partials.message-bag')
        </div>
 
        <div class="row">
            <!-- Left Side: Menus -->
            <div class="col-xl-8 col-lg-7">
                
                <div class="pos-header-actions">
                    <!-- Category Filters -->
                    <div class="category-pills m-0">
                        <div class="category-pill active" data-id="all">All Items</div>
                        @foreach($categories as $category)
                            <div class="category-pill" data-id="{{ $category->id }}">{{ $category->name }}</div>
                        @endforeach
                    </div>

                    <!-- Search Bar -->
                    <div class="search-container">
                        <input type="text" id="menu-search" class="form-control" placeholder="Search menu items..." style="border-radius: 50px; padding: 10px 20px; border: 1px solid #ddd; background: #fff;">
                    </div>
                </div>

                <!-- Menu Grid -->
                <div class="menu-grid">
                    @forelse ($menus as $menu)
                        <div class="menu-card-wrapper" data-category="{{ $menu->category_id }}">
                            <div class="menu-card add-to-cart" 
                                data-id="{{ $menu->id }}"
                                data-name="{{ $menu->name }}"
                                data-price="{{ $menu->price }}">
                                <div class="menu-card-badge" id="menu-badge-{{ $menu->id }}" style="display: none;">0</div>
                                <img src="{{ asset('storage/' . $menu->image) }}" class="menu-img" alt="{{ $menu->name }}">
                                <div class="menu-card-body">
                                    <div class="menu-card-title">{{ $menu->name }}</div>
                                    <div class="menu-card-price">{!! html_entity_decode($site_settings->currency_symbol) !!}{{ number_format($menu->price, 2) }}</div>
                                </div>
                                <div class="add-to-cart-overlay"><i class="fa fa-plus"></i></div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-5">
                            <h4>No menus available.</h4>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Side: Cart Sidebar -->
            <div class="col-xl-4 col-lg-5" id="cart-panel-section">
                <div class="cart-panel">
                    <div class="cart-header d-flex justify-content-between align-items-center">
                        <h4 class="m-0 font-weight-bold">Current Order</h4>
                        <i class="fa fa-shopping-basket text-success fa-lg"></i>
                    </div>

                    <!-- Scrollable Cart Body Container -->
                    <div class="cart-panel-body">

                        <div id="open-order-banner" style="display: none;" class="alert alert-info py-2 px-3 mb-3 border-0" style="background-color: #e0f7fa; color: #006064; border-radius: 8px;"></div>

                        <!-- Cart Items -->
                        <div class="cart-items-container" id="cart-container">
                            <!-- Items populated via JS -->
                        </div>

                        <button id="clear-cart" style="display: none;" class="btn-clear-cart">
                            <i class="fa fa-trash"></i> Clear Order
                        </button>

                        <!-- Cart Totals & Discount -->
                        <div class="cart-totals">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="m-0 font-weight-bold" style="font-size: 0.85rem;">Discount</label>
                                <div class="d-flex align-items-center gap-1">
                                    <select id="discount_type" class="form-control form-control-sm" style="width: 65px; height: 30px; padding: 2px 5px; border-radius: 6px;">
                                        <option value="amount">{!! html_entity_decode($site_settings->currency_symbol) !!}</option>
                                        <option value="percent">%</option>
                                    </select>
                                    <input type="number" id="discount_value" class="form-control form-control-sm" placeholder="0" min="0" step="0.01" style="width: 80px; height: 30px; border-radius: 6px;">
                                </div>
                            </div>
                            <div class="d-flex justify-content-between text-muted" style="font-size: 0.88rem;">
                                <span>Subtotal:</span>
                                <span id="cart-subtotal">{!! html_entity_decode($site_settings->currency_symbol) !!}0.00</span>
                            </div>
                            <div id="discount-row" class="d-flex justify-content-between text-danger" style="display: none; font-size: 0.88rem;">
                                <span>Discount:</span>
                                <span id="cart-discount">-{!! html_entity_decode($site_settings->currency_symbol) !!}0.00</span>
                            </div>
                            <hr class="my-2">
                            <div class="cart-total-row">
                                <span>Total</span>
                                <span id="cart-total">{!! html_entity_decode($site_settings->currency_symbol) !!}0.00</span>
                            </div>
                        </div>

                        <!-- Checkout Form -->
                        <div id="checkout-form-container" style="display: none;">
                            <form id="checkout-form" class="checkout-form" method="POST" action="{{ route('admin.order.store') }}">
                                <input type="hidden" id="total" value="0">
                                <input type="hidden" id="discount_amount" name="discount_amount" value="0">
                                <input type="hidden" name="cartkey" value="admin">
                                @csrf
                                
                                <select class="form-control" id="waiter_id" name="waiter_id" required>
                                    <option value="">Select Waiter</option>
                                    @foreach($waiters as $waiter)
                                        <option value="{{ $waiter->id }}">{{ $waiter->name }}</option>
                                    @endforeach
                                </select>
                                
                                <select class="form-control" id="restaurant_table_id" name="restaurant_table_id" required>
                                    <option value="">Select Table</option>
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
                                    <label class="font-weight-bold mb-1" style="font-size: 0.85rem;">Payment Method</label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="Cash">Cash</option>
                                        <option value="Card">Card</option>
                                        <option value="Mobile Pay">Mobile Pay</option>
                                        <option value="Pending">Pay Later / Pending</option>
                                    </select>
                                </div>

                                <div id="cash-calculator-box" class="p-2 border rounded bg-white mb-2" style="font-size: 0.85rem;">
                                    <label class="font-weight-bold mb-1">Cash Tendered</label>
                                    <div class="quick-cash-grid mb-2">
                                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-val="exact">Exact</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-val="1000">1,000</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-val="2000">2,000</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-val="5000">5,000</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-val="10000">10,000</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-val="20000">20,000</button>
                                        <button type="button" class="btn btn-xs btn-outline-secondary quick-cash-btn" data-val="50000">50,000</button>
                                    </div>
                                    <input type="number" class="form-control mb-1" id="amount_tendered" name="amount_tendered" step="0.01" placeholder="Amount tendered...">
                                    <div class="d-flex justify-content-between font-weight-bold text-success mt-1">
                                        <span>Change Due:</span>
                                        <span id="change_due_display">{!! html_entity_decode($site_settings->currency_symbol) !!}0.00</span>
                                    </div>
                                    <input type="hidden" id="change_due" name="change_due" value="0">
                                </div>

                                <textarea class="form-control" id="additional_info" name="additional_info" rows="2" placeholder="Order notes..."></textarea>
                            </form>
                        </div>
                    </div>

                    <button type="button" style="display:none;" id="checkout-btn" class="btn-checkout mt-2">
                        Complete Order
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Floating Sticky Cart Bar -->
        <div class="mobile-cart-bar" id="mobile-cart-bar">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                        <i class="fa fa-shopping-basket text-success mr-1"></i>
                        <span id="mobile-cart-count">0 items</span>
                    </div>
                    <div class="text-success font-weight-bold" id="mobile-cart-total" style="font-size: 1.05rem;">
                        {!! html_entity_decode($site_settings->currency_symbol) !!}0.00
                    </div>
                </div>
                <button type="button" id="mobile-view-order-btn" class="btn btn-success btn-sm font-weight-bold px-3" style="border-radius: 50px;">
                    View Order <i class="fa fa-arrow-down ml-1"></i>
                </button>
            </div>
        </div>

        <!-- Confirmation Modal -->
        <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content border-0" style="border-radius: 12px;">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title font-weight-bold">Confirm Order</h5>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <h4 class="text-dark mb-3">Send this order to the kitchen?</h4>
                        <p class="text-muted">Once confirmed, the order will be placed and printed.</p>
                    </div>
                    <div class="modal-footer justify-content-center border-0 pb-4">
                        <button type="button" class="btn btn-light px-4 mr-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-success px-4" id="confirmSubmit">Confirm Order</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('partials.admin.footer')
</div>
@endsection