@extends('user.layouts.default')

@section('title', 'Product Detail')

@push('page_specific_css')
<style>
    :root {
        --product-main-orange: #097829;
    }

    /* Khung chứa chính */
    .product-detail-card {
        background: #fff;
        padding: 25px;
        border-radius: 4px;
    }

    /* Ảnh lớn */
    .product-detail-main-img {
        width: 100%;
        height: 450px;
        object-fit: contain;
        border: 1px solid #f0f0f0;
        background: #fff;
    }

    /* Slider ảnh nhỏ */
    .product-detail-thumb-slider {
        position: relative;
        display: flex;
        align-items: center;
        margin-top: 15px;
        padding: 0 30px;
    }

    .product-detail-thumb-window {
        overflow: hidden;
        width: 100%;
    }

    .product-detail-thumb-track {
        display: flex;
        gap: 10px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .product-detail-thumb-item {
        width: 82px;
        height: 82px;
        flex-shrink: 0;
        object-fit: cover;
        border: 2px solid transparent;
        cursor: pointer;
    }

    .product-detail-thumb-item.active {
        border-color: var(--product-main-orange);
    }

    /* Nút điều hướng */
    .product-detail-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.1);
        color: #fff;
        border: none;
        width: 25px;
        height: 40px;
        z-index: 5;
        transition: 0.2s;
    }

    .product-detail-nav-btn:hover {
        background: rgba(0, 0, 0, 0.3);
    }

    .product-detail-prev {
        left: 0;
    }

    .product-detail-next {
        right: 0;
    }

    /* Thông tin bên phải */
    .product-detail-title {
        font-size: 1.25rem;
        font-weight: 400;
        line-height: 1.5;
    }

    .product-detail-price-box {
        background-color: #fafafa;
        padding: 15px 20px;
        margin: 20px 0;
    }

    .product-detail-price-text {
        color: var(--product-main-orange);
        font-size: 1.875rem;
        font-weight: 500;
    }

    /* Label và Input */
    .product-detail-label {
        color: #757575;
        width: 110px;
        flex-shrink: 0;
    }

    .product-detail-btn-outline {
        border: 1px solid #ddd;
        background: white;
        padding: 6px 14px;
        font-size: 14px;
    }

    .product-detail-btn-outline:hover {
        border-color: #097829;
        color: #097829;
    }

    .product-detail-btn-outline.active {
        border-color: #097829;
        color: #097829;
        background: white;
    }

    /* Nút mua hàng */
    .product-detail-btn-add {
        background: white;
        border: 1px solid var(--product-main-orange);
        color: #097829;
    }

    .product-detail-btn-add:hover {
        background: rgba(238, 77, 45, 0.08);
        border: 1px solid var(--product-main-orange);
        color: #097829;
        opacity: 0.7;
    }

    .product-detail-btn-buy {
        background: #097829;
        color: #fff;
        border: none;
    }

    .product-detail-btn-buy:hover {
        background: #097829;
        color: #fff;
        border: none;
        opacity: 0.7;
    }

    /* CHi tiết CSS cho trang chi tiết sản phẩm sẽ nằm trong file product.blade.php */
    .product-info-title {
        background: #f5f5f5;
        padding: 10px 15px;
        font-weight: 600;
        margin-bottom: 20px;
    }

    .product-info-item {
        margin-bottom: 14px;
    }

    .product-info-label {
        color: #888;
    }

    .review-summary {
        background: #fff6f5;
        padding: 20px;
    }

    .score-number {
        font-size: 32px;
        color: #ee4d2d;
        font-weight: 600;
    }

    .review-stars {
        color: #ee4d2d;
    }

    .review-tabs .nav-link {
        border: 1px solid #ddd;
        margin-right: 8px;
    }

    .review-tabs .nav-link.active {
        border-color: #ee4d2d;
        color: #ee4d2d;
    }

    .review-item {
        border-top: 1px solid #eee;
        padding: 16px 0;
    }
</style>
@endpush

@section('content')
<div id="wp-content" class="bg-body-tertiary pt-2 pb-4" style="margin-top: 57px;">
    <div class="container product-detail-card shadow-sm mt-4">
        <div class="row align-items-stretch">
            <div class="col-md-5">
                <img id="mainProductImg" src="https://picsum.photos/id/11/500/500" class="product-detail-main-img" alt="Product">

                <div class="product-detail-thumb-slider">
                    <button class="product-detail-nav-btn product-detail-prev" onclick="moveProductSlider(-1)">&#10094;</button>

                    <div class="product-detail-thumb-window">
                        <div id="productThumbTrack" class="product-detail-thumb-track">
                            <img src="https://picsum.photos/id/11/500/500" class="product-detail-thumb-item active" onclick="updateMainImg(this)">
                            <img src="https://picsum.photos/id/12/500/500" class="product-detail-thumb-item" onclick="updateMainImg(this)">
                            <img src="https://picsum.photos/id/13/500/500" class="product-detail-thumb-item" onclick="updateMainImg(this)">
                            <img src="https://picsum.photos/id/14/500/500" class="product-detail-thumb-item" onclick="updateMainImg(this)">
                            <img src="https://picsum.photos/id/15/500/500" class="product-detail-thumb-item" onclick="updateMainImg(this)">
                            <img src="https://picsum.photos/id/16/500/500" class="product-detail-thumb-item" onclick="updateMainImg(this)">
                            <img src="https://picsum.photos/id/17/500/500" class="product-detail-thumb-item" onclick="updateMainImg(this)">
                            <img src="https://picsum.photos/id/17/500/500" class="product-detail-thumb-item" onclick="updateMainImg(this)">
                        </div>
                    </div>

                    <button class="product-detail-nav-btn product-detail-next" onclick="moveProductSlider(1)">&#10095;</button>
                </div>
            </div>

            <div class="col-md-7 ps-md-5" style="height: 547px;">
                <div class="d-flex flex-column justify-content-between h-100">
                    <div class="d-flex flex-column">
                        <h1 class="product-detail-title mb-3">Phân bón NPK 20-20-15+TE (tan nhanh) chuyên rau màu, cây ăn trái, cây cảnh - Gói 500gr, 1kg</h1>
                        <div class="d-flex flex-row gap-4 mb-3">
                            <div class="d-flex flex-row">qthai <p class="ms-1 text-secondary"><i class="fa-solid fa-star text-warning"></i></p>
                            </div>
                            <a href="#" class="d-flex flex-row border-start px-3 border-end text-dark">100 <p class="ms-1 text-secondary"> Đánh Giá</p>
                            </a>
                            <div class="d-flex flex-row">
                                <p class="me-1 text-secondary">Đã Bán</p> 10k
                            </div>
                        </div>
                        <div class="product-detail-price-box">
                            <span class="product-detail-price-text">28.000₫</span>
                        </div>
                    </div>

                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-start mb-4">
                            <div class="product-detail-label me-3">Trọng Lượng</div>

                            <div class="d-flex flex-wrap gap-2" id="weightOptions">
                                <button class="btn product-detail-btn-outline active" data-weight="1kg">1kg</button>
                                <button class="btn product-detail-btn-outline" data-weight="500g">500g</button>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <div class="product-detail-label">Số Lượng</div>
                            <div class="input-group" style="width:150px;">
                                <button class="btn btn-outline-secondary" onclick="changeQty(-1)" style="border: rgb(222, 226, 230) 1px solid;">-</button>
                                <input id="qtyInput" type="number" class="form-control text-center" value="1" min="1">
                                <button class="btn btn-outline-secondary" onclick="changeQty(1)" style="border: rgb(222, 226, 230) 1px solid;">+</button>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-3">
                            <button class="btn product-detail-btn-add px-4 py-2">
                                <i class="fa-solid fa-cart-arrow-down me-1"></i>Thêm Vào Giỏ Hàng
                            </button>
                            <button class="btn product-detail-btn-buy px-5 py-2">Mua Ngay</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="product-detail-card shadow-sm mt-4 container">
        <h5 class="product-info-title">CHI TIẾT SẢN PHẨM</h5>

        <div class="row product-info-item">
            <div class="col-3 product-info-label">Danh Mục</div>
            <div class="col-9">
                <a href="#">Shopee</a> >
                <a href="#">Nhà Cửa & Đời Sống</a> >
                <a href="#">Ngoài trời & Sân vườn</a> >
                <a href="#">Phân bón</a>
            </div>
        </div>

        <div class="row product-info-item">
            <div class="col-3 product-info-label">Kho</div>
            <div class="col-9">CÒN HÀNG</div>
        </div>

        <div class="row product-info-item">
            <div class="col-3 product-info-label">Thương hiệu</div>
            <div class="col-9 text-primary">ACACIA BEDDING</div>
        </div>

        <div class="row product-info-item">
            <div class="col-3 product-info-label">Loại phân bón</div>
            <div class="col-9">Tổng hợp</div>
        </div>

        <div class="row product-info-item">
            <div class="col-3 product-info-label">Loại bảo hành</div>
            <div class="col-9">Không bảo hành</div>
        </div>

        <div class="row product-info-item">
            <div class="col-3 product-info-label">Xuất xứ</div>
            <div class="col-9">Mỹ</div>
        </div>

        <div class="row product-info-item">
            <div class="col-3 product-info-label">Ngày sản xuất</div>
            <div class="col-9">04-11-2024</div>
        </div>

        <div class="row product-info-item">
            <div class="col-3 product-info-label">Gửi từ</div>
            <div class="col-9">Huyện Bắc Tân Uyên, Bình Dương</div>
        </div>

        <h5 class="product-info-title">MÔ TẢ SẢN PHẨM</h5>
        <div class="row product-info-item">
            <p style="text-align: justify;">Được thành lập từ năm 2013, Sfarm là thương hiệu hàng đầu tại Việt Nam về sản xuất và phân phối các loại phân bón hữu cơ, giá thể, đất sạch hữu cơ, mụn dừa xử lý,… nhằm đáp ứng nhu cầu nông nghiệp sạch, bền vững tại thành thị cũng như nông thôn. Hiện nay Sfarm cho ra đời sản phẩm mật rỉ đường. Mật rỉ đường Sfarm hay còn gọi là rỉ mật, mật rỉ là chất lỏng đặc sánh còn lại sau khi đã rút đường bằng phương pháp cô đặc và kết tinh. Đây là sản phẩm phụ của công nghiệp chế biến đường mía.</p>
        </div>

    </div>

    <div class="container product-detail-card mt-4 shadow-sm">

        <h5 class="mb-4">ĐÁNH GIÁ SẢN PHẨM</h5>

        <!-- Tổng điểm -->
        <div class="review-summary d-flex align-items-center gap-4 mb-4">

            <div class="review-score text-center">
                <div class="score-number">4.9</div>
                <div class="score-text">trên 5</div>
                <div class="review-stars">★★★★★</div>
            </div>

            <!-- Tabs lọc -->
            <ul class="nav review-tabs">
                <li class="nav-item">
                    <button class="nav-link active review-tab" data-star="all">
                        Tất Cả
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link review-tab" data-star="5">
                        5 Sao (1100)
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link review-tab" data-star="4">
                        4 Sao (84)
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link review-tab" data-star="3">
                        3 Sao (17)
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link review-tab" data-star="2">
                        2 Sao (6)
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link review-tab" data-star="1">
                        1 Sao (12)
                    </button>
                </li>
            </ul>

        </div>

        <!-- Danh sách review -->
        <div id="reviewList">

            <div class="review-item" data-star="5">
                <strong>m*****2</strong>
                <div class="text-danger">★★★★★</div>
                <div class="text-muted small">2025-09-10 | Phân loại: 500gr</div>
                <p>Phân bón tốt, cây phát triển nhanh.</p>
            </div>

            <div class="review-item" data-star="4">
                <strong>a*****1</strong>
                <div class="text-danger">★★★★</div>
                <div class="text-muted small">2025-09-08</div>
                <p>Sản phẩm ổn.</p>
            </div>

        </div>

    </div>
</div>
@endsection

@push('page_specific_js')
<script>
    let currentSlideIndex = 0;
    const thumbTrack = document.getElementById('productThumbTrack');
    const thumbItems = document.querySelectorAll('.product-detail-thumb-item');
    const itemWidth = 82 + 10; // Rộng ảnh + gap
    const displayCount = 5;
    const maxSlideIndex = thumbItems.length - displayCount;

    function updateMainImg(el) {
        document.getElementById('mainProductImg').src = el.src;
        thumbItems.forEach(item => item.classList.remove('active'));
        el.classList.add('active');
    }

    function moveProductSlider(direction) {
        currentSlideIndex += direction;

        // Chống trượt lố
        if (currentSlideIndex < 0) currentSlideIndex = 0;
        if (currentSlideIndex > maxSlideIndex) currentSlideIndex = maxSlideIndex;

        const moveDistance = -(currentSlideIndex * itemWidth);
        thumbTrack.style.transform = `translateX(${moveDistance}px)`;
    }

    function changeQty(num) {
        let input = document.getElementById("qtyInput");
        let value = parseInt(input.value) || 1;

        value += num;

        if (value < 1) value = 1;

        input.value = value;
    }

    const weightBtns = document.querySelectorAll("#weightOptions button");

    weightBtns.forEach(btn => {
        btn.addEventListener("click", function() {

            weightBtns.forEach(b => b.classList.remove("active"));

            this.classList.add("active");

            const weight = this.dataset.weight;

            console.log("Trọng lượng:", weight);
        });
    });

    document.querySelectorAll(".review-tab").forEach(tab => {

        tab.addEventListener("click", function() {

            document.querySelectorAll(".review-tab")
                .forEach(t => t.classList.remove("active"))

            this.classList.add("active")

            let star = this.dataset.star

            document.querySelectorAll(".review-item")
                .forEach(item => {

                    if (star === "all" || item.dataset.star === star) {
                        item.style.display = "block"
                    } else {
                        item.style.display = "none"
                    }

                })

        })

    })
</script>
@endpush