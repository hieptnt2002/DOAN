<div class="admin-sidebar">
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
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
        </ul>
    </nav>
</div>
