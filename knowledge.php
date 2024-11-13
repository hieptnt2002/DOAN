<?php require_once 'config/db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kiến Thức Nước Hoa</title>
    <?php include 'includes/head.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .knowledge-banner img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        
        .knowledge-section {
            padding: 60px 0;
            background: #f8f9fa;
        }
        
        .knowledge-card {
            height: 100%;
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .knowledge-card:hover {
            transform: translateY(-5px);
        }
        
        .knowledge-card img {
            height: 300px;
            object-fit: cover;
        }
        
        .knowledge-card .card-title {
            font-family: 'Times New Roman', Times, serif;
            font-size: 20px;
            font-weight: bold;
            margin: 15px 0;
            text-align: center;
        }
        
        .knowledge-card .card-text {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            text-align: justify;
            padding: 0 15px;
        }
        
        .product-section {
            padding: 60px 0;
        }
        
        .product-card {
            text-align: center;
            height: 100%;
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-card img {
            height: 300px;
            object-fit: cover;
        }
        
        .product-card .card-title {
            margin: 15px 0;
            font-weight: bold;
        }
        
        .product-card .price {
            color: #e74c3c;
            font-weight: bold;
            font-size: 18px;
            margin: 10px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 40px;
            font-size: 28px;
            font-weight: bold;
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <!-- Banner Slider -->
    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="3000">
                <img src="./image/pexels-didsss-1190829.jpg" class="d-block w-100" alt="..." width="500" height="400">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="./image/pexels-valeriiamiller-3910071.jpg" class="d-block w-100" alt="..." width="500" height="400">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="./image/pexels-valeriya-1961795.jpg" class="d-block w-100" alt="..." width="500" height="400">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Kiến Thức Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-4">Thông Tin</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="./image/5fee2a02c330a52524cc2b19a4a2ec5b.jpg" alt="" height="400">
                        <div class="card-body">
                            <h5 class="card-title" style="font-family:'Times New Roman', Times, serif; font-size: 20px; font-weight:bolder">[Giải Đáp] Địa Chỉ Mua Nước Hoa uy tín, chất lượng ở đâu?</h5>
                            <p class="card-text">Chọn địa chỉ mua nước hoa uy tín được khá nhiều khách hàng quan tâm và thắc mắc.</p>
                            <p class="card-text">Bởi vì chúng ta đều biết rằng nước ta là "chân ái" của nhiều người. Nhưng Lại có rất nhiều các địa chỉ trên khắp nước cung cấp không đảm bảo bảo hành chính hãng và cũng thay vào đó có nhiều nơi rất uy tín và chất lượng,....</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <img src="./image/LEMONMELON_PERFUMEBOX-768x768.webp" alt="" height="415">
                        <div class="card-body">
                            <h5 class="card-title" style="font-family:'Times New Roman', Times, serif; font-size: 20px;text-align:center; align-items:center; justify-content:center; font-weight:bolder">Lựa Chọn mẫu nước hoa mùi nhẹ nhàng cho nữ</h5>
                            <p class="card-text">Nước Hoa Mùi nhẹ nhàng Cho Nữ Là Điều mà nhiều cô nàng tìm kiếm. Bởi vì ngoài những cô nàng cá tính yêu thích mùi nước hoa quyến rũ.</p>
                            <p class="card-text">Thì vẫn còn có nhiều chị em mê mẫn, say đắm các nước hoa có mùi nhẹ nhàng.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <img src="./image/088fe9f2b0200927a03973f3743ab060.jpg" alt="" height="415">
                        <div class="card-body">
                            <h5 class="card-title" style="font-family:'Times New Roman', Times, serif; font-size: 20px;text-align:center; align-items:center; justify-content:center; font-weight:bolder">Những mùi nước hoa được ưu chuộng nhất dành cho nam và nữ</h5>
                            <p class="card-text">Top những mùi nước hoa được ưu chuộng nhất dành cho cả nam và nữ. chắc chắn rằng một tín đồ của nước hoa bạn không thể bỏ qua những lựa chọn này.</p>
                            <p class="card-text">Mỗi lần ra ngoài, bản thân không chỉ đẹp, ấn tượng với phong cách thời trang mà còn là vẻ đẹp bề ngoài của những nam sinh nữ tú.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <nav class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><<</a>
                </li>
                <li class="page-item active">
                    <a class="page-link" href="#">1<span class="sr-only">(current)</span></a>
                </li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">>></a>
                </li>
            </ul>
        </nav>
    </section>

    <!-- Sản Phẩm Section -->
    <section class="py-6 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Sản Phẩm Và Dịch Vụ</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="./image/anrh-bay.webp" alt="">
                        <div class="card-body">
                            <h5 class="card-title" style="text-align:center">NOT A ROSE</h5>
                            <p class="card-price" style="font-family:Arial, Helvetica, sans-serif; font-size: 15px; font-weight: bolder; text-align:center; color: red">1.600.000<sup>đ</sup></p>
                            <p class="card-text">Hoa Hồng là một trong những hình ảnh đầu tiên mà ta dễ nghĩ tới khi nhắc đến mùi hương.</p>
                            <p class="card-text">Được ví như quyền lực của sự dịu dàng. Not A Rose là mùi hồng nhưng không điều chế từ hoa hồng. Ký ức và giá trị ẩn sâu bên trong một nhánh hoa hồng đơn sơ.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <img src="./image/tải xuống (3).jfif" alt="">
                        <div class="card-body">
                            <h5 class="card-title" style="text-align:center">Burberry London EDT 100ml</h5>
                            <p class="card-price" style="font-family:Arial, Helvetica, sans-serif; font-size: 15px; font-weight: bolder; text-align:center; color: red">3.450.000<sup>đ</sup></p>
                            <p class="card-text">Là Bậc Thầy nước hoa Alberto Morillas đã tạo nên thương hiệu giữa hổ phách và da thuộc với các hương vị làm ngọt để mang lại cảm giác và nam tính.</p>
                            <p class="card-text">Phảng phất dưới lớp hương đó chính là âm hưởng của da thuộc đậm chất với an tức hương, đậu tonka và gỗ guagic.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <img src="./image/nuoc-hoa-nam-chanel-bleu-edp-100ml-620600b02a082-11022022132240.webp" alt="">
                        <div class="card-body">
                            <h5 class="card-title" style="text-align:center">Nước Hoa Nam Chanel Bleu EDT 100ml</h5>
                            <p class="card-price" style="font-family:Arial, Helvetica, sans-serif; font-size: 15px; font-weight: bolder; text-align:center; color: red">4.750.000<sup>đ</sup></p>
                            <p class="card-text">Là mùi hương đặc trưng chính là điểm làm nên sản phẩm Bleu de Chanel, ẩn bên trong là hương vị của gió biển, mang đậm phong cách cá tính thể thao, mạnh mẽ.</p>
                            <p class="card-text">Đặc biệt, hương vị tự nhiên của biển sẽ trở nên thường xuyên.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pagination -->
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1" aria-disabled="true"><<</a>
            </li>
            <li class="page-item active">
                <a class="page-link" href="#">1<span class="sr-only">(current)</span></a>
            </li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">4</a></li>
            <li class="page-item">
                <a class="page-link" href="#">>></a>
            </li>
        </ul>
    </nav>

    <?php include 'includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 
</html> 