<?php

function getAllProducts($conn) {
    $stmt = $conn->prepare("SELECT * FROM products ORDER BY created_at DESC");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMaleProducts($conn, $page = 1, $products_per_page = 8) {
    $offset = ($page - 1) * $products_per_page;
    
    $stmt = $conn->prepare("SELECT * FROM products 
            WHERE category_id = 1  
            ORDER BY id DESC 
            LIMIT :limit 
            OFFSET :offset");
            
    $stmt->bindValue(':limit', $products_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getFemaleProducts($conn, $page = 1, $products_per_page = 8) {
    $offset = ($page - 1) * $products_per_page;
    
    $stmt = $conn->prepare("SELECT * FROM products 
            WHERE category_id = 2  
            ORDER BY id DESC 
            LIMIT :limit 
            OFFSET :offset");
            
    $stmt->bindValue(':limit', $products_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getTotalMaleProducts($conn) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = 1");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}


function getTotalFemaleProducts($conn) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM products WHERE category_id = 2");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}


function formatPrice($price) {
    return number_format($price, 0, ',', '.') . 'đ';
}

function getBanner($conn, $category_id) {
    $stmt = $conn->prepare("SELECT * FROM banners WHERE category_id = ?");
    $stmt->execute([$category_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?> 