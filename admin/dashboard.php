<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
session_start();

// Kiểm tra quyền admin
if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}

// Lấy thống kê đơn hàng
$totalOrders = $conn->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$pendingOrders = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
$processingOrders = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'processing'")->fetchColumn();
$completedOrders = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'completed'")->fetchColumn();
$cancelledOrders = $conn->query("SELECT COUNT(*) FROM orders WHERE status = 'cancelled'")->fetchColumn();

// Lấy danh sách đơn hàng mới nhất
$recentOrders = $conn->query("
    SELECT o.*, u.username 
    FROM orders o 
    JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC 
    LIMIT 10
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Dashboard - Quản lý đơn hàng</title>
    <?php include '../includes/admin/components/head.php'; ?>
</head>
<body>
    <?php include '../includes/admin/components/header.php'; ?>
    
    <div class="admin-container">
        <?php include '../includes/admin/components/sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="content-header">
                <h2>Tổng Quan Đơn Hàng</h2>
            </div>

            <!-- Thống kê -->
            <div class="dashboard-stats">
                <div class="stat-card">
                    <i class="fas fa-shopping-bag"></i>
                    <h3>Tổng Đơn Hàng</h3>
                    <p><?php echo number_format($totalOrders); ?></p>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-clock"></i>
                    <h3>Chờ Xác Nhận</h3>
                    <p><?php echo number_format($pendingOrders); ?></p>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-spinner"></i>
                    <h3>Đang Xử Lý</h3>
                    <p><?php echo number_format($processingOrders); ?></p>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <h3>Hoàn Thành</h3>
                    <p><?php echo number_format($completedOrders); ?></p>
                </div>
                
                <div class="stat-card">
                    <i class="fas fa-times-circle"></i>
                    <h3>Đã Hủy</h3>
                    <p><?php echo number_format($cancelledOrders); ?></p>
                </div>
            </div>

            <!-- Danh sách đơn hàng mới nhất -->
            <div class="recent-orders">
                <h3>Đơn Hàng Mới Nhất</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Mã ĐH</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['username']); ?></td>
                                <td><?php echo number_format($order['total'], 0, ',', '.'); ?>đ</td>
                                <td>
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo getOrderStatus($order['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <a href="order_detail.php?id=<?php echo $order['id']; ?>" 
                                       class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/admin/components/footer.php'; ?>
</body>
</html> 