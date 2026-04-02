<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        // 1. Lấy dữ liệu bổ trợ
        $categories = Category::all();

        // 2. Thực thi Query qua Model Scopes
        $products = Product::query()
            ->with(['primaryImage', 'packageTypes.packages'])
            ->withStats()
            ->filter($request->only(['query', 'categories', 'min_price', 'max_price', 'rating']))
            ->paginate(20)
            ->withQueryString();

        // 3. Xử lý số lượng bán (Eager Loading nhân tạo cho kết quả trang hiện tại)
        $soldQuantities = OrderItem::getSoldQuantities($products->pluck('id')->toArray());

        $products->getCollection()->transform(function ($product) use ($soldQuantities) {
            $product->total_sold = $soldQuantities[$product->id] ?? 0;
            $product->avg_rating = round($product->avg_rating, 1);
            return $product;
        });

        return view('user.product_search', [
            'products'   => $products,
            'categories' => $categories,
            'keyword'    => $request->input('query')
        ]);
    }
}
