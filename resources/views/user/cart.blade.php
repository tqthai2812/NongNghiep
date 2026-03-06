@extends('user.layouts.default')

@section('title', 'Giỏ hàng')
@push('page_specific_css')
<style>
    .cart-container {
        padding: 0px;
    }

    .cart-header {
        background: #fafafa;
        font-weight: 500;
    }

    .cart-item {
        transition: 0.2s;
    }

    .cart-item:hover {
        background: #fafafa;
    }

    .cart-img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 4px;
    }

    .cart-title {
        font-weight: 500;
        max-width: 350px;
    }

    .cart-variant {
        font-size: 14px;
        color: #777;
    }

    .cart-col {
        width: 140px;
    }

    .cart-qty {
        width: 120px;
        margin: auto;
    }

    .cart-footer {
        position: sticky;
        bottom: 0;
        background: white;
        border-top: 2px solid #eee;
        padding: 15px;
        margin-top: 20px;
        z-index: 100;
    }
</style>
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

    <div class="container cart-container pt-4">

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

        <div class="cart-item d-flex align-items-center p-3 bg-white border-0 rounded-2 mt-3"
            data-id="1"
            data-price="109000">

            <div style="width:40px">
                <input class="form-check-input item-check" type="checkbox">
            </div>

            <div class="d-flex flex-grow-1 align-items-center">

                <img src="{{ asset('assets/img/img_bn/1-400x400.png') }}" class="cart-img me-3">

                <div>
                    <div class="cart-title">
                        Quần short jean nam chất liệu jean mềm thoáng mát
                    </div>

                    <div class="cart-variant">
                        Đen - Size M
                    </div>
                </div>

            </div>

            <div class="cart-col text-center price">
                109.000đ
            </div>

            <div class="cart-col text-center">

                <div class="input-group cart-qty">

                    <button class="btn btn-outline-secondary qty-minus">-</button>

                    <input type="text"
                        class="form-control text-center qty-input"
                        value="1">

                    <button class="btn btn-outline-secondary qty-plus">+</button>

                </div>

            </div>

            <div class="cart-col text-danger text-center fw-bold item-total">
                109.000đ
            </div>

            <div class="cart-col text-center">
                <a href="#" class="text-danger remove-item">Xóa</a>
            </div>

        </div>


        <!-- ITEM 2 -->

        <div class="cart-item d-flex align-items-center p-3 bg-white border-0 rounded-2 mt-3"
            data-id="2"
            data-price="10800">

            <div style="width:40px">
                <input class="form-check-input item-check" type="checkbox">
            </div>

            <div class="d-flex flex-grow-1 align-items-center">

                <img src="{{ asset('assets/img/img_bn/1-400x400.png') }}" class="cart-img me-3">

                <div>
                    <div class="cart-title">
                        Hạt giống rau thơm, rau gia vị trồng quanh năm
                    </div>

                    <div class="cart-variant">
                        Ly trộn mix màu
                    </div>
                </div>

            </div>

            <div class="cart-col text-center price">
                10.800đ
            </div>

            <div class="cart-col text-center">

                <div class="input-group cart-qty">

                    <button class="btn btn-outline-secondary qty-minus">-</button>

                    <input type="text"
                        class="form-control text-center qty-input"
                        value="2">

                    <button class="btn btn-outline-secondary qty-plus">+</button>

                </div>

            </div>

            <div class="cart-col text-danger text-center fw-bold item-total">
                21.600đ
            </div>

            <div class="cart-col text-center">
                <a href="#" class="text-danger remove-item">Xóa</a>
            </div>

        </div>


        <!-- FOOTER -->

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

                    <button class="btn btn-danger px-4">
                        Mua hàng
                    </button>

                </div>

            </div>

        </div>

    </div>
</div>
@endsection

@push('page_specific_js')
<script>
    function formatMoney(number) {
        return number.toLocaleString('vi-VN') + "đ";
    }

    function updateItemTotal(item) {

        let price = parseInt(item.dataset.price);

        let qty = parseInt(item.querySelector(".qty-input").value);

        let total = price * qty;

        item.querySelector(".item-total").innerText = formatMoney(total);

    }

    function updateCartTotal() {

        let total = 0;

        document.querySelectorAll(".cart-item").forEach(item => {

            let checked = item.querySelector(".item-check").checked;

            if (checked) {

                let price = parseInt(item.dataset.price);

                let qty = parseInt(item.querySelector(".qty-input").value);

                total += price * qty;

            }

        });

        document.getElementById("cart-total").innerText = formatMoney(total);

    }


    document.querySelectorAll(".qty-plus").forEach(btn => {

        btn.onclick = function() {

            let item = this.closest(".cart-item");

            let input = item.querySelector(".qty-input");

            input.value++;

            updateItemTotal(item);

            updateCartTotal();

        }

    });


    document.querySelectorAll(".qty-minus").forEach(btn => {

        btn.onclick = function() {

            let item = this.closest(".cart-item");

            let input = item.querySelector(".qty-input");

            if (input.value > 1) {
                input.value--;
            }

            updateItemTotal(item);

            updateCartTotal();

        }

    });


    document.querySelectorAll(".qty-input").forEach(input => {

        input.onchange = function() {

            let item = this.closest(".cart-item");

            if (this.value <= 0) {
                this.value = 1;
            }

            updateItemTotal(item);

            updateCartTotal();

        }

    });


    document.querySelectorAll(".item-check").forEach(check => {

        check.onchange = function() {

            updateCartTotal();

        }

    });


    document.getElementById("check-all").onchange = function() {

        let checked = this.checked;

        document.querySelectorAll(".item-check").forEach(check => {

            check.checked = checked;

        });

        updateCartTotal();

    };


    document.querySelectorAll(".remove-item").forEach(btn => {

        btn.onclick = function(e) {

            e.preventDefault();

            let item = this.closest(".cart-item");

            item.remove();

            updateCartTotal();

        }

    });


    updateCartTotal();
</script>
@endpush