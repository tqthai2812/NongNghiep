<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\ProductPackage;
use App\Models\InventoryTransaction;
use App\Models\ProductReview;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        // Sử dụng helper để quản lý các số liệu chính
        $stats = [
            'totalRevenue'       => Order::where('status', 'completed')->sum('total_price'),
            'pendingOrdersCount' => Order::where('status', 'pending')->count(),
            'lowStockCount'      => ProductPackage::where('stock', '<', 10)->count(),
            'totalCustomers'     => User::where('role', 'customer')->count(),
        ];

        // Eager Loading các danh sách
        $recentOrders = Order::with('user')->latest()->take(7)->get();
        $inventoryLogs = InventoryTransaction::with('admin')->latest()->take(6)->get();

        // Tách logic xử lý Chart ra một hàm private hoặc Service
        $userChart = $this->getUserRegistrationChart();
        $reviewChart = $this->getReviewChart();
        $categoryChart = $this->getCategoryChart();

        return view('admin.dashboard.index', array_merge(
            $stats,
            compact('recentOrders', 'inventoryLogs'),
            $userChart,
            $reviewChart,
            $categoryChart
        ));
    }

    private function getUserRegistrationChart()
    {
        $days = 7;
        $usersPerDay = User::where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date'); // Biến thành mảng ['2023-01-01' => 5]

        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d/m');
            $data[] = $usersPerDay[$date->format('Y-m-d')] ?? 0;
        }

        return ['chartLabels' => $labels, 'chartData' => $data];
    }

    /**
     * Lấy dữ liệu biểu đồ tròn cho đánh giá sao
     */
    private function getReviewChart()
    {
        // Lấy số lượng đánh giá nhóm theo 'rating' (1-5 sao)
        $reviewCounts = ProductReview::selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        // Sắp xếp mảng cố định từ 5 sao xuống 1 sao
        $labels = ['5 Sao', '4 Sao', '3 Sao', '2 Sao', '1 Sao'];
        $data = [
            $reviewCounts[5] ?? 0,
            $reviewCounts[4] ?? 0,
            $reviewCounts[3] ?? 0,
            $reviewCounts[2] ?? 0,
            $reviewCounts[1] ?? 0,
        ];

        return [
            'reviewLabels' => $labels,
            'reviewData'   => $data
        ];
    }

    /**
     * Lấy dữ liệu biểu đồ tròn cho tỷ trọng sản phẩm theo danh mục
     */
    private function getCategoryChart()
    {
        // Lấy danh mục có sản phẩm, đếm số lượng sản phẩm trong mỗi danh mục
        // Category::withCount('products') sẽ tạo ra biến 'products_count'
        $categories = Category::withCount('products')
            ->having('products_count', '>', 0)
            ->get();

        return [
            'categoryLabels' => $categories->pluck('name')->toArray(),
            'categoryData'   => $categories->pluck('products_count')->toArray(),
        ];
    }
}
