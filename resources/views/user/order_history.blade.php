@extends('user.layouts.default')

@section('title', 'Lịch sử đơn hàng')

@push('page_specific_css')
<style>
    body {
        background: #f5f5f5;
    }

    .sidebar {
        background: white;
        padding: 20px;
    }

    .sidebar img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    .sidebar a {
        display: block;
        padding: 10px 0;
        text-decoration: none;
        color: #333;
        font-size: 15px;
    }

    .sidebar a.active {
        color: #ee4d2d;
        font-weight: 500;
    }

    .order-tabs {
        background: white;
        border-bottom: 1px solid #eee;
    }

    .order-tabs .nav-link {
        color: #555;
    }

    .order-tabs .nav-link.active {
        color: #ee4d2d;
        border-bottom: 2px solid #ee4d2d;
    }

    .order-search {
        background: white;
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .order-card {
        background: white;
        margin-top: 15px;
    }

    .product-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
    }

    .price-old {
        text-decoration: line-through;
        color: #999;
        margin-right: 10px;
    }

    .price-new {
        color: #ee4d2d;
        font-weight: 600;
    }

    .order-status {
        color: #00bfa5;
        font-weight: 500;
    }

    .total-price {
        color: #ee4d2d;
        font-size: 22px;
        font-weight: 600;
    }

    .order-footer {
        background: #fafafa;
        padding: 20px;
        border-top: 1px solid #eee;
    }
</style>
@endpush

@section('content')
<div id="wp-content" class="bg-body-tertiary" style="margin-top: 57px;">
    <div class="container mt-4">

        <div class="row">

            <!-- SIDEBAR -->

            <div class="col-md-3">

                <div class="sidebar">

                    <div class="d-flex align-items-center mb-4">

                        <img src="https://i.pravatar.cc/100">

                        <div class="ms-3">

                            <div class="fw-bold">
                                qthai2812
                            </div>

                            <small class="text-muted">
                                ✏️ Sửa Hồ Sơ
                            </small>

                        </div>

                    </div>

                    <a href="#">🔔 Thông Báo</a>

                    <a href="#">👤 Tài Khoản Của Tôi</a>

                    <a href="#" class="active">📄 Đơn Mua</a>

                    <a href="#">🎫 Kho Voucher</a>

                    <a href="#">🪙 Shopee Xu</a>

                </div>

            </div>


            <!-- CONTENT -->

            <div class="col-md-9">

                <ul class="nav order-tabs px-3">

                    <li class="nav-item">
                        <a class="nav-link active">Tất cả</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link">Chờ thanh toán</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link">Vận chuyển</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link">Chờ giao hàng</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link">Hoàn thành</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link">Đã hủy</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link">Trả hàng/Hoàn tiền</a>
                    </li>

                </ul>


                <div class="order-search">

                    <input type="text"
                        class="form-control"
                        placeholder="Bạn có thể tìm kiếm theo tên Shop, ID đơn hàng hoặc Tên Sản phẩm">

                </div>


                <div class="order-card p-3">

                    <div class="d-flex justify-content-between mb-3">

                        <span class="order-status">
                            🚚 Giao hàng thành công
                        </span>

                        <span class="text-danger">
                            HOÀN THÀNH
                        </span>

                    </div>


                    <div class="d-flex align-items-center border-top pt-3">

                        <img src="https://via.placeholder.com/70" class="product-img me-3">

                        <div class="flex-grow-1">

                            <div>
                                Găng tay hở ngón nữ hình mèo dễ thương vải polyester mềm dày
                            </div>

                            <div class="text-muted">
                                Phân loại hàng: Trắng
                            </div>

                            <div class="text-muted">
                                x1
                            </div>

                        </div>

                        <div>

                            <span class="price-old">90.000đ</span>
                            <span class="price-new">84.000đ</span>

                        </div>

                    </div>


                    <div class="order-footer mt-3">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>
                                Thành tiền:
                                <span class="total-price">75.600đ</span>
                            </div>

                            <div>

                                <button class="btn btn-outline-secondary me-2">
                                    Liên hệ người bán
                                </button>

                                <button class="btn btn-outline-warning me-2">
                                    Đánh giá
                                </button>

                                <button class="btn btn-danger">
                                    Mua lại
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>
@endsection

@push('page_specific_js')
@endpush