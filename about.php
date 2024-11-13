<?php
session_start();
require_once 'config/db.php';
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới Thiệu - Nước Hoa</title>
    <?php include 'includes/head.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    

</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="about-container">
        <div class="about-header">
            <h1>Về Chúng Tôi</h1>
            <p>Chuyên cung cấp các sản phẩm nước hoa chính hãng</p>
        </div>

        <div class="about-content">
            <div class="about-section">
                <div class="about-image">
                    <img src="image/Hồng ân.png" alt="Cửa hàng nước hoa">
                </div>
                <div class="about-text">
                    <h2>Câu Chuyện Của Chúng Tôi</h2>
                    <p>Được thành lập từ năm 2020, chúng tôi tự hào là một trong những đơn vị tiên phong trong lĩnh vực kinh doanh nước hoa chính hãng tại Việt Nam.</p>
                    <p>Với phương châm "Uy tín tạo nên thương hiệu", chúng tôi cam kết mang đến cho khách hàng những sản phẩm chất lượng nhất với giá cả hợp lý nhất.</p>
                </div>
            </div>

            <div class="values-section">
                <h2>Giá Trị Cốt Lõi</h2>
                <div class="values-grid">
                    <div class="value-item">
                        <i class="fas fa-check-circle"></i>
                        <h3>Chất Lượng</h3>
                        <p>100% sản phẩm chính hãng, có giấy tờ nguồn gốc rõ ràng</p>
                    </div>
                    <div class="value-item">
                        <i class="fas fa-heart"></i>
                        <h3>Tận Tâm</h3>
                        <p>Tư vấn chuyên nghiệp, hỗ trợ khách hàng tận tình</p>
                    </div>
                    <div class="value-item">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Uy Tín</h3>
                        <p>Cam kết bảo hành và đổi trả theo quy định</p>
                    </div>
                </div>
            </div>

            <div class="stats-section">
                <div class="stat-item">
                    <h3>1000+</h3>
                    <p>Khách hàng</p>
                </div>
                <div class="stat-item">
                    <h3>50+</h3>
                    <p>Thương hiệu</p>
                </div>
                <div class="stat-item">
                    <h3>500+</h3>
                    <p>Sản phẩm</p>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <!-- Thêm main.js -->
    <script src="js/main.js"></script>
</body>
</html> 