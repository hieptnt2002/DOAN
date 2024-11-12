<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $fullname = $_POST['fullname'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        
        
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $error = 'Tên đăng nhập đã tồn tại!';
        }
        
        else {
            $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                $error = 'Email đã được sử dụng!';
            }
            
            elseif ($password !== $confirm_password) {
                $error = 'Mật khẩu xác nhận không khớp!';
            }
            
            else {
                if (registerUser($conn, $username, $email, $password, $fullname, $phone, $address)) {
                    $success = 'Đăng ký thành công! Vui lòng đăng nhập.';
                } else {
                    $error = 'Có lỗi xảy ra, vui lòng thử lại!';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="main.css?v=1.0">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Form CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <!-- jQuery và Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="form-container">
        <form action="" method="POST" class="register-form">
            <h3>Đăng ký</h3>
            
            <?php if ($error): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="success-msg"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <input type="text" name="username" required placeholder="Tên đăng nhập">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="password" required placeholder="Mật khẩu">
            <input type="password" name="confirm_password" required placeholder="Xác nhận mật khẩu">
            <input type="text" name="fullname" placeholder="Họ và tên">
            <input type="tel" name="phone" placeholder="Số điện thoại">
            <textarea name="address" placeholder="Địa chỉ"></textarea>
            <input type="submit" name="register" value="Đăng ký" class="btn">
            <p>Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>
        </form>
    </div>
</body>
</html> 