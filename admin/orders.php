<?php
require_once '../config/db.php';
require_once '../includes/functions.php';
session_start();

if (!isAdmin()) {
    header('Location: ../index.php');
    exit();
}

// Xử lý cập nhật trạng thái đơn hàng qua AJAX
if (isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    if ($stmt->execute([$status, $order_id])) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit();
}

// Lấy danh sách đơn hàng
$stmt = $conn->query("
    SELECT o.*, u.username, u.email, u.phone
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.created_at DESC
");
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Đơn Hàng - Admin</title>
    <?php include '../includes/admin/components/head.php'; ?>

</head>

<body>


    <div class="admin-container">
        <?php include '../includes/admin/components/sidebar.php'; ?>

        <div class="admin-content">
            <div class="content-header">
                <h2>Quản Lý Đơn Hàng</h2>
            </div>

            <div class="orders-list">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Khách Hàng</th>
                            <th>Tổng Tiền</th>
                            <th>Phương thức TT</th>
                            <th>Trạng Thái</th>
                            <th>Ngày Đặt</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo htmlspecialchars($order['username'] ?? 'Khách vãng lai'); ?></td>
                                <td><?php echo number_format($order['total'], 0, ',', '.'); ?>đ</td>
                                <td><?php echo $order['payment_method'] ?? 'COD'; ?></td>
                                <td>
                                    <select class="status-select status-<?php echo $order['status']; ?>"
                                        onchange="updateStatus(<?php echo $order['id']; ?>, this.value)">
                                        <option value="pending" <?php echo $order['status'] == 'pending' ? 'selected' : ''; ?>>
                                            Chờ xử lý
                                        </option>
                                        <option value="processing" <?php echo $order['status'] == 'processing' ? 'selected' : ''; ?>>
                                            Đang xử lý
                                        </option>
                                        <option value="shipping" <?php echo $order['status'] == 'shipping' ? 'selected' : ''; ?>>
                                            Đang giao
                                        </option>
                                        <option value="completed" <?php echo $order['status'] == 'completed' ? 'selected' : ''; ?>>
                                            Hoàn thành
                                        </option>
                                        <option value="cancelled" <?php echo $order['status'] == 'cancelled' ? 'selected' : ''; ?>>
                                            Đã hủy
                                        </option>
                                    </select>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <button onclick="viewOrder(<?php echo $order['id']; ?>)"
                                        class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7" class="p-0">
                                    <div id="order-<?php echo $order['id']; ?>" class="order-details">
                                        <div class="customer-info">
                                            <h5>Thông tin khách hàng</h5>
                                            <p><strong>Họ tên:</strong> <?php echo htmlspecialchars($order['username'] ?? 'Không có'); ?></p>
                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email'] ?? 'Không có'); ?></p>
                                            <p><strong>SĐT:</strong> <?php echo htmlspecialchars($order['phone'] ?? 'Không có'); ?></p>
                                            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address'] ?? 'Không có'); ?></p>
                                            <p><strong>Ghi chú:</strong> <?php echo htmlspecialchars($order['note'] ?? 'Không có'); ?></p>
                                            <p><strong>Phương thức thanh toán:</strong> <?php echo htmlspecialchars($order['payment_method'] ?? 'COD'); ?></p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



    <script>
        function updateStatus(orderId, status) {
            if (confirm('Bạn có chắc muốn cập nhật trạng thái đơn hàng?')) {
                $.ajax({
                    url: 'orders.php',
                    method: 'POST',
                    data: {
                        update_status: 1,
                        order_id: orderId,
                        status: status
                    },
                    success: function(response) {
                        const result = JSON.parse(response);
                        if (result.status === 'success') {
                            const select = document.querySelector(`select[onchange*="${orderId}"]`);
                            select.className = `status-select status-${status}`;
                            alert('Cập nhật trạng thái thành công!');
                        } else {
                            alert('Có lỗi xảy ra!');
                        }
                    },
                    error: function() {
                        alert('Có lỗi xảy ra!');
                    }
                });
            }
        }

        function viewOrder(orderId) {
            const details = document.getElementById(`order-${orderId}`);
            if (details.style.display === 'none' || !details.style.display) {
                details.style.display = 'block';
            } else {
                details.style.display = 'none';
            }
        }
    </script>
</body>

</html>