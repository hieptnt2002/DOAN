<?php
session_start();
error_log("Current session: " . print_r($_SESSION, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Nước Hoa</title>
    <?php include 'includes/head.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php 
    include 'includes/header.php';
    include 'config/db.php';
    require_once 'includes/functions.php';
    
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
                                <?php if(isset($_SESSION['user_id'])): ?>
                                    <button type="button" class="add-to-cart" data-product-id="<?php echo $product['id']; ?>">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                <?php else: ?>
                                    <button type="button" class="add-to-cart" onclick="window.location.href='login.php'">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                <?php endif; ?>
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
                            <?php if(isset($_SESSION['user_id'])): ?>
                                <button class="add-to-cart-btn" data-product-id="" id="modalAddToCartBtn">
                                    Thêm vào giỏ hàng
                                </button>
                            <?php else: ?>
                                <button class="add-to-cart-btn" onclick="window.location.href='login.php'">
                                    Đăng nhập để mua hàng
                                </button>
                            <?php endif; ?>
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

        
        document.getElementById('modalAddToCartBtn').setAttribute('data-product-id', product.id);
        
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

    $(document).ready(function() {
        // Debug click event
        $('.add-to-cart-btn, .add-to-cart').click(function(e) {
            e.preventDefault();
            console.log('Button clicked');
            const productId = $(this).data('product-id');
            const quantity = $('#quantity').val() || 1;
            console.log('Product ID:', productId);
            console.log('Quantity:', quantity);
            
            $.ajax({
                url: 'includes/add_to_cart.php',
                method: 'POST',
                data: { 
                    product_id: productId,
                    quantity: quantity 
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Success response:', response);
                    if (response.success) {
                        alert(response.message);
                        if(response.cartCount) {
                            $('.cart-count').text(response.cartCount);
                        }
                    } else {
                        if (response.message.includes('đăng nhập')) {
                            if (confirm('Bạn cần đăng nhập để thêm sản phẩm vào giỏ hàng. Đến trang đăng nhập?')) {
                                window.location.href = 'login.php';
                            }
                        } else {
                            alert(response.message);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                    console.log('Response:', xhr.responseText);
                    alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
                }
            });
        });
    });
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html> 