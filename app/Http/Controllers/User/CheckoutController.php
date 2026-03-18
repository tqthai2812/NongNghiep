<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductPackage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Lấy mảng ID từ form giỏ hàng gửi sang (qua request POST)
        $cartIds = $request->input('cart_ids');

        if (!$cartIds || empty($cartIds)) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn sản phẩm.');
        }

        $user = Auth::user();

        // Chỉ lấy ra các item trong giỏ hàng có ID nằm trong mảng $cartIds
        // Đi kèm eager loading theo sơ đồ database bạn cung cấp
        $selectedItems = Cart::with(['package.product.images', 'package.packageType'])
            ->where('user_id', $user->id)
            ->whereIn('id', $cartIds)
            ->get();

        // Tính tổng tiền dựa trên các mặt hàng được chọn
        $totalAmount = $selectedItems->sum(function ($item) {
            return $item->package->price * $item->quantity;
        });

        $addresses = $user->addresses;
        $shippingFee = 30000; // Hoặc gọi API tính phí vận chuyển tự động ở đây

        return view('user.checkout', compact('selectedItems', 'addresses', 'totalAmount', 'shippingFee'));
    }

    public function placeOrder(Request $request)
    {
        // 1. Validate dữ liệu gửi lên từ giao diện Checkout
        $request->validate([
            'cart_ids'   => 'required|array',
            'cart_ids.*' => 'exists:cart,id',
            'address_id' => 'required|exists:user_addresses,id',
        ]);

        $user = Auth::user();
        $cartIds = $request->cart_ids;

        try {
            // Bắt đầu Transaction
            DB::beginTransaction();

            // 2. Lấy các sản phẩm trong giỏ hàng và kiểm tra
            $cartItems = Cart::with('package.product')->where('user_id', $user->id)->whereIn('id', $cartIds)->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Không có sản phẩm nào để thanh toán.');
            }

            $totalItemPrice = 0;

            // 3. Kiểm tra tồn kho và tính tổng tiền sản phẩm
            foreach ($cartItems as $item) {
                if ($item->package->stock < $item->quantity) {
                    throw new \Exception('Sản phẩm "' . $item->package->product->name . '" không đủ số lượng trong kho.');
                }
                $totalItemPrice += $item->package->price * $item->quantity;
            }

            // Cộng thêm phí ship (30k như bạn đã fix cứng ở hàm index)
            $shippingFee = 30000;
            $totalOrderPrice = $totalItemPrice + $shippingFee;

            // 4. Lấy địa chỉ và chuyển thành dạng chuỗi (Text) để lưu vào bảng Orders
            $address = UserAddress::where('user_id', $user->id)->findOrFail($request->address_id);
            $shippingAddressString = sprintf(
                "%s - %s - %s, %s, %s, %s",
                $address->receiver_name,
                $address->receiver_phone,
                $address->address_detail,
                $address->ward,
                $address->district,
                $address->province
            );

            // 5. Tạo đơn hàng (Bảng Orders)
            $order = Order::create([
                'user_id'          => $user->id,
                'total_price'      => $totalOrderPrice,
                'status'           => 'pending', // Trạng thái mặc định
                'shipping_address' => $shippingAddressString
            ]);

            // 6. Tạo chi tiết đơn hàng (Bảng Order_Items), trừ kho và Ghi log tồn kho
            foreach ($cartItems as $item) {
                // Thêm vào Order_Items
                OrderItem::create([
                    'order_id'          => $order->id,
                    'package_id'        => $item->package_id,
                    'quantity'          => $item->quantity,
                    'price_at_purchase' => $item->package->price // Lưu lại giá tại thời điểm mua
                ]);

                // Trừ số lượng trong kho
                $package = ProductPackage::find($item->package_id);
                $package->decrement('stock', $item->quantity);

                // Ghi log vào bảng Inventory_Transactions như thiết kế DB
                InventoryTransaction::create([
                    'package_id' => $item->package_id,
                    'user_id'    => $user->id, // Người tạo giao dịch (ở đây là khách mua)
                    'type'       => 'out',     // Xuất kho
                    'quantity'   => -$item->quantity, // Số lượng âm
                    'reason'     => 'Khách hàng đặt đơn #' . $order->id
                ]);
            }

            // 7. Xóa các sản phẩm đã mua khỏi Giỏ hàng (Cart)
            Cart::whereIn('id', $cartIds)->delete();

            // Lưu toàn bộ thay đổi vào DB
            DB::commit();

            // Thành công thì chuyển hướng về trang thông báo hoặc lịch sử đơn hàng
            return redirect()->route('order.success', ['id' => $order->id])->with('success', 'Đặt hàng thành công!');
        } catch (\Exception $e) {
            // Nếu có lỗi ở bất kỳ bước nào, rollback toàn bộ
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
}
