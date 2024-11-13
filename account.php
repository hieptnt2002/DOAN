<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
session_start();

// Kiểm tra đăng nhập
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user = getCurrentUser($conn);

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Cập nhật thông tin cơ bản
    $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, phone = ?, address = ? WHERE id = ?");
    $stmt->execute([$username, $email, $phone, $address, $user['id']]);

    // Cập nhật mật khẩu nếu có
    if (!empty($new_password) && $new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashed_password, $user['id']]);
    }

    // Refresh user data
    $user = getCurrentUser($conn);
    $success_message = "Thông tin tài khoản đã được cập nhật thành công!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Tài Khoản - Hồng Ân</title>
    <?php include 'includes/head.php'; ?>
    <link rel="stylesheet" href="./css/main_form.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="account-container">
        <h2>Thông Tin Tài Khoản</h2>
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form action="" method="POST" class="account-form">
            <div class="form-group">
                <label for="username">Tên đăng nhập:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <textarea id="address" name="address"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label for="new_password">Mật khẩu mới (để trống nếu không đổi):</label>
                <input type="password" id="new_password" name="new_password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                <input type="password" id="confirm_password" name="confirm_password">
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Cập Nhật Thông Tin</button>
            </div>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 