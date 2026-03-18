@extends('admin.layouts.master')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('page_specific_css')
<style>
    /* Khung lớn bao trọn nội dung */
    .invoice-frame {
        background-color: #ffffff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
        padding: 40px;
        margin-bottom: 30px;
    }

    .frame-header {
        border-bottom: 2px dashed #f3f4f6;
        padding-bottom: 24px;
        margin-bottom: 30px;
    }

    .info-box {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        height: 100%;
        border: 1px solid #f1f5f9;
    }

    /* Badge Trạng thái */
    .status-badge {
        padding: 8px 20px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
    }

    .status-badge .material-icons {
        font-size: 18px;
        margin-right: 6px;
    }

    .badge-pending {
        background-color: #fffbeb;
        color: #b45309;
        border: 1px solid #fde68a;
    }

    .badge-shipping {
        background-color: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .badge-completed {
        background-color: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .badge-cancelled {
        background-color: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    /* Table Sản phẩm */
    .table-order-items th {
        text-transform: uppercase;
        font-size: 0.8rem;
        color: #6b7280;
        border-bottom: 2px solid #e5e7eb;
        padding: 15px 10px;
    }

    .table-order-items td {
        padding: 20px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f3f4f6;
    }

    /* Tổng kết Bill */
    .bill-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        color: #4b5563;
        font-size: 1.05rem;
    }

    .bill-row.total {
        border-top: 2px solid #e5e7eb;
        margin-top: 10px;
        padding-top: 15px;
        font-size: 1.4rem;
        font-weight: 800;
        color: #ef4444;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-11 bg-white p-4 shadow-sm">

        {{-- Nút quay lại và Alert nằm ngoài khung --}}
        <div class="mb-3 d-flex justify-content-between align-items-center">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-light shadow-sm border text-dark fw-bold d-inline-flex align-items-center" style="border-radius: 8px;">
                <i class="material-icons me-2" style="font-size: 18px;">arrow_back</i> Quay lại danh sách
            </a>

            {{-- Gợi ý: Nút in hóa đơn (nếu có tính năng window.print) --}}
            <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="btn btn-primary fw-bold d-inline-flex align-items-center shadow-sm" style="border-radius: 8px;">
                <i class="material-icons me-2" style="font-size: 18px;">print</i> Xuất PDF / In
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
            <i class="material-icons me-2 align-middle">check_circle</i>
            <span class="align-middle fw-bold">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
            <i class="material-icons me-2 align-middle">error</i>
            <span class="align-middle fw-bold">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- BẮT ĐẦU KHUNG LỚN (FRAME) --}}
        <div class="invoice-frame">

            {{-- 1. Header Khung --}}
            <div class="frame-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 fw-bold text-dark text-uppercase">
                        ĐƠN HÀNG #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                    </h3>
                    <span class="text-muted fw-medium">
                        <i class="material-icons align-middle me-1" style="font-size: 16px;">calendar_today</i>
                        Ngày đặt: {{ $order->created_at->format('H:i - d/m/Y') }}
                    </span>
                </div>
                <div>
                    @if($order->status == 'pending')
                    <span class="status-badge badge-pending"><i class="material-icons">hourglass_empty</i> Chờ xử lý</span>
                    @elseif($order->status == 'shipping')
                    <span class="status-badge badge-shipping"><i class="material-icons">local_shipping</i> Đang giao hàng</span>
                    @elseif($order->status == 'completed')
                    <span class="status-badge badge-completed"><i class="material-icons">check_circle</i> Đã hoàn thành</span>
                    @elseif($order->status == 'cancelled')
                    <span class="status-badge badge-cancelled"><i class="material-icons">cancel</i> Đã hủy</span>
                    @endif
                </div>
            </div>

            {{-- 2. Thông tin Người mua & Giao hàng (Chia 2 cột bên trong khung) --}}
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="info-box">
                        <h6 class="fw-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.5px;">Thông tin khách hàng</h6>
                        @if($order->user)
                        <p class="mb-2 text-dark"><strong class="fs-6">{{ $order->user->user_name ?? 'Chưa cập nhật tên' }}</strong></p>
                        <p class="mb-2 text-muted"><i class="material-icons align-middle me-2" style="font-size: 18px;">email</i> {{ $order->user->email }}</p>
                        <p class="mb-0 text-muted"><i class="material-icons align-middle me-2" style="font-size: 18px;">phone</i> {{ $order->user->phone_number ?? 'Không có SĐT' }}</p>
                        @else
                        <p class="text-muted fst-italic mb-0">Khách vãng lai (Guest)</p>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <h6 class="fw-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.5px;">Địa chỉ giao hàng</h6>
                        <p class="mb-0 text-dark lh-lg">
                            <i class="material-icons align-top me-2 text-muted" style="font-size: 18px; margin-top: 5px;">location_on</i>
                            <span>{!! nl2br(e($order->shipping_address ?? 'Chưa cung cấp địa chỉ chi tiết.')) !!}</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- 3. Bảng Chi tiết Sản phẩm --}}
            <h6 class="fw-bold text-dark text-uppercase mb-3" style="letter-spacing: 0.5px;">Chi tiết sản phẩm</h6>
            <div class="table-responsive mb-4">
                <table class="table table-order-items table-borderless w-100">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3" style="min-width: 250px;">Mặt hàng</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end pe-3">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td class="ps-3">
                                <h6 class="mb-1 fw-bold text-dark" style="font-size: 1rem;">
                                    {{ $item->package->packageType->product->name ?? 'Gói hàng ID #' . $item->package_id }}
                                </h6>
                                <span class="text-muted small">Phân loại: {{ $item->package->size ?? '' }} {{ $item->package->unit ?? '' }}</span>
                            </td>
                            <td class="text-center text-muted fw-medium">
                                {{ number_format($item->price_at_purchase, 0, ',', '.') }} đ
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-dark">x{{ $item->quantity }}</span>
                            </td>
                            <td class="text-end fw-bold text-dark pe-3">
                                {{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }} đ
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- 4. Tổng tiền & Hành động (Cập nhật trạng thái) --}}
            <div class="row align-items-end mt-4">
                {{-- Form Cập nhật trạng thái góc trái dưới --}}
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="p-3 bg-light" style="border-radius: 12px; border: 1px dashed #cbd5e1;">
                        <label class="form-label fw-bold text-dark mb-2">Cập nhật trạng thái đơn hàng</label>
                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-flex">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select fw-medium shadow-sm me-2" style="border-radius: 8px;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Hủy đơn</option>
                            </select>
                            <button type="submit" class="btn btn-primary fw-bold px-4" style="border-radius: 8px;">
                                Lưu
                            </button>
                        </form>
                        @if($order->status == 'cancelled')
                        <div class="text-danger mt-2 small"><i class="material-icons align-middle me-1" style="font-size: 14px;">info</i> Số lượng tồn kho đã được hoàn lại.</div>
                        @endif
                    </div>
                </div>

                {{-- Tổng kết tiền góc phải dưới --}}
                <div class="col-md-6">
                    <div class="ms-auto" style="max-width: 350px;">
                        <div class="bill-row">
                            <span>Tạm tính:</span>
                            <span class="fw-bold text-dark">{{ number_format($order->total_price, 0, ',', '.') }} đ</span>
                        </div>
                        <div class="bill-row">
                            <span>Phí vận chuyển:</span>
                            <span class="fw-bold text-dark">30.000 đ</span>
                        </div>
                        <div class="bill-row total">
                            <span>Tổng cộng:</span>
                            <span>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        {{-- KẾT THÚC KHUNG LỚN --}}

    </div>
</div>
@endsection