<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../includes/functions.php';

// Kiểm tra quyền admin
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}
?>
<header class="admin-header">
    <div class="header-left">
        <a href="dashboard.php" class="admin-logo">
            <img src="../image/Hồng ân.png" alt="Logo" height="40">
            <span>Admin Panel</span>
        </a>
    </div>
    
    <div class="header-right">
        <div class="admin-user">
            <a href="../index.php" class="btn btn-outline-light btn-sm me-2">
                <i class="fas fa-home"></i> Trang chủ
            </a>
            <div class="dropdown d-inline-block">
                <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                    <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="../account.php"><i class="fas fa-user-circle"></i> Tài khoản</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
                </ul>
            </div>
        </div>
    </div>
</header>
