<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\OrderItem;

class Homepage extends Controller
{
    public function index()
    {
        $latestProducts = Product::with('primaryImage')
            ->latest()
            ->take(8)
            ->get();

        return view('user.index', compact('latestProducts'));
    }

    public function productDetail($id)
    {
        $product = Product::with([
            'images',
            'packageTypes.packages.reviews.user', // Lấy reviews và user của từng package
            'category'
        ])->findOrFail($id);

        // 2. Gom tất cả reviews của sản phẩm này lại thành 1 Collection phẳng (Flat Collection)
        $allReviews = collect();
        foreach ($product->packageTypes as $type) {
            foreach ($type->packages as $package) {
                // Thêm thuộc tính package_full_name vào mỗi review để dễ hiển thị ngoài View
                foreach ($package->reviews as $review) {
                    $review->package_full_name = $type->type_name . ' ' . $package->size . $package->unit;
                    $allReviews->push($review);
                }
            }
        }

        // 3. Sắp xếp review mới nhất lên đầu
        $allReviews = $allReviews->sortByDesc('created_at')->values();

        // 4. Tính toán thống kê đánh giá
        $totalReviews = $allReviews->count();
        $averageRating = $totalReviews > 0 ? round($allReviews->avg('rating'), 1) : 0;

        // Đếm số lượng review theo từng sao (1-5)
        $ratingCounts = [
            5 => $allReviews->where('rating', 5)->count(),
            4 => $allReviews->where('rating', 4)->count(),
            3 => $allReviews->where('rating', 3)->count(),
            2 => $allReviews->where('rating', 2)->count(),
            1 => $allReviews->where('rating', 1)->count(),
        ];

        // Tính tổng số lượng đã bán của sản phẩm này (dựa vào bảng OrderItems)
        $totalSold = OrderItem::whereHas('order', function ($q) {
            $q->where('status', 'completed'); // Chỉ tính những đơn đã giao thành công
        })->whereHas('package.packageType', function ($q) use ($product) {
            $q->where('product_id', $product->id);
        })->sum('quantity');

        return view('user.product', compact(
            'product',
            'allReviews',
            'totalReviews',
            'averageRating',
            'ratingCounts',
            'totalSold'
        ));
    }
}
