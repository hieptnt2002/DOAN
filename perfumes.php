<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Nước Hoa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>
    <?php 
    include 'includes/header.php';
    include 'config/db.php';
    
    try {
       
        $items_per_page = 12;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $items_per_page;
        
       
        $total_sql = "SELECT COUNT(*) FROM products";
        $total_stmt = $conn->query($total_sql);
        $total_items = $total_stmt->fetchColumn();
        $total_pages = ceil($total_items / $items_per_page);
        
       
        $sql = "SELECT p.*, b.name as brand_name 
                FROM products p 
                LEFT JOIN brands b ON p.brand_id = b.id 
                ORDER BY p.created_at DESC 
                LIMIT :offset, :items_per_page";
                
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':items_per_page', $items_per_page, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="perfumes-container">
        <h2 class="section-title">Danh Sách Nước Hoa</h2>
        
        <div class="perfumes-grid">
            <?php foreach($products as $product): ?>
                <div class="perfume-card">
                    <div class="perfume-image">
                        <?php if($product['image']): ?>
                            <img src="<?php echo htmlspecialchars($product['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 onerror="this.src='https://via.placeholder.com/300x300'">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/300x300" alt="Default perfume image">
                        <?php endif; ?>
                    </div>
                    <div class="perfume-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <p class="brand"><?php echo htmlspecialchars($product['brand_name']); ?></p>
                        <div class="price-container">
                            <?php if($product['sale_price']): ?>
                                <span class="original-price"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                                <span class="sale-price"><?php echo number_format($product['sale_price'], 0, ',', '.'); ?>đ</span>
                            <?php else: ?>
                                <span class="price"><?php echo number_format($product['price'], 0, ',', '.'); ?>đ</span>
                            <?php endif; ?>
                        </div>
                        <div class="stock-status">
                            <?php if($product['stock'] > 0): ?>
                                <span class="in-stock">Còn hàng</span>
                            <?php else: ?>
                                <span class="out-of-stock">Hết hàng</span>
                            <?php endif; ?>
                        </div>
                        <div class="perfume-actions">
                            <a href="perfume_detail.php?id=<?php echo $product['id']; ?>" class="view-detail">
                                Chi tiết
                            </a>
                            <?php if($product['stock'] > 0): ?>
                                <button class="add-to-cart">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        
        <?php if($total_pages > 1): ?>
            <div class="pagination">
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>" 
                       class="<?php echo $i == $page ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php 
    } catch(PDOException $e) {
        echo "Lỗi: " . $e->getMessage();
    }
    
    include 'includes/footer.php'; 
    ?>
</body>
</html> 