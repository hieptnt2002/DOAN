<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fix đường dẫn tương đối
$root_path = $_SERVER['DOCUMENT_ROOT'] . '/DoAn/';
if (!file_exists($root_path . 'config/db.php')) {
    require_once '../config/db.php';
} else {
    require_once 'config/db.php';
}
?>
<header class="header">
    <a href="index.php" class="logo">
        <img src="./image/Hồng ân.png" alt="">
    </a>
    <nav class="navbar">
        <a href="index.php">Trang Chủ</a>
        <a href="about.php">Giới Thiệu</a>
        <a href="brands.php">Thương Hiệu</a>
        <a href="perfumes.php">Nước Hoa</a>
        <a href="samples.php">Nước Hoa Chiết</a>
        <a href="knowledge.php">Kiến Thức</a>
        <a href="contact.php">Liên Hệ</a>
    </nav>
    <div class="icons">
        <div class="search-icon">
            <i class="fa fa-search" id="search-btn"></i>
            <div class="search-form">
                <form action="" method="GET">
                    <input type="text" name="keyword" placeholder="Tìm kiếm thương hiệu..." required>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
                <div class="search-results"></div>
            </div>
        </div>
        <div class="user-icon">
            <i class="fa fa-user" id="user-btn"></i>
            <div class="user-dropdown">
                <?php if (isLoggedIn()): ?>
                    <?php $user = getCurrentUser($conn); ?>
                    <div class="user-info">
                        <p>Xin chào, <?php echo htmlspecialchars($user['username']); ?></p>
                    </div>
                    <hr>
                    <a href="account.php"><i class="fa fa-user-circle"></i> Tài khoản</a>
                    <a href="orders.php"><i class="fa fa-shopping-bag"></i> Đơn hàng</a>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="admin/dashboard.php"><i class="fa fa-gauge"></i> Quản trị</a>
                        <a href="admin/products.php"><i class="fa fa-box"></i> Quản lý sản phẩm</a>
                        <a href="admin/orders.php"><i class="fa fa-list"></i> Quản lý đơn hàng</a>
                        <a href="admin/users.php"><i class="fa fa-users"></i> Quản lý người dùng</a>
                    <?php endif; ?>
                    <hr>
                    <a href="logout.php"><i class="fa fa-sign-out"></i> Đăng xuất</a>
                <?php else: ?>
                    <a href="login.php"><i class="fa fa-sign-in"></i> Đăng nhập</a>
                    <a href="register.php"><i class="fa fa-user-plus"></i> Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
        <a href="wishlist.php"><i class="fa fa-heart"></i></a>
        <a href="cart.php" class="cart-icon">
            <i class="fa fa-shopping-cart"></i>
            <span class="cart-count"><?php echo getCartCount($conn); ?></span>
        </a>
    </div>
</header>
<script>
$(document).ready(function() {

    $('#search-btn').click(function(e) {
        e.stopPropagation();
        $('.search-form').toggleClass('active');
    });

   
    $(document).click(function(e) {
        if (!$(e.target).closest('.search-icon').length) {
            $('.search-form').removeClass('active');
        }
    });

    
    $('.search-form').click(function(e) {
        e.stopPropagation();
    });

    
    let searchTimeout;
    $('.search-form input').on('input', function() {
        clearTimeout(searchTimeout);
        const keyword = $(this).val();
        
        if (keyword.length >= 2) {
            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: 'includes/search_ajax.php',
                    method: 'GET',
                    data: { keyword: keyword },
                    success: function(response) {
                        $('.search-results').html(response).show();
                    }
                });
            }, 300);
        } else {
            $('.search-results').empty().hide();
        }
    });
});
</script> 

