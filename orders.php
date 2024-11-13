<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
session_start();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("
    SELECT o.*, 
           COALESCE((
               SELECT SUM(od.price * od.quantity) 
               FROM order_details od 
               WHERE od.order_id = o.id
           ), 0) as total
    FROM orders o 
    WHERE o.user_id = ? 
    ORDER BY o.created_at DESC
");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn Hàng - Hồng Ân</title>
    <?php include 'includes/head.php'; ?>
    <style>
        .orders-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }
        .order-item {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            margin-bottom: 15px;
        }
        .order-id {
            font-weight: bold;
        }
        .order-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .text-warning { color: #ffc107 !important; }
        .text-info { color: #17a2b8 !important; }
        .text-primary { color: #007bff !important; }
        .text-success { color: #28a745 !important; }
        .text-danger { color: #dc3545 !important; }
        .order-details {
            margin: 15px 0;
        }
        .order-product {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .product-name {
            flex: 1;
        }
        .product-quantity {
            margin: 0 20px;
            color: #666;
        }
        .product-price,
        .product-subtotal {
            font-weight: 500;
            min-width: 120px;
            text-align: right;
        }
        .product-subtotal {
            color: #e84393;
        }
        .order-total {
            text-align: right;
            font-weight: bold;
            margin-top: 15px;
            font-size: 1.1em;
            color: #e84393;
        }
        .status-pending { 
            background: #ffeeba !important; 
            color: #856404 !important; 
        }
        .status-processing { 
            background: #b8daff !important; 
            color: #004085 !important; 
        }
        .status-shipping { 
            background: #c3e6cb !important; 
            color: #155724 !important; 
        }
        .status-completed { 
            background: #d4edda !important; 
            color: #155724 !important; 
        }
        .status-cancelled { 
            background: #f8d7da !important; 
            color: #721c24 !important; 
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="orders-container">
        <h2 class="mb-4">Đơn Hàng Của Tôi</h2>
        
        <?php if (empty($orders)): ?>
            <div class="alert alert-info">
                <p>Bạn chưa có đơn hàng nào.</p>
                <a href="index.php" class="btn btn-primary mt-3">Mua Sắm Ngay</a>
            </div>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <div class="order-item">
                    <div class="order-header">
                        <div class="order-id">
                            Mã đơn: #<?php echo $order['id']; ?>
                        </div>
                        <div class="order-date">
                            <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                        </div>
                        <div class="order-status <?php echo getOrderStatusClass($order['status']); ?>">
                            <?php echo getOrderStatus($order['status']); ?>
                        </div>
                        <?php if ($order['status'] == 'pending' || $order['status'] == 'processing'): ?>
                            <button class="btn btn-danger btn-sm cancel-order" data-order-id="<?php echo $order['id']; ?>">Hủy đơn</button>
                        <?php endif; ?>
                    </div>
                    <div class="order-details">
                        <?php
                        $stmt = $conn->prepare("
                            SELECT od.*, p.name as product_name 
                            FROM order_details od 
                            JOIN products p ON od.product_id = p.id 
                            WHERE od.order_id = ?
                        ");
                        $stmt->execute([$order['id']]);
                        $orderDetails = $stmt->fetchAll();
                        ?>
                        
                        <?php foreach ($orderDetails as $detail): ?>
                            <?php
                            $subtotal = $detail['price'] * $detail['quantity'];
                            $orderTotal = 0;
                            $orderTotal += $subtotal;
                            ?>
                            <div class="order-product">
                                <span class="product-name"><?php echo $detail['product_name']; ?></span>
                                <span class="product-quantity">x<?php echo $detail['quantity']; ?></span>
                                <span class="product-price"><?php echo number_format($detail['price'], 0, ',', '.'); ?>đ</span>
                                <span class="product-subtotal"><?php echo number_format($subtotal, 0, ',', '.'); ?>đ</span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-total">
                        Tổng tiền: <?php echo number_format($orderTotal, 0, ',', '.'); ?>đ
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
    $(document).ready(function() {
        $('.cancel-order').click(function() {
            var orderId = $(this).data('order-id');
            if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
                $.ajax({
                    url: 'cancel_order.php',
                    method: 'POST',
                    data: { order_id: orderId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            location.reload();
                        } else {
                            alert(response.message || 'Có lỗi xảy ra khi hủy đơn hàng.');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', status, error);
                        console.log('Response:', xhr.responseText);
                        alert('Có lỗi xảy ra khi kết nối với server.');
                    }
                });
            }
        });
    });
    </script>
</body>
</html> 