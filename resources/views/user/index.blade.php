@extends('user.layouts.default')

@section('title', 'Trang chủ')

@push('page_specific_css')
<style>
    /* Custum CSS cho section giới thiệu */
    .section-padding {
        padding: 80px 0;
    }

    .brand-title {
        color: #198754;
        font-weight: 700;
    }

    .image-frame {
        border: 8px solid #dfead3;
        padding: 20px;
        background: #fff;
    }

    .img-overlay {
        position: absolute;
        bottom: -30px;
        right: -30px;
        width: 60%;
        /* box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); */
    }

    .read-more {
        color: #198754;
        font-weight: 600;
        text-decoration: none;
    }

    .read-more:hover {
        text-decoration: underline;
    }

    .p-justify {
        text-align: justify;
        text-align-last: left;
    }

    /* Custum CSS cho section vì sao chọn */
    .why-section {
        background: url("{{ asset('assets/img/img_bn/bn_why.jpg') }}") center center/cover no-repeat;
        position: relative;
        padding: 80px 0;
        color: white;
    }

    .why-section::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 120, 0, 0.65);
        /* lớp phủ xanh */
    }

    .why-section .container {
        position: relative;
        z-index: 2;
    }

    .why-title {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .divider {
        width: 120px;
        height: 2px;
        background: white;
        margin: 15px auto 40px;
        position: relative;
    }

    .divider::after {
        content: "✿";
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        top: -14px;
        background: transparent;
        padding: 0 10px;
        font-size: 18px;
    }

    .icon-box {
        text-align: center;
        padding: 20px;
    }

    .icon-circle {
        width: 90px;
        height: 90px;
        background: white;
        color: #198754;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 35px;
        margin: 0 auto 20px;
    }

    .icon-box h5 {
        font-weight: 700;
        margin-bottom: 15px;
    }

    .icon-box p {
        font-size: 15px;
        line-height: 1.6;
    }

    @media (max-width: 991px) {
        .icon-box {
            margin-bottom: 40px;
        }
    }

    /* custom CSS cho slider sản phẩm  */
    /* ====== DGT PRODUCT SECTION ====== */
    .dgt-product-section {
        background: #f3f3f3;
    }

    .dgt-title {
        font-weight: 700;
        color: #1b8a3a;
        text-transform: uppercase;
    }

    .dgt-divider {
        width: 80px;
        height: 3px;
        background: #1b8a3a;
        margin: 12px auto 40px;
    }

    .dgt-product-card {
        text-align: center;
        transition: 0.3s ease;
    }

    .dgt-product-card img {
        max-height: 250px;
        object-fit: contain;
        transition: 0.3s ease;
    }

    .dgt-product-card:hover img {
        transform: translateY(-8px);
    }

    .dgt-product-title {
        margin-top: 20px;
        color: #198754;
        font-weight: 600;
        font-size: 16px;
    }

    .dgt-product-desc {
        color: #555;
        font-size: 14px;
    }

    .dgt-product-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .dgt-product-link:hover {
        color: inherit;
    }
</style>
@endpush
@section('content')
<div id="wp-content" class="bg-body-tertiary" style="margin-top: 57px;">
    <div class="bg-header-content">
        <div id="carouselheader" class="carousel slide mx-auto mb-3" style="width: 85%;">
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="3000">
                    <a href="#" class="d-flex justify-content-between">
                        <img src="{{ asset('assets/img/img_bn/bg_1.png') }}" class="d-block w-100" alt="...">
                    </a>
                </div>
                <div class="carousel-item" data-bs-interval="3000">
                    <a href="#" class="d-flex justify-content-between">
                        <img src="{{ asset('assets/img/img_bn/bg_2.png') }}" class="d-block w-100" alt="...">
                    </a>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselheader" data-bs-slide="prev">
                <span class="fa-solid fa-chevron-left" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselheader" data-bs-slide="next">
                <span class="fa-solid fa-chevron-right" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <div id="carouselExampleIndicators" class="carousel slide mx-auto d-md-block d-none" style="width: 85%;" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="3000">
                    <div class="row">
                        <a href="#" class="col-md w-100"><img src="{{ asset('assets/img/img_bn/bn_1.png') }}" class="border-0 border rounded w-100" style="height: 220px;" alt="..."></a>
                        <a href="#" class="col-md w-100"><img src="{{ asset('assets/img/img_bn/bn_2.png') }}" class="border-0 border rounded w-100" style="height: 220px;" alt="..."></a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="3000">
                    <div class="row">
                        <a href="#" class="col-md w-100"><img src="{{ asset('assets/img/img_bn/bn_3.png') }}" class="border-0 border rounded w-100" style="height: 220px;" alt="..."></a>
                        <a href="#" class="col-md w-100"><img src="{{ asset('assets/img/img_bn/bn_4.png') }}" class="border-0 border rounded w-100" style="height: 220px;" alt="..."></a>
                    </div>
                </div>
                <div class="carousel-item" data-bs-interval="3000">
                    <div class="row">
                        <a href="#" class="col-md w-100"><img src="{{ asset('assets/img/img_bn/bn_5.png') }}" class="border-0 border rounded w-100" style="height: 220px;" alt="..."></a>
                        <a href="#" class="col-md w-100"><img src="{{ asset('assets/img/img_bn/bn_6.png') }}" class="border-0 border rounded w-100" style="height: 220px;" alt="..."></a>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="fa-solid fa-chevron-left" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="fa-solid fa-chevron-right" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>

    <section class="section-padding bg-light">
        <div class="container">
            <div class="row align-items-center">

                <!-- LEFT CONTENT -->
                <div class="col-lg-6">
                    <h1 class="fw-bold mb-3">Giới thiệu</h1>

                    <h3 class="brand-title mb-4">Thương hiệu TQT</h3>

                    <p class="p-justify">
                        Thành lập tháng 4/2013, Trần Quốc Thái hiện là đơn vị cung cấp phân trùn quế hàng đầu Việt Nam với thương hiệu SFARM. Đồng thời cũng đã phát triển thêm các sản phẩm vật tư khác cung cấp cho ngành nông nghiệp đô thị, chủ yếu là dòng giá thể, phân bón hữu cơ và sinh học như: đất sạch hữu cơ, mụn dừa xử lý, trấu hun nguyên cánh,…
                    </p>

                    <p class="p-justify">
                        Với hệ thống điểm bán phủ khắp các thành phố của 63 tỉnh thành trên cả nước, TQT đang dần trở thành thương hiệu được lựa chọn hàng đầu cho các “nông dân phố”. Hiện tại, Trần Quốc Thái tiếp tục nghiên cứu để phát triển thêm nhiều dòng sản phẩm mới với tầm nhìn trở thành nhà cung cấp hàng đầu mảng nông nghiệp đô thị, cải tạo đất ở Việt Nam.
                    </p>

                    <a href="#" class="read-more">Xem chi tiết ...</a>
                </div>

                <!-- RIGHT IMAGES -->
                <div class="col-lg-6 position-relative mt-5 mt-lg-0">

                    <div class="image-frame position-relative">
                        <img src="{{ asset('assets/img/img_bn/bn_intro.png') }}"
                            class="img-fluid"
                            alt="Máy xúc">
                    </div>

                    <img src="{{ asset('assets/img/img_bn/bn_intro2.png') }}"
                        class="img-overlay img-fluid"
                        alt="Bao phân SFARM">

                </div>

            </div>
        </div>
    </section>

    <section class="why-section">
        <div class="container text-center">

            <h2 class="why-title">VÌ SAO CHỌN TRẦN QUỐC THÁI?</h2>
            <div class="divider"></div>

            <div class="row mt-4">

                <div class="col-lg-3 col-md-6">
                    <div class="icon-box">
                        <div class="icon-circle">
                            <i class="fa-solid fa-thumbs-up"></i>
                        </div>
                        <h5>THƯƠNG HIỆU TQT UY TÍN</h5>
                        <p>
                            Hơn 10 năm hình thành và phát triển, TQT với thương hiệu TQT
                            ngày càng trở thành thương hiệu uy tín hàng đầu...
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="icon-box">
                        <div class="icon-circle">
                            <i class="fa-solid fa-seedling"></i>
                        </div>
                        <h5>SẢN PHẨM HỮU CƠ, SINH HỌC</h5>
                        <p>
                            Cam kết mang đến những sản phẩm hữu cơ, sinh học chất lượng cao,
                            an toàn với con người và thân thiện môi trường.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="icon-box">
                        <div class="icon-circle">
                            <i class="fa-solid fa-truck"></i>
                        </div>
                        <h5>HỆ THỐNG PHÂN PHỐI TOÀN QUỐC</h5>
                        <p>
                            Hơn 1500 đại lý trên 63 tỉnh thành giúp trải nghiệm mua hàng
                            nhanh chóng và tiện lợi.
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="icon-box">
                        <div class="icon-circle">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h5>HỖ TRỢ TƯ VẤN NHIỆT TÌNH</h5>
                        <p>
                            Đội ngũ kỹ sư chuyên ngành luôn sẵn sàng hỗ trợ và đồng hành
                            cùng khách hàng.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ====== DGT PRODUCT SECTION ====== -->
    <section class="dgt-product-section py-5">
        <div class="container">

            <div class="text-center dgt-heading">
                <h2 class="dgt-title">SẢN PHẨM CỦA TRẦN QUỐC THÁI</h2>
                <div class="dgt-divider"></div>
            </div>

            <div class="row g-4">

                <div class="col-lg-3 col-md-6">
                    <a href="{{ route('user.test') }}" class="dgt-product-link">
                        <div class="dgt-product-card">
                            <img src="{{ asset('assets/img/img_bn/1-400x400.png') }}" class="img-fluid" alt="">
                            <h5 class="dgt-product-title">
                                PHÂN TRÙN QUẾ CAO CẤP SFARM PB01
                            </h5>
                            <p class="dgt-product-desc">
                                Sfarm Pb01 là dòng phân trùn quế cao cấp đã được giảm ẩm...
                            </p>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="#" class="dgt-product-link">
                        <div class="dgt-product-card">
                            <img src="{{ asset('assets/img/img_bn/1-400x400.png') }}" class="img-fluid" alt="">
                            <h5 class="dgt-product-title">
                                GIÁ THỂ ƯƠM HẠT GIỐNG SFARM
                            </h5>
                            <p class="dgt-product-desc">
                                Giá thể ươm hạt giống từ thương hiệu Sfarm...
                            </p>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="#" class="dgt-product-link">
                        <div class="dgt-product-card">
                            <img src="{{ asset('assets/img/img_bn/1-400x400.png') }}" class="img-fluid" alt="">
                            <h5 class="dgt-product-title">
                                ĐẤT TRỒNG HOA HỒNG SFARM
                            </h5>
                            <p class="dgt-product-desc">
                                Đất trồng hoa hồng Sfarm là hỗn hợp giá thể...
                            </p>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="#" class="dgt-product-link">
                        <div class="dgt-product-card">
                            <img src="{{ asset('assets/img/img_bn/1-400x400.png') }}" class="img-fluid" alt="">
                            <h5 class="dgt-product-title">
                                PHÂN TRÙN QUẾ SFARM PB00
                            </h5>
                            <p class="dgt-product-desc">
                                Dòng phân trùn quế Sfarm Pb00 là phân trùn quế thô...
                            </p>
                        </div>
                    </a>
                </div>

            </div>

            <div class="row g-4 mt-2">

                <div class="col-lg-3 col-md-6">
                    <a href="#" class="dgt-product-link">
                        <div class="dgt-product-card">
                            <img src="{{ asset('assets/img/img_bn/1-400x400.png') }}" class="img-fluid" alt="">
                            <h5 class="dgt-product-title">
                                PHÂN TRÙN QUẾ CAO CẤP SFARM PB01
                            </h5>
                            <p class="dgt-product-desc">
                                Sfarm Pb01 là dòng phân trùn quế cao cấp đã được giảm ẩm...
                            </p>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="#" class="dgt-product-link">
                        <div class="dgt-product-card">
                            <img src="{{ asset('assets/img/img_bn/1-400x400.png') }}" class="img-fluid" alt="">
                            <h5 class="dgt-product-title">
                                GIÁ THỂ ƯƠM HẠT GIỐNG SFARM
                            </h5>
                            <p class="dgt-product-desc">
                                Giá thể ươm hạt giống từ thương hiệu Sfarm...
                            </p>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="#" class="dgt-product-link">
                        <div class="dgt-product-card">
                            <img src="{{ asset('assets/img/img_bn/1-400x400.png') }}" class="img-fluid" alt="">
                            <h5 class="dgt-product-title">
                                ĐẤT TRỒNG HOA HỒNG SFARM
                            </h5>
                            <p class="dgt-product-desc">
                                Đất trồng hoa hồng Sfarm là hỗn hợp giá thể...
                            </p>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6">
                    <a href="#" class="dgt-product-link">
                        <div class="dgt-product-card">
                            <img src="{{ asset('assets/img/img_bn/1-400x400.png') }}" class="img-fluid" alt="">
                            <h5 class="dgt-product-title">
                                PHÂN TRÙN QUẾ SFARM PB00
                            </h5>
                            <p class="dgt-product-desc">
                                Dòng phân trùn quế Sfarm Pb00 là phân trùn quế thô...
                            </p>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </section>

</div>
@endsection