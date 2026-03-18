<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class Homepage extends Controller
{
    public function index()
    {
        $categories = Category::all();

        // Lấy 8 sản phẩm mới nhất
        // Sử dụng with('primaryImage') để lấy kèm ảnh đại diện luôn
        $latestProducts = Product::with('primaryImage')
            ->latest() // Sắp xếp theo created_at giảm dần
            ->take(8)  // Lấy tối đa 8 bản ghi (nếu có 3 thì lấy 3, có 10 thì lấy 8)
            ->get();

        return view('user.index', compact('categories', 'latestProducts'));
    }

    public function productDetail($id)
    {
        // 1. Lấy thông tin sản phẩm kèm các quan hệ cần thiết
        // Thay vì chỉ gọi images và packageTypes, ta gọi lồng thêm reviews thông qua ProductPackage
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
        $totalSold = \App\Models\OrderItem::whereHas('order', function ($q) {
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
