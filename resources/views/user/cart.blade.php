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

    /* Xử lý hết hàng cho sản phẩm */

    /* Khung chứa ảnh để định vị lớp phủ */
    .cart-img-wrapper {
        position: relative;
        width: 80px;
        height: 80px;
    }

    /* Lớp phủ hết hàng */
    .out-of-stock-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        /* Màu tối mờ */
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
        border-radius: 4px;
        text-align: center;
        text-transform: uppercase;
        pointer-events: none;
        /* Không ngăn cản click vào ảnh nếu cần */
    }

    /* Làm mờ nhẹ item nếu hết hàng (tùy chọn) */
    .cart-item.out-of-stock {
        background-color: #f9f9f9;
    }

    .cart-item.out-of-stock .cart-title {
        color: #999;
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

                <button id="btn-checkout" class="btn btn-danger px-4">
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
            let cartItemId = item.dataset.id; // Lấy ID của dòng giỏ hàng

            input.value++; // Tăng UI

            updateItemTotal(item); // Cập nhật thành tiền UI
            updateCartTotal(); // Cập nhật tổng giỏ hàng UI

            // QUAN TRỌNG: Gửi dữ liệu lên Server
            syncQuantity(cartItemId, input.value);
        }
    });


    document.querySelectorAll(".qty-minus").forEach(btn => {
        btn.onclick = function() {
            let item = this.closest(".cart-item");
            let input = item.querySelector(".qty-input");
            let cartItemId = item.dataset.id;

            if (input.value > 1) {
                input.value--; // Giảm UI

                updateItemTotal(item);
                updateCartTotal();

                // QUAN TRỌNG: Gửi dữ liệu lên Server
                syncQuantity(cartItemId, input.value);
            }
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


    // Hàm cập nhật số lượng lên Server
    function syncQuantity(cartItemId, newQty) {
        fetch("{{ route('cart.update') }}", { // Đường dẫn đến hàm updateQuantity trong Controller
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    id: cartItemId,
                    quantity: newQty
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status !== 'success') {
                    alert(data.message || "Có lỗi xảy ra");
                    location.reload(); // Nếu lỗi (hết hàng), reload để lấy số lượng đúng
                }
            })
            .catch(err => {
                console.error("Lỗi kết nối:", err);
            });
    }

    // Cập nhật sự kiện nút Xóa
    document.querySelectorAll(".remove-item").forEach(btn => {
        btn.onclick = function(e) {
            e.preventDefault();
            if (!confirm("Xác nhận xóa sản phẩm?")) return;

            let itemRow = this.closest(".cart-item");
            let id = itemRow.dataset.id;

            fetch(`/cart/delete/${id}`, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    }
                })
                .then(res => res.json())
                .then(data => {
                    itemRow.remove();
                    updateCartTotal();
                });
        }
    });

    // Xử lý sự kiện nhấn nút Mua hàng
    document.getElementById('btn-checkout').addEventListener('click', function(e) {
        e.preventDefault();

        let selectedCartIds = [];

        // Tìm tất cả các checkbox sản phẩm đang được tích
        document.querySelectorAll('.item-check:checked').forEach(function(checkbox) {
            let cartItem = checkbox.closest('.cart-item');
            // Chỉ lấy những sản phẩm không bị hết hàng
            if (cartItem && !cartItem.classList.contains('out-of-stock')) {
                selectedCartIds.push(cartItem.dataset.id);
            }
        });

        if (selectedCartIds.length === 0) {
            alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán!');
            return;
        }

        // Tạo một form ẩn để gửi mảng ID sang trang Checkout bằng phương thức POST
        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '/checkout'; // Thay đổi đường dẫn này theo route của bạn (VD: route('checkout.index'))

        // Thêm CSRF Token
        let csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        // Thêm các ID sản phẩm được chọn vào form
        selectedCartIds.forEach(id => {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'cart_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit(); // Chuyển hướng sang trang thanh toán
    });




    updateCartTotal();
</script>
@endpush