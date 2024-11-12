<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

updateQuantityOrder($conn);
deleteOrder($conn);

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
                            <tr data-id="<?php echo $order['order_id']; ?>">
                                <td>
                                    <img src="./image/shopping (31).webp" alt="" width="48" class="mr-2">
                                    <?php echo $order['product_name']; ?>
                                </td>
                                <td class="text-center">
                                    <p class="order-price"><?php echo formatPrice($order['order_price']); ?></p>
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" data-id="<?php echo $order['order_id']; ?>" value="<?php echo $order['quantity']; ?>" min="1" />
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger btn-sm delete-item" data-id="<?php echo $order['order_details_id']; ?>">Xoá</button>
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
                            <tr data-id="` + order.order_id + `">
                            <td><img src="./image/shopping (31).webp" alt="" width="48" class="mr-2">` + order.product_name + `</td>
                            <td class="text-center"><p class="order-price">` + Math.floor(order.order_price) + "đ" + `</p></td>
                            <td>
                                <input type="number" class="form-control quantity" data-id="` + order.order_id + `" value="` + order.quantity + `" min="1" />
                            </td>
                            <td class="text-center">
                                <button class="btn btn-danger btn-sm delete-item" data-id="` + order.order_details_id + `">Xoá</button>
                            </td>
                            </tr>
                            `);
                        });
                    }

                    $('#total-price').text(data.totalPrice);
                },
                error: function() {
                    alert('Có lỗi xảy ra khi xóa sản phẩm');
                }
            });
        });
    </script>

</body>

</html>