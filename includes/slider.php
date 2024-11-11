<section class="slider-image-run">
    <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active" data-bs-interval="3000">
                <img src="./image/slider_timezone_image_desk.webp" class="d-block w-100" alt="Slider 1">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="./image/slideshow_3.webp" class="d-block w-100" alt="Slider 2">
            </div>
            <div class="carousel-item" data-bs-interval="3000">
                <img src="./image/slideshow_4.webp" class="d-block w-100" alt="Slider 3">
            </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselExampleInterval" data-bs-slide-to="2"></button>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var myCarousel = new bootstrap.Carousel(document.getElementById('carouselExampleInterval'), {
        interval: 3000,
        wrap: true
    });
});
</script> 