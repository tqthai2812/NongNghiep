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
                <img id="mainProductImg"
                    src="{{ asset('storage/' . ($product->primaryImage->image_url ?? 'default.jpg')) }}"
                    class="product-detail-main-img" alt="{{ $product->name }}">

                <div class="product-detail-thumb-slider">
                    <button class="product-detail-nav-btn product-detail-prev" onclick="moveProductSlider(-1)">&#10094;</button>

                    <div class="product-detail-thumb-window">
                        <div id="productThumbTrack" class="product-detail-thumb-track">
                            @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->image_url) }}" class="product-detail-thumb-item {{ $loop->first ? 'active' : '' }}" onclick="updateMainImg(this)">
                            @endforeach
                        </div>
                    </div>

                    <button class="product-detail-nav-btn product-detail-next" onclick="moveProductSlider(1)">&#10095;</button>
                </div>
            </div>

            <div class="col-md-7 ps-md-5" style="height: 547px;">
                <div class="d-flex flex-column justify-content-between h-100">
                    <div class="d-flex flex-column">
                        <h1 class="product-detail-title mb-3">{{ $product->name }}</h1>
                        <div class="d-flex flex-row gap-4 mb-3">
                            <div class="d-flex flex-row">
                                {{ number_format($averageRating, 1) }}
                                <p class="ms-1 text-secondary">
                                    <i class="fa-solid fa-star text-warning"></i>
                                </p>
                            </div>

                            <a href="#reviewList" class="d-flex flex-row border-start px-3 border-end text-dark text-decoration-none">
                                {{ $totalReviews }}
                                <p class="ms-1 text-secondary"> Đánh Giá</p>
                            </a>

                            <div class="d-flex flex-row">
                                <p class="me-1 text-secondary">Đã Bán</p>
                                {{ $totalSold ?? 0 }}
                            </div>
                        </div>
                        <div class="product-detail-price-box">
                            @php
                            // Lấy giá thấp nhất từ tất cả các loại đóng gói
                            $minPrice = $product->packageTypes->flatMap->packages->min('price');
                            @endphp
                            <span class="product-detail-price-text">{{ number_format($minPrice) }}₫</span>
                        </div>
                    </div>

                    <div class="d-flex flex-column">
                        <div class="d-flex align-items-start mb-4">
                            <div class="product-detail-label me-3">Phân loại</div>
                            <div class="d-flex flex-wrap gap-2" id="packageOptions">
                                @foreach($product->packageTypes as $type)
                                @foreach($type->packages as $package)
                                <button type="button"
                                    class="btn product-detail-btn-outline package-option-btn"
                                    data-id="{{ $package->id }}"
                                    data-price="{{ number_format($package->price, 0, ',', '.') }}₫"
                                    data-stock="{{ $package->stock }}"
                                    data-full-name="{{ $package->full_name }}">
                                    {{ $type->type_name }} {{ $package->size }}{{ $package->unit }}
                                </button>
                                @endforeach
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <div class="product-detail-label">Số Lượng</div>
                            <div class="d-flex flex-column">
                                <div class="input-group" style="width:150px;">
                                    <button class="btn btn-outline-secondary" onclick="changeQty(-1)">-</button>
                                    <input id="qtyInput" type="number" class="form-control text-center" value="1" min="1">
                                    <button class="btn btn-outline-secondary" onclick="changeQty(1)">+</button>
                                </div>
                                <div id="stockDisplay" class="text-muted small mt-2" style="display: none;">
                                    <span id="stockCount">0</span> sản phẩm có sẵn
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-3 mt-3">
                            <button id="addToCartBtn" class="btn product-detail-btn-add px-4 py-2">
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
                {{ $product->category->name }}
            </div>
        </div>

        <div class="row product-info-item">
            <div class="col-3 product-info-label">Kho</div>
            <div class="col-9">CÒN HÀNG</div>
        </div>

        <div class="row product-info-item">
            <div class="col-3 product-info-label">Thương hiệu</div>
            <div class="col-9 text-primary">{{ $product->brand }}</div>
        </div>

        <h5 class="product-info-title">MÔ TẢ SẢN PHẨM</h5>
        <div class="row product-info-item">
            <p style="text-align: justify;">{{ $product->description }}</p>
        </div>

    </div>

    <div class="container product-detail-card mt-4 shadow-sm">

        <h5 class="mb-4">ĐÁNH GIÁ SẢN PHẨM ({{ $totalReviews }})</h5>

        <div class="review-summary d-flex align-items-center gap-4 mb-4">

            <div class="review-score text-center">
                <div class="score-number">{{ number_format($averageRating, 1) }}</div>
                <div class="score-text">trên 5</div>
                <div class="review-stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <=round($averageRating))
                        ★
                        @else
                        ☆
                        @endif
                        @endfor
                        </div>
                </div>

                <ul class="nav review-tabs">
                    <li class="nav-item">
                        <button class="nav-link active review-tab" data-star="all">
                            Tất Cả ({{ $totalReviews }})
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link review-tab" data-star="5">
                            5 Sao ({{ $ratingCounts[5] }})
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link review-tab" data-star="4">
                            4 Sao ({{ $ratingCounts[4] }})
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link review-tab" data-star="3">
                            3 Sao ({{ $ratingCounts[3] }})
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link review-tab" data-star="2">
                            2 Sao ({{ $ratingCounts[2] }})
                        </button>
                    </li>

                    <li class="nav-item">
                        <button class="nav-link review-tab" data-star="1">
                            1 Sao ({{ $ratingCounts[1] }})
                        </button>
                    </li>
                </ul>

            </div>

            <div id="reviewList">
                @forelse($allReviews as $review)
                <div class="review-item" data-star="{{ $review->rating }}">
                    <div class="d-flex align-items-start mb-2">
                        <img src="{{ $review->user->avatar ? asset('storage/' . $review->user->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($review->user->user_name ?? 'User') }}"
                            alt="Avatar" class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">

                        <div>
                            <strong>{{ $review->user->user_name ?? 'Khách hàng ẩn danh' }}</strong>
                            <div class="text-danger">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <=$review->rating)
                                    ★
                                    @else
                                    ☆
                                    @endif
                                    @endfor
                            </div>
                            <div class="text-muted small mt-1">
                                {{ $review->created_at->format('Y-m-d H:i') }} | Phân loại hàng: {{ $review->package_full_name }}
                            </div>
                        </div>
                    </div>

                    <p class="mt-2 text-dark">{{ $review->comment }}</p>
                </div>
                @empty
                <div class="text-center p-4 text-muted">
                    Chưa có đánh giá nào cho sản phẩm này. Hãy là người đầu tiên đánh giá!
                </div>
                @endforelse
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

        const packageBtns = document.querySelectorAll("#packageOptions button");

        packageBtns.forEach(btn => {
            btn.addEventListener("click", function() {

                packageBtns.forEach(b => b.classList.remove("active"));

                this.classList.add("active");

                // const weight = this.dataset.weight;

                // console.log("Trọng lượng:", weight);
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


        document.addEventListener('DOMContentLoaded', function() {
            let selectedPackageId = null;
            const packageBtns = document.querySelectorAll(".package-option-btn");
            const priceText = document.querySelector(".product-detail-price-text");
            const stockDisplay = document.getElementById("stockDisplay");
            const stockCount = document.getElementById("stockCount");
            const qtyInput = document.getElementById("qtyInput");

            packageBtns.forEach(btn => {
                btn.addEventListener("click", function() {
                    selectedPackageId = this.dataset.id;
                    // 1. Cập nhật UI nút được chọn
                    packageBtns.forEach(b => b.classList.remove("active"));
                    this.classList.add("active");

                    // 2. Lấy dữ liệu từ data attributes
                    const price = this.dataset.price;
                    const stock = parseInt(this.dataset.stock);

                    // 3. Cập nhật hiển thị giá và kho
                    priceText.innerText = price;
                    stockCount.innerText = stock;
                    stockDisplay.style.display = "block";

                    // 4. Kiểm tra số lượng nhập hiện tại so với kho mới
                    if (parseInt(qtyInput.value) > stock) {
                        qtyInput.value = stock;
                    }
                    if (stock <= 0) {
                        qtyInput.value = 0;
                        qtyInput.disabled = true;
                    } else {
                        if (qtyInput.value == 0) qtyInput.value = 1;
                        qtyInput.disabled = false;
                    }

                    // Cập nhật thuộc tính max cho input
                    qtyInput.setAttribute("max", stock);
                });
            });
            // Xử lý click nút Thêm vào giỏ hàng
            const addToCartBtn = document.getElementById("addToCartBtn");
            addToCartBtn.addEventListener("click", function() {
                if (!selectedPackageId) {
                    alert("Vui lòng chọn phân loại sản phẩm!");
                    return;
                }

                const quantity = document.getElementById("qtyInput").value;

                // Gửi Ajax
                fetch("{{ route('user.cart.add') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            package_id: selectedPackageId,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            alert(data.message);
                            // Bạn có thể cập nhật số lượng trên icon giỏ hàng ở đây
                        } else {
                            alert(data.message || "Có lỗi xảy ra!");
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Vui lòng đăng nhập để thực hiện chức năng này!");
                    });
            });
        });

        // Hàm thay đổi số lượng có kiểm tra tồn kho
        function changeQty(num) {
            const input = document.getElementById("qtyInput");
            const maxStock = parseInt(input.getAttribute("max")) || 999;
            let value = parseInt(input.value) || 1;

            value += num;

            if (value < 1) value = 1;
            if (value > maxStock) {
                value = maxStock;
                alert("Rất tiếc, chỉ còn " + maxStock + " sản phẩm trong kho.");
            }

            input.value = value;
        }
    </script>
    @endpush