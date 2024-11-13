<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

if (!isset($_GET['order_id'])) {
    header('Location: index.php');
    exit();
}

$order_id = $_GET['order_id'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Hàng Thành Công</title>
    <?php include 'includes/head.php'; ?>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container py-5 text-center">
        <div class="success-message">
            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
            <h2 class="mt-4">Đặt Hàng Thành Công!</h2>
            <p class="lead">Cảm ơn bạn đã đặt hàng. Mã đơn hàng của bạn là: #<?php echo $order_id; ?></p>
            <p>Chúng tôi sẽ sớm liên hệ với bạn để xác nhận đơn hàng.</p>
            
            <div class="mt-4">
                <a href="index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
                <a href="orders.php" class="btn btn-outline-primary">Xem đơn hàng</a>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 