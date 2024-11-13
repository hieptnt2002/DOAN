<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Lấy thông tin giỏ hàng
$orders = getOrders($conn);
$totalPrice = getTotalOrders($orders);

// Lấy thông tin người dùng
$user = getCurrentUser($conn);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5">
        <h2 class="text-center mb-4">Thanh Toán</h2>
        
        <div class="row">
            <!-- Thông tin đơn hàng -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Thông Tin Đơn Hàng</h4>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                                    <td><?php echo $order['quantity']; ?></td>
                                    <td><?php echo formatPrice($order['price']); ?></td>
                                    <td><?php echo formatPrice($order['price'] * $order['quantity']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Tổng tiền:</strong></td>
                                    <td><strong><?php echo formatPrice($totalPrice); ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Form thông tin giao hàng -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4>Thông Tin Giao Hàng</h4>
                    </div>
                    <div class="card-body">
                        <form id="checkoutForm" method="POST" action="process_order.php">
                            <div class="mb-3">
                                <label for="fullname" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" 
                                       value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ giao hàng</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Phương thức thanh toán</label>
                                <select class="form-control" id="payment_method" name="payment_method" required>
                                    <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                                    <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="note" class="form-label">Ghi chú</label>
                                <textarea class="form-control" id="note" name="note" rows="2"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Đặt hàng</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    $(document).ready(function() {
        $('#checkoutForm').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: 'process_order.php',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Đặt hàng thành công!');
                        window.location.href = 'order_success.php?order_id=' + response.order_id;
                    } else {
                        alert(response.message || 'Có lỗi xảy ra');
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra khi xử lý đơn hàng');
                }
            });
        });
    });
    </script>
</body>
</html> 