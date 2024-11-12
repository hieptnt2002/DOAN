<?php
$total_pages = ceil($total_products / $products_per_page);
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
?>

<section class="page-next">
    <nav class="mt-8">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $current_page - 1; ?>" <?php echo ($current_page <= 1) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>Trang Trước</a>
            </li>
            
            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($current_page == $i) ? 'active' : ''; ?>" <?php echo ($current_page == $i) ? 'aria-current="page"' : ''; ?>>
                    <a class="page-link" href="?page=<?php echo $i; ?>">
                        <?php echo $i; ?>
                        <?php if($current_page == $i): ?>
                            <span class="sr-only">(current)</span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endfor; ?>
            
            <li class="page-item <?php echo ($current_page >= $total_pages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $current_page + 1; ?>" <?php echo ($current_page >= $total_pages) ? 'tabindex="-1" aria-disabled="true"' : ''; ?>>Trang Sau</a>
            </li>
        </ul>
    </nav>
</section> 