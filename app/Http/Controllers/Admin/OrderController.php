<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\InventoryTransaction; // Đảm bảo bạn đã có Model này
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Sử dụng thư viện DomPDF
use Maatwebsite\Excel\Facades\Excel; // Sử dụng thư viện Excel
use App\Exports\RevenueReportExport; // Class Export bạn đã tạo

class OrderController extends Controller
{
    /**
     * Hiển thị danh sách đơn hàng
     */
    public function index()
    {
        // Eager load 'user' để tránh lỗi N+1 Query
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Cập nhật trạng thái đơn hàng (Inline update)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,shipping,completed,cancelled'
        ]);

        // Eager load 'items.package' vì ta cần thao tác với số lượng tồn kho
        $order = Order::with('items.package')->findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return redirect()->back();
        }

        try {
            DB::beginTransaction();

            // 1. Cập nhật trạng thái mới
            $order->status = $newStatus;
            $order->save();

            // 2. Xử lý Logic Tồn Kho (Inventory)

            // TRƯỜNG HỢP A: Đơn hàng MỚI BỊ HỦY -> Hoàn lại hàng vào kho
            if ($oldStatus !== 'cancelled' && $newStatus === 'cancelled') {
                foreach ($order->items as $item) {
                    // Cộng lại số lượng tồn
                    $item->package->increment('stock', $item->quantity);

                    // Ghi log Transaction
                    InventoryTransaction::create([
                        'package_id' => $item->package_id,
                        'user_id' => Auth::id(), // Admin thao tác
                        'type' => 'in',
                        'quantity' => $item->quantity, // Số lượng nhập lại
                        'reason' => "Hoàn kho do hủy đơn hàng #{$order->id}"
                    ]);
                }
            }
            // TRƯỜNG HỢP B: Phục hồi đơn hàng TỪ TRẠNG THÁI HỦY -> Trừ lại hàng trong kho
            elseif ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    // Trừ số lượng tồn
                    $item->package->decrement('stock', $item->quantity);

                    // Ghi log Transaction
                    InventoryTransaction::create([
                        'package_id' => $item->package_id,
                        'user_id' => Auth::id(),
                        'type' => 'out',
                        'quantity' => $item->quantity,
                        'reason' => "Trừ kho do phục hồi đơn hàng #{$order->id}"
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Đã cập nhật trạng thái đơn hàng #' . $order->id . ' thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xóa đơn hàng (Chỉ cho phép khi đã Cancelled)
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'cancelled') {
            return redirect()->back()->with('error', 'Lỗi! Chỉ có thể xóa các đơn hàng đã bị hủy.');
        }

        try {
            $order->delete();
            return redirect()->back()->with('success', 'Đã xóa đơn hàng thành công!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể xóa đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết một đơn hàng
     */
    public function show($id)
    {
        // Sử dụng $order->items() theo đúng Model của bạn
        $order = Order::with(['user', 'items.package'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function print($id)
    {
        // Lấy dữ liệu đơn hàng giống như hàm show
        $order = Order::with(['user', 'items.package.packageType.product'])->findOrFail($id);

        // Tạo PDF từ một View riêng biệt dành cho việc in
        $pdf = Pdf::loadView('admin.orders.print', compact('order'));

        // Trả về file PDF để xem trực tiếp trên trình duyệt (hoặc dùng ->download() để tải về)
        return $pdf->stream('Don_Hang_' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '.pdf');
    }

    /**
     * Lấy dữ liệu Báo cáo Doanh thu & Đơn hàng (dùng cho AJAX Xuất Excel)
     */
    public function getRevenueReportData(\Illuminate\Http\Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $status = $request->status;

        $filename = 'Bao_Cao_Doanh_Thu_' . date('d_m_Y_H_i') . '.xlsx';

        // Gọi Class Export và tải thẳng file về máy
        return Excel::download(new RevenueReportExport($startDate, $endDate, $status), $filename);
    }

    // Hàm phụ trợ dịch trạng thái
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
