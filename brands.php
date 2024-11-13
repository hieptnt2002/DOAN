<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thương Hiệu Nước Hoa</title>
    <?php include 'includes/head.php'; ?>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="main.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php
include 'config/db.php';
include 'includes/header.php';

try {
    
    $sql = "SELECT * FROM brands ORDER BY name";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="page-wrapper">
    <section class="brands-section">
        <h2 class="section-title">Thương Hiệu Nước Hoa</h2>
        
        <div class="brands-container">
            <?php foreach($brands as $brand): ?>
                <div class="brand-item">
                    <div class="brand-image">
                        <?php if($brand['image']): ?>
                            <img src="<?php echo htmlspecialchars($brand['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($brand['name']); ?>"
                                 onerror="this.src='https://via.placeholder.com/300x200'">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x200" 
                                 alt="Default brand image">
                        <?php endif; ?>
                    </div>
                    <div class="brand-info">
                        <h3><?php echo htmlspecialchars($brand['name']); ?></h3>
                        <?php if($brand['description']): ?>
                            <p><?php echo htmlspecialchars($brand['description']); ?></p>
                        <?php endif; ?>
                        <a href="brand_products.php?id=<?php echo $brand['id']; ?>" class="view-products">
                            Xem Sản Phẩm
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php 
} catch(PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

include 'includes/footer.php'; 
?>

</body>
</html> 