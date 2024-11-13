<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
session_start();

// Bật hiển thị lỗi để debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Kiểm tra đăng nhập
if (!isLoggedIn()) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
    try {
        $order_id = $_POST['order_id'];
        $user_id = $_SESSION['user_id'];

        // Sửa câu query để cho phép hủy cả đơn hàng đang xử lý
        $stmt = $conn->prepare("
            SELECT * FROM orders 
            WHERE id = ? AND user_id = ? 
            AND status IN ('pending', 'processing')
        ");
        $stmt->execute([$order_id, $user_id]);
        $order = $stmt->fetch();

        if ($order) {
            // Cập nhật trạng thái
            $stmt = $conn->prepare("
                UPDATE orders 
                SET status = 'cancelled' 
                WHERE id = ? AND user_id = ?
            ");
            
            if ($stmt->execute([$order_id, $user_id])) {
                echo json_encode(['status' => 'success', 'message' => 'Đơn hàng đã được hủy thành công']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không thể cập nhật trạng thái đơn hàng']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy đơn hàng hoặc đơn hàng không thể hủy']);
        }
    } catch (PDOException $e) {
        error_log("Database Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi database: ' . $e->getMessage()]);
    } catch (Exception $e) {
        error_log("General Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Lỗi hệ thống: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Yêu cầu không hợp lệ']);
} 