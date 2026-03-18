<!DOCTYPE html>
<html lang="vi">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>In Đơn Hàng #{{ $order->id }}</title>
    <style>
        /* Sử dụng font DejaVu Sans để không bị lỗi font Tiếng Việt khi xuất PDF */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table {
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            text-align: right;
            text-transform: uppercase;
        }

        .info-table {
            margin-bottom: 30px;
        }

        .info-table td {
            vertical-align: top;
            width: 50%;
        }

        .box-title {
            font-weight: bold;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 12px;
        }

        .text-right {
            text-align: right !important;
        }

        .text-center {
            text-align: center !important;
        }

        .total-row {
            font-weight: bold;
            font-size: 14px;
        }

        .total-final {
            font-weight: bold;
            font-size: 16px;
            color: #d93025;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td>
                <h3 style="margin: 0; font-size: 18px;">NÔNG NGHIỆP XANH STORE</h3>
                <p style="margin: 5px 0 0 0; font-size: 12px; color: #555;">
                    Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM<br>
                    Điện thoại: 0909.123.456<br>
                    Website: www.nongnghiepxanh.com
                </p>
            </td>
            <td class="title">
                PHIẾU GIAO HÀNG<br>
                <span style="font-size: 14px; font-weight: normal; color: #666;">Mã đơn: #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span><br>
                <span style="font-size: 12px; font-weight: normal; color: #666;">Ngày: {{ $order->created_at->format('d/m/Y H:i') }}</span>
            </td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td style="padding-right: 20px;">
                <div class="box-title">Người Đặt Hàng</div>
                <strong>{{ $order->user->user_name ?? 'Khách vãng lai' }}</strong><br>
                SĐT: {{ $order->user->phone_number ?? 'Không có' }}<br>
                Email: {{ $order->user->email ?? 'Không có' }}
            </td>
            <td>
                <div class="box-title">Địa Chỉ Nhận Hàng</div>
                {!! nl2br(e($order->shipping_address ?? 'Chưa cung cấp địa chỉ chi tiết.')) !!}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">STT</th>
                <th style="width: 45%;">Tên sản phẩm</th>
                <th style="width: 15%;" class="text-center">Đơn giá</th>
                <th style="width: 10%;" class="text-center">SL</th>
                <th style="width: 25%;" class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <strong>{{ $item->package->packageType->product->name ?? 'Gói hàng #' . $item->package_id }}</strong><br>
                    <span style="font-size: 11px; color: #666;">Phân loại: {{ $item->package->size ?? '' }} {{ $item->package->unit ?? '' }}</span>
                </td>
                <td class="text-center">{{ number_format($item->price_at_purchase, 0, ',', '.') }} đ</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->price_at_purchase * $item->quantity, 0, ',', '.') }} đ</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-right total-row" style="padding-top: 15px;">Tạm tính:</td>
                <td class="text-right" style="padding-top: 15px;">{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right total-row">Phí vận chuyển:</td>
                <td class="text-right">30.000 đ</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right total-row">Tổng cộng:</td>
                <td class="text-right total-final">{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</td>
            </tr>
        </tfoot>
    </table>

    <table style="margin-top: 50px;">
        <tr>
            <td style="width: 50%; text-align: center;">
                <strong>Khách Hàng</strong><br>
                <span style="font-size: 12px; color: #666;">(Ký và ghi rõ họ tên)</span>
            </td>
            <td style="width: 50%; text-align: center;">
                <strong>Người Giao Hàng</strong><br>
                <span style="font-size: 12px; color: #666;">(Ký và ghi rõ họ tên)</span>
            </td>
        </tr>
    </table>

</body>

</html>