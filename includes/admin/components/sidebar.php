<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
$user = getCurrentUser($conn);
?>
<div class="admin-sidebar">
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="../index.php">
                    <i class="fas fa-home"></i>
                    <span>Trang Chủ</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>"
                    href="dashboard.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Tổng Quan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'orders.php') ? 'active' : ''; ?>"
                    href="orders.php">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Quản Lý Đơn Hàng</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'products.php') ? 'active' : ''; ?>"
                    href="products.php">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Quản Lý Sản Phẩm</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Admin Profile Section -->
    <div class="admin-profile mt-auto p-3 d-flex align-items-center">
        <img src="../image/Hồng ân.png" alt="Admin Avatar" class="admin-avatar rounded-circle">
        <div class="admin-info ms-3">
            <h5 class="admin-name mb-0"><?php echo $user['fullname'] ?></h5>
            <p class="admin-email text-muted mb-0"><?php echo $user['email'] ?></p>
        </div>
    </div>
</div>