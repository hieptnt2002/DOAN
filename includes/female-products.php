<?php
require_once 'functions.php';

$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$products_per_page = 8;

$female_products = getFemaleProducts($conn, $current_page, $products_per_page);
$total_products = getTotalFemaleProducts($conn);
$banner = getBanner($conn, 2);
?>

<section class="female">
    <div class="female-banner">
        <h1>NƯỚC HOA NỮ</h1>
        <img src="<?php echo $banner['image']; ?>" alt="Banner nước hoa nữ" width="85%" height="400" style="position: relative; left: 100px;">
    </div>
    
    <div class="products-female">
        <?php foreach($female_products as $product): ?>
        <div class="products-item-female">
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