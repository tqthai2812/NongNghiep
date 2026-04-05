@extends('user.layouts.default')

@section('title', 'Giỏ hàng')
@push('page_specific_css')
<link rel="stylesheet" href="{{ asset('assets/css/user/cart.css') }}">
@endpush

@section('content')
<div id="wp-content" class="bg-body-tertiary" style="margin-top: 57px;">

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

    {{-- Thông báo Lỗi --}}
    <div class="container">
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mt-3" role="alert" style="background-color: #fdeaea; color: #d93025; border-radius: 8px;">
            <div class="d-flex align-items-center">
                <i class="bi bi-x-lg"></i>
                <span class="fw-bold">{{ session('error') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Thông báo Thành công --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mt-3" role="alert" style="background-color: #e6f4ea; color: #1e8e3e; border-radius: 8px;">
            <div class="d-flex align-items-center">
                <i class="bi bi-check2"></i>
                <span class="fw-bold">{{ session('success') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>

    <div class="container cart-container py-4">
        @if($cartItems->count() > 0)
        <!-- HEADER -->

        <div class="cart-header d-flex align-items-center p-3 border bg-white border-0 rounded-2 mt-3">

            <div style="width:40px">
                <input class="form-check-input" type="checkbox" id="check-all">
            </div>

            <div class="flex-grow-1">
                Sản phẩm
            </div>

            <div class="cart-col text-center">
                Đơn giá
            </div>

            <div class="cart-col text-center">
                Số lượng
            </div>

            <div class="cart-col text-center">
                Thành tiền
            </div>

            <div class="cart-col text-center">
                Thao tác
            </div>

        </div>


        <!-- ITEM 1 -->

        @foreach($cartItems as $item)
        @php
        $isOutOfStock = $item->package->stock <= 0;
            @endphp

            <div class="cart-item d-flex align-items-center p-3 bg-white border-0 rounded-2 mt-3 {{ $isOutOfStock ? 'out-of-stock' : '' }}"
            data-id="{{ $item->id }}"
            data-price="{{ $item->package->price }}">

            <div style="width:40px">
                <input class="form-check-input item-check" type="checkbox" {{ $isOutOfStock ? 'disabled' : '' }}>
            </div>

            <div class="d-flex flex-grow-1 align-items-center">
                <div class="cart-img-wrapper me-3">
                    <img src="{{ asset('storage/' . ($item->package->product->primaryImage->image_url ?? 'default.png')) }}" class="cart-img">

                    @if($isOutOfStock)
                    <div class="out-of-stock-overlay">Hết hàng</div>
                    @endif
                </div>

                <div>
                    <div class="cart-title">
                        {{ $item->package->product->name }}
                    </div>
                    <div class="cart-variant">
                        {{ $item->package->full_name }}
                    </div>
                </div>
            </div>

            <div class="cart-col text-center price">
                {{ number_format($item->package->price, 0, ',', '.') }}₫
            </div>

            <div class="cart-col text-center">
                <div class="input-group cart-qty">
                    <button class="btn btn-outline-secondary qty-minus" {{ $isOutOfStock ? 'disabled' : '' }}>-</button>
                    <input type="text"
                        class="form-control text-center qty-input"
                        value="{{ $isOutOfStock ? 0 : $item->quantity }}"
                        readonly>
                    <button class="btn btn-outline-secondary qty-plus" {{ $isOutOfStock ? 'disabled' : '' }}>+</button>
                </div>
            </div>

            <div class="cart-col text-danger text-center fw-bold item-total">
                {{ $isOutOfStock ? '0₫' : number_format($item->package->price * $item->quantity, 0, ',', '.') . '₫' }}
            </div>

            <div class="cart-col text-center">
                <a href="#" class="text-danger remove-item">Xóa</a>
            </div>
    </div>
    @endforeach

    @else
    <div class="bg-white rounded-2 p-5 mt-3 text-center mb-0">
        <div class="row align-items-center">
            <div class="col-md-7 text-start ps-5">
                <h2 class="fw-bold" style="font-size: 2rem;">Chưa có sản phẩm nào trong giỏ hàng</h2>
                <p class="text-secondary mb-4">Cùng mua sắm hàng ngàn sản phẩm tại TQTShop nhé!</p>
                <a href="/" class="btn btn-success px-2 py-1 btn-lg rounded-pill fs-5 text-white">Mua hàng</a>
            </div>
            <div class="col-md-5">
                <img src="{{ asset('assets/img/cart.png') }}"
                    alt="Empty Cart"
                    class="img-fluid"
                    style="width: 500px;">
            </div>
        </div>
    </div>
    @endif
    <!-- FOOTER -->
    @if($cartItems->count() > 0)
    <div class="cart-footer">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <input class="form-check-input me-2" type="checkbox" id="check-all-footer">
                Chọn tất cả
            </div>

            <div class="d-flex align-items-center">

                <div class="me-4">
                    Tổng cộng:
                    <span class="text-danger fs-5 fw-bold" id="cart-total">
                        0đ
                    </span>
                </div>

                <button id="btn-checkout" class="btn btn-danger px-4">
                    Mua hàng
                </button>

            </div>

        </div>

    </div>
    @endif

</div>
</div>
@endsection

@push('page_specific_js')
<script>
    // Bước trung gian: Chuyển dữ liệu Laravel sang Object Javascript
    window.cartConfig = {
        updateUrl: "{{ route('cart.update') }}",
        csrfToken: "{{ csrf_token() }}",
    };
</script>

<script src="{{ asset('assets/js/user/cart.js') }}"></script>
@endpush