$(document).ready(function () {

    // Floating Cart Notification Toast (Pink & White Theme)
    function showCartToast(itemName) {
        $('#cart-toast-notification').remove();
        var toastHtml = `
            <div id="cart-toast-notification" style="
                position: fixed;
                top: 90px;
                right: 20px;
                z-index: 99999;
                background: #ffffff;
                color: #222222;
                padding: 14px 24px;
                border-radius: 30px;
                box-shadow: 0 10px 30px rgba(255, 50, 77, 0.3);
                display: flex;
                align-items: center;
                gap: 14px;
                font-family: inherit;
                font-size: 14px;
                border: 2px solid #FF324D;
                animation: slideInToast 0.3s ease-out;
            ">
                <div style="background: #FF324D; color: #ffffff; width: 34px; height: 34px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; box-shadow: 0 4px 10px rgba(255, 50, 77, 0.4);">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div>
                    <strong style="color: #FF324D; display: block; line-height: 1.2; font-size: 14px;">Added to Cart!</strong>
                    <span style="color: #444444; font-size: 13px; font-weight: 500;">${itemName}</span>
                </div>
            </div>
            <style>
                @keyframes slideInToast {
                    from { transform: translateY(-20px); opacity: 0; }
                    to { transform: translateY(0); opacity: 1; }
                }
            </style>
        `;
        $('body').append(toastHtml);
        setTimeout(function() {
            $('#cart-toast-notification').fadeOut(400, function() { $(this).remove(); });
        }, 2800);
    }

    // Quick Add To Cart button event for product hover cards
    $(document).on('click', '.add-to-cart-quick', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var $btn = $(this);
        var originalHtml = $btn.html();

        var id = $btn.data('id');
        var name = $btn.data('name');
        var price = $btn.data('price');
        var img_src = $btn.data('img_src');

        $btn.html('<i class="fa fa-spinner fa-spin me-1"></i> Adding...');

        $.ajax({
            url: typeof addToCartUrl !== 'undefined' ? addToCartUrl : '/cart/add',
            type: 'POST',
            data: {
                _token: typeof csrfToken !== 'undefined' ? csrfToken : $('meta[name="csrf-token"]').attr('content'),
                cartkey: 'customer',
                id: id,
                name: name,
                price: price,
                img_src: img_src
            },
            success: function (data) {
                if (data.success) {
                    $('#cart_count').text(data.total_items);
                    showCartToast(name);
                    $btn.html('<i class="fa fa-check me-1"></i> Added!');
                    $btn.removeClass('btn-default').addClass('btn-success');
                    setTimeout(function () {
                        $btn.html(originalHtml);
                        $btn.removeClass('btn-success').addClass('btn-default');
                    }, 2000);
                } else {
                    alert(data.message || 'Failed to add item to cart.');
                    $btn.html(originalHtml);
                }
            },
            error: function () {
                alert('An error occurred while adding the item to the cart.');
                $btn.html(originalHtml);
            }
        });
    });

    // Attach click event to add-to-cart buttons
    $('.add-to-cart').click(function () {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var price = $(this).data('price');
        var img_src = $(this).data('img_src');

        $.ajax({
            url: addToCartUrl,
            type: 'POST',
            data: {
                _token: csrfToken,
                cartkey: 'customer',
                id: id,
                name: name,
                price: price,
                img_src: img_src
            },
            success: function (data) {
                if (data.success) {
                    $('#cart_count').text(data.total_items);
                    showCartToast(name);
                    $('.quantity-input').val(1);
                    $('.checkout-btn').removeClass('d-none').addClass('d-block');
                    $('.quantity').removeClass('d-none').addClass('d-block');
                    $('.add-to-cart').removeClass('d-block').addClass('d-none');
                } else {
                    alert(data.message || 'Failed to add item to cart.');
                }
            },
            error: function () {
                alert('An error occurred while adding the item to the cart.');
            }
        });
    });
 

    // Listener to quantity inputs
    $('.quantity-input').change(function () {
        var id = $(this).data('id');
        var quantity = $(this).val();

        if (quantity == 0) {
            // Remove
            $.post(removeFromCartUrl, { _token: csrfToken, cartkey: 'customer', id: id }, function (data) {
                if (data.success) {
                    $('#cart_count').text(data.total_items);
                    $('.add-to-cart').removeClass('d-none').addClass('d-block');
                    $('.quantity').removeClass('d-block').addClass('d-none');
                    $('.checkout-btn').removeClass('d-block').addClass('d-none');
                }
            });
        } else {
            // Update
            $.post(updateCartUrl, { _token: csrfToken, cartkey: 'customer', id: id, quantity: quantity }, function (data) {
                if (data.success) {
                    $('#cart_count').text(data.total_items);
                }
            });
        }
    });

    
    // Plus button listener
    $('.plus').on('click', function () {
        if ($(this).prev().val()) {
            $(this).prev().val(+$(this).prev().val() + 1).trigger('change');
        }
    });

    // Minus button listener
    $('.minus').on('click', function () {
        if ($(this).next().val() > 0) {
            $(this).next().val(+$(this).next().val() - 1).trigger('change');
        }
    });


});
