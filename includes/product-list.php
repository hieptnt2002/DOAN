<?php
require_once 'functions.php';
$products = getAllProducts($conn);
?>

<section class="list-product-item">
    <div class="product">
        <?php foreach($products as $product): ?>
        <div class="product-item">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo formatPrice($product['price']); ?></p>
            <?php if($product['sale_price']): ?>
                <small><?php echo formatPrice($product['sale_price']); ?></small>
            <?php endif; ?>
            <a href="" class="watch">Xem Trước</a>
            <a href="" class="buy">Mua Ngay</a>
        </div>
        <?php endforeach; ?>
    </div>
</section> 