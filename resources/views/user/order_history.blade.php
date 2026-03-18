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
        cursor: pointer;
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
        width: 80px;
        height: 80px;
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

    .total-price {
        color: #ee4d2d;
        font-size: 22px;
        font-weight: 600;
    }

    .order-footer {
        background: #fafafa;
        padding: 15px 20px;
        border-top: 1px solid #eee;
    }

    /* danh gia san pham */

    /* CSS cho 5 ngôi sao đánh giá */
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        font-size: 30px;
        color: #ddd;
        cursor: pointer;
        transition: 0.2s;
        margin-right: 5px;
    }

    .star-rating input:checked~label,
    .star-rating label:hover,
    .star-rating label:hover~label {
        color: #ffc107;
        /* Màu vàng shopee */
    }
</style>
@endpush

@section('content')
<div id="wp-content" class="bg-body-tertiary" style="margin-top: 57px;">
    <div class="container mt-4 pb-3 pt-3">
        <div class="row">

            @include('user.layouts.profile')

            <div class="col-md-10">

                <ul class="nav order-tabs px-3 shadow-sm rounded-top">
                    <li class="nav-item"><a class="nav-link active" data-filter="all">Tất cả</a></li>
                    <li class="nav-item"><a class="nav-link" data-filter="pending">Chờ xác nhận</a></li>
                    <li class="nav-item"><a class="nav-link" data-filter="shipping">Vận chuyển</a></li>
                    <li class="nav-item"><a class="nav-link" data-filter="completed">Hoàn thành</a></li>
                    <li class="nav-item"><a class="nav-link" data-filter="cancelled">Đã hủy</a></li>
                </ul>

                <div class="order-search shadow-sm rounded-bottom mb-3">
                    <input type="text" id="orderSearchInput" class="form-control" placeholder="Bạn có thể tìm kiếm theo Mã đơn hàng hoặc Tên Sản phẩm">
                </div>

                @forelse($orders as $order)
                @php
                $statusText = '';
                $statusIcon = '';
                $statusColor = 'text-danger';

                switch($order->status) {
                case 'pending': $statusText = 'CHỜ XÁC NHẬN'; $statusIcon = ''; $statusColor = 'text-danger'; break;
                case 'shipping': $statusText = 'ĐANG GIAO HÀNG'; $statusIcon = ''; $statusColor = 'text-danger'; break;
                case 'completed': $statusText = 'GIAO HÀNG THÀNH CÔNG'; $statusIcon = ''; $statusColor = 'text-danger'; break;
                case 'cancelled': $statusText = 'ĐÃ HỦY'; $statusIcon = ''; $statusColor = 'text-danger'; break;
                default: $statusText = strtoupper($order->status);
                }
                @endphp

                <div class="order-card shadow-sm rounded border-0 mb-4"
                    data-status="{{ $order->status }}"
                    data-search="{{ strtolower('#' . $order->id . ' ' . implode(' ', $order->items->map(fn($i) => $i->package->product->name)->toArray())) }}">

                    <div class="p-3 border-bottom d-flex justify-content-between">
                        <div class="fw-bold">
                            Mã đơn: #{{ $order->id }}
                            <span class="text-muted ms-2 fw-normal fs-6">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="{{ $statusColor }}">{{ $statusIcon }} {{ $statusText }}</span>
                        </div>
                    </div>

                    <div class="p-3">
                        @foreach($order->items as $item)
                        <div class="d-flex align-items-start {{ !$loop->first ? 'border-top pt-3 mt-3' : '' }}">

                            <img src="{{ asset('storage/' . ($item->package->product->primaryImage->image_url ?? 'default.png')) }}" class="product-img me-3 border rounded">

                            <div class="flex-grow-1">
                                <div class="fw-medium text-truncate" style="max-width: 500px;">
                                    {{ $item->package->product->name ?? 'Sản phẩm' }}
                                </div>
                                <div class="text-muted mt-1" style="font-size: 14px;">
                                    Phân loại: {{ $item->package->packageType->type_name ?? '' }} - {{ rtrim(rtrim(number_format($item->package->size, 2, '.', ''), '0'), '.') }} {{ $item->package->unit }}
                                </div>
                                <div class="text-muted mt-1 fw-bold">
                                    x{{ $item->quantity }}
                                </div>
                            </div>

                            <div class="d-flex flex-column align-items-end text-end ms-3" style="min-width: 120px;">
                                <span class="price-new mb-2">{{ number_format($item->price_at_purchase, 0, ',', '.') }}đ</span>

                                @if($order->status == 'completed')
                                @php
                                // Kiểm tra xem user đã đánh giá mặt hàng này trong đơn này chưa
                                $hasReviewed = $order->reviews->where('package_id', $item->package_id)->first();
                                @endphp

                                @if($hasReviewed)
                                <button class="btn btn-sm btn-outline-secondary mt-auto" disabled>Đã đánh giá ({{ $hasReviewed->rating }}⭐)</button>
                                @else
                                <button class="btn btn-sm btn-outline-warning mt-auto btn-open-review"
                                    data-bs-toggle="modal"
                                    data-bs-target="#reviewModal"
                                    data-order-id="{{ $order->id }}"
                                    data-package-id="{{ $item->package_id }}"
                                    data-product-name="{{ $item->package->product->name }}">
                                    Đánh giá
                                </button>
                                @endif
                                @endif
                            </div>

                        </div>
                        @endforeach
                    </div>

                    <div class="order-footer rounded-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Thành tiền: <span class="total-price ms-2">{{ number_format($order->total_price, 0, ',', '.') }}đ</span>
                            </div>
                            <div>
                                <button class="btn btn-outline-secondary me-2">Liên hệ CSKH</button>
                                @if($order->status == 'completed')
                                <button class="btn btn-success">Mua lại</button>
                                @elseif($order->status == 'pending')
                                <form action="{{ route('order.cancel', $order->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không? Quá trình này không thể hoàn tác.');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger">Hủy đơn hàng</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
                @empty
                <div class="text-center p-5 bg-white rounded shadow-sm mt-3">
                    <h5 class="text-muted mt-3">Chưa có đơn hàng nào</h5>
                </div>
                @endforelse

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('review.submit') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Đánh giá sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="reviewProductName" class="fw-bold text-muted mb-2"></p>

                    <input type="hidden" name="order_id" id="reviewOrderId">
                    <input type="hidden" name="package_id" id="reviewPackageId">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Chất lượng sản phẩm</label>
                        <div class="star-rating">
                            <input type="radio" id="star5" name="rating" value="5" checked /><label for="star5" title="5 sao">★</label>
                            <input type="radio" id="star4" name="rating" value="4" /><label for="star4" title="4 sao">★</label>
                            <input type="radio" id="star3" name="rating" value="3" /><label for="star3" title="3 sao">★</label>
                            <input type="radio" id="star2" name="rating" value="2" /><label for="star2" title="2 sao">★</label>
                            <input type="radio" id="star1" name="rating" value="1" /><label for="star1" title="1 sao">★</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Chia sẻ thêm về trải nghiệm của bạn (Tùy chọn)</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Ví dụ: Sản phẩm đóng gói cẩn thận, chất lượng tuyệt vời..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Trở lại</button>
                    <button type="submit" class="btn btn-danger px-4">Hoàn thành</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('page_specific_js')
<script>
    // Bắt sự kiện khi click vào nút Đánh giá của từng sản phẩm
    document.querySelectorAll('.btn-review').forEach(button => {
        button.addEventListener('click', function() {
            let orderItemId = this.dataset.itemId;
            let packageId = this.dataset.packageId;

            // Log thử ra xem lấy đúng ID chưa
            console.log("Chuẩn bị đánh giá cho OrderItem ID: " + orderItemId + ", Package ID: " + packageId);

            // GỢI Ý NEXT STEP: Mở một Modal (Bootstrap) chứa Form đánh giá ở đây
            // alert('Tính năng mở form đánh giá cho sản phẩm đang được xây dựng!');
        });
    });
    // xu ly modal review
    document.addEventListener('DOMContentLoaded', function() {
        // Bắt sự kiện khi click mở modal đánh giá
        let reviewButtons = document.querySelectorAll('.btn-open-review');

        reviewButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Lấy data từ nút bấm
                let orderId = this.getAttribute('data-order-id');
                let packageId = this.getAttribute('data-package-id');
                let productName = this.getAttribute('data-product-name');

                // Điền vào form ẩn trong Modal
                document.getElementById('reviewOrderId').value = orderId;
                document.getElementById('reviewPackageId').value = packageId;
                document.getElementById('reviewProductName').innerText = "Sản phẩm: " + productName;
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.order-tabs .nav-link');
        const orderCards = document.querySelectorAll('.order-card');
        const searchInput = document.getElementById('orderSearchInput');

        let currentFilter = 'all';

        // 1. Hàm lọc chính xử lý cả Tab và Tìm kiếm
        function filterOrders() {
            const searchText = searchInput.value.toLowerCase().trim();

            orderCards.forEach(card => {
                const status = card.getAttribute('data-status');
                const searchContent = card.getAttribute('data-search').toLowerCase();

                // Kiểm tra khớp tab
                const isTabMatched = (currentFilter === 'all' || status === currentFilter);
                // Kiểm tra khớp tìm kiếm
                const isSearchMatched = searchContent.includes(searchText);

                if (isTabMatched && isSearchMatched) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Hiển thị thông báo nếu không tìm thấy đơn nào
            checkEmptyResult();
        }

        // 2. Xử lý click Tab
        tabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();

                // UI: Đổi active class
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                // Logic: Lọc
                currentFilter = this.getAttribute('data-filter');
                filterOrders();
            });
        });

        // 3. Xử lý tìm kiếm (Debounce nhẹ để mượt hơn)
        searchInput.addEventListener('input', filterOrders);

        function checkEmptyResult() {
            const visibleCards = Array.from(orderCards).filter(c => c.style.display !== 'none');
            let emptyMsg = document.getElementById('empty-order-msg');

            if (visibleCards.length === 0) {
                if (!emptyMsg) {
                    const div = document.createElement('div');
                    div.id = 'empty-order-msg';
                    div.className = 'text-center p-5 bg-white rounded shadow-sm mt-3';
                    div.innerHTML = '<h5 class="text-muted">Không tìm thấy đơn hàng phù hợp</h5>';
                    document.querySelector('.col-md-9').appendChild(div);
                }
            } else if (emptyMsg) {
                emptyMsg.remove();
            }
        }

        // --- Giữ nguyên Logic Modal Review của bạn ---
        let reviewButtons = document.querySelectorAll('.btn-open-review');
        reviewButtons.forEach(button => {
            button.addEventListener('click', function() {
                let orderId = this.getAttribute('data-order-id');
                let packageId = this.getAttribute('data-package-id');
                let productName = this.getAttribute('data-product-name');

                document.getElementById('reviewOrderId').value = orderId;
                document.getElementById('reviewPackageId').value = packageId;
                document.getElementById('reviewProductName').innerText = "Sản phẩm: " + productName;
            });
        });
    });
</script>

@endpush