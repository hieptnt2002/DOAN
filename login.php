<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        if (loginUser($conn, $email, $password)) {
            header('Location: index.php');
            exit();
        } else {
            $error = 'Email hoặc mật khẩu không đúng!';
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
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
   
    <link rel="stylesheet" href="main.css?v=1.0">
    
   
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    
    <link rel="stylesheet" href="css/style.css">
    
  
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="form-container">
        <form action="" method="POST" class="login-form">
            <h3>Đăng nhập</h3>
            
            <?php if ($error): ?>
                <div class="error-msg"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="password" required placeholder="Mật khẩu">
            <input type="submit" name="login" value="Đăng nhập" class="btn">
            <p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
        </form>
    </div>
</body>
</html> 