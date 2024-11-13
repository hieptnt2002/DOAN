<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập để tiếp tục'
    ]);
    exit;
}

try {
    $conn->beginTransaction();

    // Lấy thông tin từ form
    $user_id = $_SESSION['user_id'];
    $name = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];
    $note = $_POST['note'];

    // Cập nhật thông tin người dùng nếu có thay đổi
    $stmt = $conn->prepare("UPDATE users SET fullname = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->execute([$name, $phone, $address, $user_id]);

    // Lấy đơn hàng pending và tổng tiền
    $stmt = $conn->prepare("SELECT o.*, od.* FROM orders o 
                           JOIN order_details od ON o.id = od.order_id 
                           WHERE o.user_id = ? AND o.status = 'pending'");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll();

    if (empty($orders)) {
        throw new Exception('Không tìm thấy đơn hàng');
    }

    // Tính tổng tiền
    $total_amount = 0;
    foreach ($orders as $order) {
        $total_amount += $order['price'] * $order['quantity'];
    }

    // Cập nhật trạng thái đơn hàng
    $order_id = $orders[0]['order_id'];
    $stmt = $conn->prepare("UPDATE orders SET 
        status = 'processing',
        name = ?,
        phone = ?,
        address = ?,
        payment_method = ?,
        note = ?,
        total_amount = ?,
        total = ?
        WHERE id = ?");
    
    $stmt->execute([
        $name,
        $phone, 
        $address,
        $payment_method,
        $note,
        $total_amount,
        $total_amount,
        $order_id
    ]);

    $conn->commit();

    echo json_encode([
        'success' => true,
        'order_id' => $order_id,
        'message' => 'Đặt hàng thành công'
    ]);

} catch (Exception $e) {
    $conn->rollBack();
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} 