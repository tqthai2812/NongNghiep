<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductPackage;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::with(['package.packageType.product.primaryImage'])
            ->where('user_id', Auth::id())
            ->get();

        return view('user.cart', compact('cartItems'));
    }

    /**
     * Cập nhật số lượng qua Ajax
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:cart,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Không tìm thấy mục giỏ hàng'], 404);
        }

        // Kiểm tra tồn kho trước khi cập nhật
        if ($cartItem->package->stock < $request->quantity) {
            return response()->json([
                'message' => 'Số lượng vượt quá tồn kho còn lại!',
                'max_stock' => $cartItem->package->stock
            ], 400);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Cập nhật thành công'
        ]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function destroy($id)
    {
        $cartItem = Cart::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['status' => 'success', 'message' => 'Đã xóa sản phẩm']);
        }

        return response()->json(['message' => 'Có lỗi xảy ra'], 400);
    }

    public function addToCart(Request $request)
    {
        // 1. Validate dữ liệu
        $request->validate([
            'package_id' => 'required|exists:product_packages,id',
            'quantity'   => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $packageId = $request->package_id;
        $quantity = $request->quantity;

        // 2. Kiểm tra tồn kho thực tế
        $package = ProductPackage::findOrFail($packageId);
        if ($package->stock < $quantity) {
            return response()->json(['message' => 'Số lượng trong kho không đủ!'], 400);
        }

        // 3. Xử lý giỏ hàng
        $cartItem = Cart::where('user_id', $userId)
            ->where('package_id', $packageId)
            ->first();

        if ($cartItem) {
            // Nếu đã có, cộng dồn số lượng
            $cartItem->increment('quantity', $quantity);
        } else {
            // Nếu chưa có, tạo mới
            Cart::create([
                'user_id'    => $userId,
                'package_id' => $packageId,
                'quantity'   => $quantity
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm sản phẩm vào giỏ hàng thành công!'
        ]);
    }
}
