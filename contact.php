<?php require_once 'config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ | Nước Hoa</title>
    <?php include 'includes/head.php'; ?>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="main.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-wrapper">
        <section class="contact-section">
            <div class="container">
                <h2 class="section-title">Liên Hệ Với Chúng Tôi</h2>
                <div class="contact-content">
                    <div class="contact-form-wrapper">
                        <form action="process_contact.php" method="POST">
                            <div class="form-group">
                                <label for="name">Họ Và Tên</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone">Số Điện Thoại</label>
                                <input type="tel" id="phone" name="phone">
                            </div>
                            
                            <div class="form-group">
                                <label for="message">Nội Dung</label>
                                <textarea id="message" name="message" rows="5" required></textarea>
                            </div>
                            
                            <button type="submit" class="submit-btn">Gửi Liên Hệ</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="map-section">
            <div class="container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3826.3793581352425!2d107.58497117338416!3d16.456317828962558!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3141a147b5776fbd%3A0x1deb5c08a8dc7abe!2zNzMgUGhhbiDEkMOsbmggUGjDuW5nLCBWxKluaCBOaW5oLCBUaMOgbmggcGjhu5EgSHXhur8sIFRo4burYSBUaGnDqm4gSHXhur8sIFZp4buHdCBOYW0!5e0!3m2!1svi!2s!4v1717256726885!5m2!1svi!2s"
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html>