<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem; // Import model OrderItem để tính tổng số lượng đã bán

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // 1. Nhận các tham số từ URL
        $keyword = $request->input('query');
        $categoryIds = $request->input('categories', []); // Mảng ID danh mục
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $rating = $request->input('rating');

        // Lấy danh sách danh mục để đổ ra Sidebar
        $categories = Category::all();

        // 2. Khởi tạo Query builder (Kèm Eager Loading để tránh N+1 Query)
        $query = Product::with(['primaryImage', 'packageTypes.packages.reviews']);

        // --- BỘ LỌC TỪ KHÓA ---
        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('description', 'LIKE', '%' . $keyword . '%');
            });
        }

        // --- BỘ LỌC DANH MỤC ---
        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        // --- BỘ LỌC GIÁ (Query xuyên qua bảng Packages) ---
        if ($minPrice !== null || $maxPrice !== null) {
            $query->whereHas('packageTypes.packages', function ($q) use ($minPrice, $maxPrice) {
                if ($minPrice !== null) $q->where('price', '>=', $minPrice);
                if ($maxPrice !== null) $q->where('price', '<=', $maxPrice);
            });
        }

        // 3. Thực thi Query và Phân trang (Giữ nguyên các param trên URL khi chuyển trang)
        $products = $query->paginate(20)->withQueryString();

        // 4. Tính toán Giá Min và Sao trung bình để View dễ hiển thị
        $products->getCollection()->transform(function ($product) {
            // Lấy ra giá thấp nhất trong tất cả các phân loại hàng của SP này
            $product->min_price = $product->packageTypes->flatMap->packages->min('price') ?? 0;

            // Tính số lượng bán (Mock data tạm, nếu bạn đã setup OrderItems thì thay logic vào đây)
            $product->total_sold = OrderItem::whereHas('package.packageType', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
                ->whereHas('order', function ($query) {
                    $query->where('status', 'completed');
                })
                ->sum('quantity');;

            // Tính sao trung bình
            $allReviews = $product->packageTypes->flatMap->packages->flatMap->reviews;
            $product->avg_rating = $allReviews->count() > 0 ? round($allReviews->avg('rating'), 1) : 0;

            return $product;
        });

        // --- BỘ LỌC SAO (Xử lý ở Collection do hạn chế cấu trúc bảng) ---
        if ($rating !== null) {
            $filteredItems = $products->getCollection()->filter(function ($product) use ($rating) {
                return $product->avg_rating >= $rating;
            });
            $products->setCollection($filteredItems);
        }

        return view('user.product_search', compact('products', 'categories', 'keyword'));
    }
}
