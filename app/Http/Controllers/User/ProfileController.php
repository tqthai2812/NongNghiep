<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductPackage;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\DB;
use App\Models\ProductReview;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('user.profile');
    }

    public function orderhistory()
    {
        $userId = Auth::id();

        $orders = Order::with([
            'items.package.product.primaryImage',
            'items.package.packageType',
            'reviews' // Load kèm review để check xem đã đánh giá chưa
        ])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.order_history', compact('orders'));
    }

    public function submitReview(Request $request)
    {
        $request->validate([
            'order_id'   => 'required|exists:orders,id',
            'package_id' => 'required|exists:product_packages,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ]);

        $userId = Auth::id();

        // Kiểm tra xem đã đánh giá chưa (tránh spam F5)
        $exists = ProductReview::where('user_id', $userId)
            ->where('order_id', $request->order_id)
            ->where('package_id', $request->package_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi!');
        }

        // Lưu đánh giá
        ProductReview::create([
            'user_id'    => $userId,
            'order_id'   => $request->order_id,
            'package_id' => $request->package_id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    public function cancelOrder(Request $request, $id)
    {
        $user = Auth::user();

        // Tìm đơn hàng của user này
        $order = Order::where('user_id', $user->id)->findOrFail($id);

        // Bảo mật lớp thứ 2: Đảm bảo chỉ đơn 'pending' mới được hủy
        if ($order->status !== 'pending') {
            return back()->with('error', 'Không thể hủy đơn hàng ở trạng thái này!');
        }

        try {
            DB::beginTransaction();

            // 1. Cập nhật trạng thái đơn hàng thành 'cancelled' (Đã hủy)
            $order->status = 'cancelled';
            $order->save();

            // 2. Hoàn lại số lượng sản phẩm vào kho (Restock)
            // Lấy các chi tiết đơn hàng (Order_Items)
            foreach ($order->items as $item) {
                $package = ProductPackage::find($item->package_id);
                if ($package) {
                    // Cộng lại số lượng tồn kho
                    $package->increment('stock', $item->quantity);

                    // Ghi log vào bảng Inventory_Transactions là nhập lại kho
                    InventoryTransaction::create([
                        'package_id' => $item->package_id,
                        'user_id'    => $user->id, // Người thao tác là khách hàng tự hủy
                        'type'       => 'in',      // Nhập lại vào kho
                        'quantity'   => $item->quantity, // Số lượng dương (cộng vào)
                        'reason'     => 'Khách hàng tự hủy đơn #' . $order->id
                    ]);
                }
            }

            DB::commit();
            return back()->with('success', 'Đã hủy đơn hàng thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function profile_edit()
    {
        return view('user.profile_edit');
    }

    public function address_edit()
    {
        $userId = Auth::id();

        // Lấy danh sách địa chỉ của user đang đăng nhập
        // Sắp xếp: Địa chỉ mặc định (is_default = 1) lên đầu tiên, sau đó sắp xếp theo thời gian tạo mới nhất
        $addresses = UserAddress::where('user_id', $userId)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.address_edit', compact('addresses'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // 1. Validate dữ liệu gửi lên
        $request->validate([
            'name'    => 'required|string|max:255',
            // Rule unique: bỏ qua email của chính user hiện tại
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            // File ảnh: dung lượng tối đa 1024 KB (1MB), định dạng jpeg, png, jpg
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ], [
            'name.required' => 'Vui lòng nhập tên.',
            'email.required'     => 'Vui lòng nhập email.',
            'email.unique'       => 'Email này đã được sử dụng.',
            'avatar.max'         => 'Dung lượng ảnh tối đa là 1MB.',
            'avatar.image'       => 'File tải lên phải là hình ảnh.',
        ]);

        // 2. Cập nhật thông tin text
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;

        // 3. Xử lý tải ảnh (Avatar)
        if ($request->hasFile('avatar')) {
            // (Tùy chọn) Xóa ảnh cũ đi cho nhẹ bộ nhớ nếu không phải ảnh mặc định
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            // Lưu ảnh mới vào thư mục storage/app/public/avatars
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            // Cập nhật đường dẫn vào DB
            $user->avatar = $avatarPath;
        }

        // 4. Lưu vào Database
        /** @var \App\Models\User $user */
        $user->save();

        return back()->with('success', 'Cập nhật thông tin hồ sơ thành công!');
    }
}
