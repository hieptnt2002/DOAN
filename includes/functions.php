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

function registerUser($conn, $username, $email, $password, $fullname = null, $phone = null, $address = null) {
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, fullname, phone, address, role) 
                               VALUES (:username, :email, :password, :fullname, :phone, :address, 'user')");
        
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':fullname', $fullname);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        
        return $stmt->execute();
    } catch(PDOException $e) {
        return false;
    }
}

function loginUser($conn, $email, $password) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        return true;
    }
    return false;
}
?> 