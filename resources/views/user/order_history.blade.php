@extends('user.layouts.default')

@section('title', 'Lịch sử đơn hàng')

@push('page_specific_css')
<link rel="stylesheet" href="{{ asset('assets/css/user/order_history.css') }}">
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

<script src="{{ asset('assets/js/user/order_history.js') }}"></script>

@endpush