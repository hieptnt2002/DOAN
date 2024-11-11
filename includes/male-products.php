<?php
require_once 'functions.php';
$male_products = getMaleProducts($conn);
$banner = getBanner($conn, 1); // 1 là category_id cho nước hoa nam
?>

<section class="male">
    <div class="male-banner">
        <h1>NƯỚC HOA NAM</h1>
        <img src="<?php echo $banner['image']; ?>" alt="Banner nước hoa nam" width="85%" height="400" style="position: relative; left: 100px;">
    </div>
    
    <div class="products-male">
        <?php foreach($male_products as $product): ?>
        <div class="products-item-male">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo formatPrice($product['price']); ?></p>
            <?php if($product['sale_price']): ?>
                <small><?php echo formatPrice($product['sale_price']); ?></small>
            <?php endif; ?>
            <a href=""><i class="fa-solid fa-cart-shopping"></i></a>
            <a href=""><i class="fa-regular fa-heart"></i></a>
            <a href=""><i class="fa-regular fa-eye"></i></a>
        </div>
        <?php endforeach; ?>
    </div>
</section> 