<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

// Xử lý xóa sản phẩm
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    try {
        $order_details_id = $_POST['id'];
        $user_id = $_SESSION['user_id'];

        // Xác thực order thuộc về user hiện tại
        $stmt = $conn->prepare("
            SELECT od.id 
            FROM order_details od
            JOIN orders o ON od.order_id = o.id
            WHERE od.id = ? AND o.user_id = ? AND o.status = 'pending'
        ");
        $stmt->execute([$order_details_id, $user_id]);
        
        if ($stmt->fetch()) {
            // Xóa order detail
            $stmt = $conn->prepare("DELETE FROM order_details WHERE id = ?");
            $stmt->execute([$order_details_id]);

            // Lấy danh sách orders mới
            $orders = getOrders($conn);
            $totalPrice = getTotalOrders($orders);

            echo json_encode([
                'success' => true,
                'orders' => $orders,
                'totalPrice' => $totalPrice
            ]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi xóa sản phẩm'
        ]);
        exit;
    }
}

updateQuantityOrder($conn);
$orders = getOrders($conn);
$totalPrice = getTotalOrders($orders);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng Của Bạn</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css?v=1.0">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <?php include 'includes/header.php'; ?>
    <?php include 'includes/slider.php';  ?>

    <section class="cart py-5">
        <div class="container">
            <h2 class="text-center mb-4">Giỏ Hàng Của Bạn</h2>

            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Sản Phẩm</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Chọn</th>
                    </tr>
                </thead>
                <tbody id="cart-body">
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="4" style="text-align:center; font-weight: bold; color: #555;">
                                Giỏ hàng của bạn hiện tại rỗng.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr data-id="<?php echo $order['id']; ?>">
                                <td>
                                    <img src="./image/shopping (31).webp" alt="" width="48" class="mr-2">
                                    <?php echo htmlspecialchars($order['product_name']); ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    // Kiểm tra và hiển thị giá
                                    $price = isset($order['price']) ? $order['price'] : 0;
                                    echo formatPrice($price); 
                                    ?>
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" 
                                           data-id="<?php echo $order['id']; ?>" 
                                           value="<?php echo htmlspecialchars($order['quantity']); ?>" 
                                           min="1" />
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger btn-sm delete-item" 
                                            data-id="<?php echo $order['id']; ?>">
                                        Xóa
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

            </table>

            <div class="price-total mt-4 text-right">
                <p class="font-weight-bold p-2 border rounded" style="border: 1px solid #ddd;">Tổng Tiền:
                    <span id="total-price"><?php echo formatPrice($totalPrice); ?></span>
                </p>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="perfumes.php" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
                </a>
                <?php if (!empty($orders)): ?>
                    <a href="checkout.php" class="btn btn-primary">
                        Tiến hành thanh toán <i class="fas fa-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </section>


    <?php include 'includes/footer.php'; ?>

    <script>
        $(document).on('change', '.quantity', function() {
            var orderId = $(this).data('id');
            var quantity = $(this).val();
            $.ajax({
                url: 'cart.php',
                method: 'POST',
                data: {
                    action: 'update_quantity',
                    order_id: orderId,
                    quantity: quantity
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#total-price').text(data.totalPrice);
                },
                error: function() {
                    alert('Có lỗi xảy ra khi cập nhật giỏ hàng');
                }
            });
        });
        $(document).ready(function() {
            $(document).on('click', '.delete-item', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: 'cart.php',
                    method: 'POST',
                    data: {
                        action: 'delete',
                        id: id
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.orders.length === 0) {
                            $('#cart-body').html('<tr><td colspan="4" style="text-align:center;">Giỏ hàng của bạn hiện tại rỗng.</td></tr>');
                        } else {
                            var tbody = $('#cart-body');
                            tbody.empty();

                            data.orders.forEach(function(order) {
                                tbody.append(`
                                <tr data-id="${order.order_id}">
                                    <td>
                                        <img src="./image/shopping (31).webp" alt="" width="48" class="mr-2">
                                        ${order.product_name}
                                    </td>
                                    <td class="text-center">
                                        <p class="order-price">${formatPrice(order.price)}</p>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control quantity" 
                                               data-id="${order.order_id}" 
                                               value="${order.quantity}" min="1" />
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-danger btn-sm delete-item" 
                                                data-id="${order.order_details_id}">Xoá</button>
                                    </td>
                                </tr>
                                `);
                            });
                        }

                        $('#total-price').text(formatPrice(data.totalPrice));
                    },
                    error: function() {
                        alert('Có lỗi xảy ra khi xóa sản phẩm');
                    }
                });
            });

            // Thêm hàm format giá
            function formatPrice(price) {
                return new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                }).format(price).replace('₫', 'đ');
            }
        });
    </script>

</body>

</html>