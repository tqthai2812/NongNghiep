<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\SubmitReviewRequest;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function orderhistory()
    {
        $user = request()->user();

        $orders = Order::with([
            'items.package.packageType.product.primaryImage', // Load kèm thông tin sản phẩm và ảnh đại diện
            'reviews' // Load kèm review để check xem đã đánh giá chưa
        ])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.order_history', compact('orders'));
    }

    public function submitReview(SubmitReviewRequest $request)
    {
        $user = $request->user();

        // Do Form Request đã check quyền sở hữu đơn hàng, ta chỉ cần check xem đã đánh giá chưa
        $hasReviewed = $user->reviews()
            ->where('order_id', $request->order_id)
            ->where('package_id', $request->package_id)
            ->exists();

        if ($hasReviewed) {
            return back()->with('error', 'Bạn đã đánh giá sản phẩm này rồi!');
        }

        // Lưu thẳng vào DB
        $user->reviews()->create($request->validated());

        return back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    public function cancelOrder(Request $request, $id)
    {
        // 1. Tìm đơn hàng và eager load sẵn items để tránh N+1 Query
        $order = Order::with('items')->where('user_id', $request->user()->id)->findOrFail($id);

        // 2. Bảo mật: Kiểm tra trạng thái
        if ($order->status !== 'pending') {
            return back()->with('error', 'Không thể hủy đơn hàng ở trạng thái này!');
        }

        // 3. Thực thi logic nghiệp vụ và bọc trong Transaction tự động
        try {
            DB::transaction(function () use ($order, $request) {
                $order->cancelAndRestock($request->user()->id);
            });

            return back()->with('success', 'Đã hủy đơn hàng thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function profile_edit()
    {
        return view('user.profile_edit');
    }

    public function address_edit(Request $request)
    {
        $addresses = UserAddress::where('user_id', $request->user()->id)
            ->ordered() // Gọi scope đã định nghĩa ở trên
            ->get();

        return view('user.address_edit', compact('addresses'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();

        // 1. Lấy dữ liệu đã qua kiểm duyệt (chỉ lấy các trường text)
        $data = $request->safe()->only(['name', 'email', 'phone_number']);

        // 2. Xử lý ảnh bằng một hàm riêng (hoặc Service)
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->uploadAvatar($request->file('avatar'), $user->avatar);
        }

        // 3. Cập nhật hàng loạt (Mass Assignment)
        $user->update($data);

        return back()->with('success', 'Cập nhật thông tin hồ sơ thành công!');
    }

    private function uploadAvatar($file, $oldPath): string
    {
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $filename = time() . '_' . $file->getClientOriginalName();

        return $file->storeAs('avatars', $filename, 'public');
    }
}
