<?php
session_start();
require_once '../config/db.php';
require_once 'functions.php';

// Thêm log
error_log('Request received: ' . print_r($_POST, true));

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    error_log('User not logged in');
    echo json_encode([
        'success' => false,
        'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    error_log("Processing add to cart - Product ID: $product_id, Quantity: $quantity");
    
    try {
        if (addToCart($conn, $product_id, $quantity)) {
            $cartCount = getCartCount($conn);
            error_log("Add to cart successful - Cart count: $cartCount");
            echo json_encode([
                'success' => true,
                'cartCount' => $cartCount,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng'
            ]);
        } else {
            error_log("Add to cart failed");
            echo json_encode([
                'success' => false,
                'message' => 'Không thể thêm sản phẩm vào giỏ hàng'
            ]);
        }
    } catch (Exception $e) {
        error_log("Add to cart error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
        ]);
    }
} else {
    error_log("Invalid request - POST data: " . print_r($_POST, true));
    echo json_encode([
        'success' => false,
        'message' => 'Yêu cầu không hợp lệ'
    ]);
} 