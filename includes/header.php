<header class="header">
    <a href="index.php" class="logo">
        <img src="./image/Hồng ân.png" alt="">
    </a>
    <nav class="navbar">
        <a href="index.php">Trang Chủ</a>
        <a href="about.php">Giới Thiệu</a>
        <a href="brands.php">Thương Hiệu</a>
        <a href="perfumes.php">Nước Hoa</a>
        <a href="samples.php">Nước Hoa Chiết</a>
        <a href="knowledge.php">Kiến Thức</a>
        <a href="contact.php">Liên Hệ</a>
    </nav>
    <div class="icons">
        <div class="search-icon">
            <i class="fa fa-search" id="search-btn"></i>
            <div class="search-form">
                <form action="" method="GET">
                    <input type="text" name="keyword" placeholder="Tìm kiếm thương hiệu..." required>
                    <button type="submit"><i class="fa fa-search"></i></button>
                </form>
                <div class="search-results"></div>
            </div>
        </div>
        <a href="login.php"><i class="fa fa-user"></i></a>
        <a href="wishlist.php"><i class="fa fa-star"></i></a>
        <a href="cart.php"><i class="fa fa-cart-shopping"></i></a>
    </div>
</header>

<script>
$(document).ready(function() {

    $('#search-btn').click(function(e) {
        e.stopPropagation();
        $('.search-form').toggleClass('active');
    });

   
    $(document).click(function(e) {
        if (!$(e.target).closest('.search-icon').length) {
            $('.search-form').removeClass('active');
        }
    });

    
    $('.search-form').click(function(e) {
        e.stopPropagation();
    });

    
    let searchTimeout;
    $('.search-form input').on('input', function() {
        clearTimeout(searchTimeout);
        const keyword = $(this).val();
        
        if (keyword.length >= 2) {
            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: 'includes/search_ajax.php',
                    method: 'GET',
                    data: { keyword: keyword },
                    success: function(response) {
                        $('.search-results').html(response).show();
                    }
                });
            }, 300);
        } else {
            $('.search-results').empty().hide();
        }
    });
});
</script> 