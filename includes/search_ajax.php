<?php
require_once '../config/db.php';
require_once 'functions.php';

if (isset($_GET['keyword'])) {
    $keyword = trim($_GET['keyword']);
    
    if (strlen($keyword) >= 2) {
        $stmt = $conn->prepare("SELECT * FROM brands 
                WHERE name LIKE :keyword 
                OR description LIKE :keyword 
                LIMIT 5");
                
        $search = "%{$keyword}%";
        $stmt->bindParam(':keyword', $search, PDO::PARAM_STR);
        $stmt->execute();
        $brands = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($brands)) {
            foreach ($brands as $brand) {
                echo '<div class="search-item">';
                if (!empty($brand['image'])) {
                    echo '<img src="' . htmlspecialchars($brand['image']) . '" alt="' . htmlspecialchars($brand['name']) . '">';
                } else {
                    echo '<img src="image/default-brand.jpg" alt="Default Image">';
                }
                echo '<div class="search-item-info">';
                echo '<h4>' . htmlspecialchars($brand['name']) . '</h4>';
                if (!empty($brand['description'])) {
                    echo '<p class="brand">' . mb_substr(htmlspecialchars($brand['description']), 0, 50) . '...</p>';
                }
                echo '</div>';
                echo '<a href="brand_detail.php?id=' . $brand['id'] . '" class="view-btn">Xem</a>';
                echo '</div>';
            }
            echo '<a href="brands.php?keyword=' . urlencode($keyword) . '" class="view-all">Xem tất cả thương hiệu</a>';
        } else {
            echo '<div class="no-results">Không tìm thấy thương hiệu nào</div>';
        }
    }
}
?> 