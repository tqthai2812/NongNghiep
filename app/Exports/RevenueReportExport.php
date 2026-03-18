<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RevenueReportExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate, $endDate, $status)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    /**
     * Lấy dữ liệu và "Trải phẳng" (Flatten) các sản phẩm
     */
    public function collection()
    {
        $query = Order::with(['user', 'items.package.packageType.product']);

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->status) {
            $query->where('status', $this->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Khởi tạo một mảng trống để chứa dữ liệu đã được tách dòng
        $exportData = collect();

        foreach ($orders as $order) {
            // Lặp qua từng sản phẩm trong đơn hàng
            foreach ($order->items as $item) {
                // Đẩy từng sản phẩm vào mảng kèm theo thông tin của Đơn hàng gốc
                $exportData->push([
                    'order' => $order,
                    'item'  => $item
                ]);
            }
        }

        return $exportData; // Trả về danh sách chi tiết từng mặt hàng
    }

    /**
     * Tiêu đề các cột trong Excel
     */
    public function headings(): array
    {
        return [
            'MÃ ĐƠN',
            'KHÁCH HÀNG',
            'NGÀY ĐẶT',
            'TRẠNG THÁI',
            'TÊN SẢN PHẨM',
            'PHÂN LOẠI (SIZE)',
            'SỐ LƯỢNG',
            'ĐƠN GIÁ (VNĐ)',
            'THÀNH TIỀN (VNĐ)'
        ];
    }

    /**
     * Định dạng dữ liệu cho từng dòng (Bây giờ mỗi dòng là 1 sản phẩm)
     */
    public function map($row): array
    {
        $order = $row['order'];
        $item = $row['item'];

        $productName = $item->package->packageType->product->name ?? 'Gói hàng ID #' . $item->package_id;
        $size = $item->package->size ?? '';
        $unit = $item->package->unit ?? '';

        return [
            '#' . str_pad($order->id, 5, '0', STR_PAD_LEFT), // Giữ nguyên mã đơn để biết các SP này chung 1 đơn
            $order->user->user_name ?? 'Khách vãng lai',
            $order->created_at->format('d/m/Y H:i'),
            $this->translateStatus($order->status),

            // Tách riêng thông tin sản phẩm ra các cột độc lập
            $productName,
            trim("{$size} {$unit}"),
            $item->quantity,
            $item->price_at_purchase,
            $item->price_at_purchase * $item->quantity
        ];
    }

    /**
     * Định dạng độ rộng cột cho đẹp
     */
    public function columnWidths(): array
    {
        return [
            'A' => 12, // Mã đơn
            'B' => 25, // Khách hàng
            'C' => 18, // Ngày đặt
            'D' => 18, // Trạng thái
            'E' => 40, // Tên sản phẩm (Cột này cho rộng nhất)
            'F' => 18, // Phân loại
            'G' => 12, // Số lượng
            'H' => 18, // Đơn giá
            'I' => 20, // Thành tiền
        ];
    }

    /**
     * In đậm dòng tiêu đề đầu tiên
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function translateStatus($status)
    {
        $statuses = [
            'pending' => 'Chờ xử lý',
            'shipping' => 'Đang giao',
            'completed' => 'Đã hoàn thành',
            'cancelled' => 'Đã hủy'
        ];
        return $statuses[$status] ?? $status;
    }
}
