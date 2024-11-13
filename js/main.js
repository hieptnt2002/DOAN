$(document).ready(function() {
    // Toggle user dropdown
    $('#user-btn').click(function(e) {
        e.stopPropagation();
        $('.user-dropdown').toggleClass('active');
        $('.search-form').removeClass('active');
    });

    // Đóng dropdown khi click bên ngoài
    $(document).click(function(e) {
        if (!$(e.target).closest('.user-icon').length) {
            $('.user-dropdown').removeClass('active');
        }
    });

    // Ngăn dropdown đóng khi click vào nội dung bên trong
    $('.user-dropdown').click(function(e) {
        e.stopPropagation();
    });

    // Xử lý thêm vào giỏ hàng từ danh sách sản phẩm
    $(document).on('click', '.add-to-cart', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        addToCartAjax(productId, 1);
    });

    // Xử lý thêm vào giỏ hàng từ modal chi tiết
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        const productId = $(this).data('product-id');
        const quantity = $('#quantity').val();
        addToCartAjax(productId, quantity);
    });

    function addToCartAjax(productId, quantity) {
        $.ajax({
            url: 'includes/add_to_cart.php',
            method: 'POST',
            data: { 
                product_id: productId,
                quantity: quantity 
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('.cart-count').text(response.cartCount);
                    $('.cart-count').addClass('pulse');
                    setTimeout(() => {
                        $('.cart-count').removeClass('pulse');
                    }, 500);
                    alert(response.message);
                    $('#productModal').modal('hide');
                } else {
                    alert(response.message);
                    if (response.message.includes('đăng nhập')) {
                        window.location.href = 'login.php';
                    }
                }
            },
            error: function() {
                alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
            }
        });
    }
}); 
