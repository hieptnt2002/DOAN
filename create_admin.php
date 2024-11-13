<?php
require_once 'config/db.php';

// Tạo hash password
$password = "123";
$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Xóa tài khoản admin cũ nếu có
    $stmt = $conn->prepare("DELETE FROM users WHERE email = 'admin@gmail.com'");
    $stmt->execute();
    
    // Tạo tài khoản admin mới
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin@gmail.com', $hash, 'admin']);
    
    echo "=== THÔNG TIN TÀI KHOẢN ADMIN ĐÃ TẠO ===<br>";
    echo "Username: admin<br>";
    echo "Email: admin@gmail.com<br>";
    echo "Password: 123<br>";
    echo "Role: admin<br>";
    echo "<br>";
    echo "=== TẠO TÀI KHOẢN THÀNH CÔNG ===<br>";
    
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}
?> 