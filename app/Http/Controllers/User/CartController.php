<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Models\Cart;
use App\Models\ProductPackage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateCartQuantityRequest;

class CartController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $cartItems = Cart::with(['package.packageType.product.primaryImage'])
            ->where('user_id', $userId)
            ->get();

        return view('user.cart', compact('cartItems'));
    }

    /**
     * Cập nhật số lượng qua Ajax
     */
    public function updateQuantity(UpdateCartQuantityRequest $request)
    {
        $userId = request()->user()->id;
        // Lấy item và kiểm tra quyền sở hữu ngay trong câu query
        $cartItem = Cart::where('user_id', Auth::id())
            ->with('package') // Eager load để tránh lỗi N+1 khi check stock
            ->find($request->id);

        if (!$cartItem) {
            return response()->json(['message' => 'Không tìm thấy mục giỏ hàng'], 404);
        }

        // Kiểm tra tồn kho
        if ($cartItem->package->stock < $request->quantity) {
            return response()->json([
                'message' => 'Số lượng vượt quá tồn kho còn lại!',
                'max_stock' => $cartItem->package->stock
            ], 400);
        }

        $cartItem->update(['quantity' => $request->quantity]);

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

    public function addToCart(AddToCartRequest $request)
    {
        $package = ProductPackage::findOrFail($request->package_id);

        if ($package->stock < $request->quantity) {
            return response()->json(['message' => 'Số lượng trong kho không đủ!'], 400);
        }


        $cartItem = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'package_id' => $package->id],
            ['quantity' => 0]
        );
        $cartItem->increment('quantity', $request->quantity);


        return response()->json([
            'status'  => 'success',
            'message' => 'Đã thêm sản phẩm vào giỏ hàng thành công!'
        ]);
    }
}
