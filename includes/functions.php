<?php
// Lấy tất cả sản phẩm
function getAllProducts($conn) {
    $stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy sản phẩm nam
function getMaleProducts($conn) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = 1");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy sản phẩm nữ
function getFemaleProducts($conn) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = 2");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy banner theo danh mục
function getCategoryBanner($conn, $category_id) {
    $stmt = $conn->prepare("SELECT image FROM banners WHERE category_id = ?");
    $stmt->execute([$category_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Format giá tiền
function formatPrice($price) {
    return number_format($price, 0, ',', '.') . 'đ';
}

// Thêm hàm lấy banner
function getBanner($conn, $category_id) {
    $stmt = $conn->prepare("SELECT * FROM banners WHERE category_id = ?");
    $stmt->execute([$category_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?> 