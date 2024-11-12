<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Nước Hoa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
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
                            <button type="button" class="view-detail" 
                                    onclick="showProductDetail(<?php echo htmlspecialchars(json_encode($product)); ?>)">
                                Chi tiết
                            </button>
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

    <!-- Thêm Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="product-detail-content">
                        <div class="product-image">
                            <img src="" alt="" id="modalProductImage">
                        </div>
                        <div class="product-info">
                            <h2 id="modalProductName"></h2>
                            <p class="brand" id="modalProductBrand"></p>
                            <div class="price-container">
                                <span id="modalProductPrice"></span>
                                <span id="modalProductSalePrice"></span>
                            </div>
                            <div class="stock-status" id="modalProductStock"></div>
                            <p class="description" id="modalProductDescription"></p>
                            <div class="quantity-selector">
                                <button onclick="decreaseQuantity()">-</button>
                                <input type="number" id="quantity" value="1" min="1">
                                <button onclick="increaseQuantity()">+</button>
                            </div>
                            <button class="add-to-cart-btn" onclick="addToCart()">
                                Thêm vào giỏ hàng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>

    document.getElementById('productModal').addEventListener('hidden.bs.modal', function () {
        // Reset số lượng về 1
        document.getElementById('quantity').value = 1;
    });

    function showProductDetail(product) {
      
        document.getElementById('modalProductImage').src = product.image || 'https://via.placeholder.com/300x300';
        document.getElementById('modalProductName').textContent = product.name;
        document.getElementById('modalProductBrand').textContent = product.brand_name;
        
       
        if (product.sale_price) {
            document.getElementById('modalProductPrice').innerHTML = 
                `<span class="original-price">${formatPrice(product.price)}đ</span>`;
            document.getElementById('modalProductSalePrice').innerHTML = 
                `<span class="sale-price">${formatPrice(product.sale_price)}đ</span>`;
        } else {
            document.getElementById('modalProductPrice').innerHTML = 
                `<span class="price">${formatPrice(product.price)}đ</span>`;
            document.getElementById('modalProductSalePrice').innerHTML = '';
        }
        
       
        document.getElementById('modalProductStock').innerHTML = product.stock > 0 
            ? '<span class="in-stock">Còn hàng</span>' 
            : '<span class="out-of-stock">Hết hàng</span>';
        
        document.getElementById('modalProductDescription').textContent = 
            product.description || 'Chưa có mô tả cho sản phẩm này';

        
        new bootstrap.Modal(document.getElementById('productModal')).show();
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }

    function decreaseQuantity() {
        let qty = document.getElementById('quantity');
        if (qty.value > 1) qty.value = parseInt(qty.value) - 1;
    }

    function increaseQuantity() {
        let qty = document.getElementById('quantity');
        qty.value = parseInt(qty.value) + 1;
    }

    function addToCart() {
        
        alert('Đã thêm sản phẩm vào giỏ hàng!');
      
        bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
    }
    </script>

   
    <style>
    .product-detail-content {
        display: flex;
        gap: 30px;
    }

    .product-image {
        flex: 1;
        max-width: 400px;
    }

    .product-image img {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .product-info {
        flex: 1;
    }

    .quantity-selector {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0;
    }

    .quantity-selector button {
        width: 30px;
        height: 30px;
        border: none;
        background: #f1f1f1;
        border-radius: 5px;
        cursor: pointer;
    }

    .quantity-selector input {
        width: 50px;
        height: 30px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .add-to-cart-btn {
        width: 100%;
        padding: 12px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .add-to-cart-btn:hover {
        background: #2980b9;
    }

    .description {
        margin: 20px 0;
        line-height: 1.6;
        color: #666;
    }

    @media (max-width: 768px) {
        .product-detail-content {
            flex-direction: column;
        }
        
        .product-image {
            max-width: 100%;
        }
    }
    </style>

    <?php 
    } catch(PDOException $e) {
        echo "Lỗi: " . $e->getMessage();
    }
    
    include 'includes/footer.php'; 
    ?>
</body>
</html> 