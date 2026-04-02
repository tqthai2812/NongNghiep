<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;

class AddressController extends Controller
{
    public function store(StoreAddressRequest $request)
    {
        $userId = request()->user()->id;

        // 1. Xác định trạng thái mặc định
        // Nếu là địa chỉ đầu tiên HOẶC người dùng check "is_default", thì set true
        $isFirstAddress = !UserAddress::where('user_id', $userId)->exists();
        $isDefault = $isFirstAddress || $request->boolean('is_default');

        // 2. Xử lý logic reset địa chỉ mặc định cũ (chỉ chạy khi địa chỉ mới là mặc định)
        if ($isDefault) {
            UserAddress::where('user_id', $userId)->update(['is_default' => false]);
        }

        // 3. Tạo địa chỉ mới
        $address = UserAddress::create(array_merge(
            $request->validated(),
            [
                'user_id'    => $userId,
                'is_default' => $isDefault
            ]
        ));

        // 4. Trả về response
        if ($request->expectsJson()) {
            return response()->json([
                'status'  => 'success',
                'message' => 'Thêm địa chỉ thành công!',
                'data'    => $address
            ]);
        }

        return back()->with('success', 'Thêm địa chỉ nhận hàng thành công!');
    }

    // ========================================================
    // HÀM MỚI: XỬ LÝ CẬP NHẬT ĐỊA CHỈ
    // ========================================================
    public function update(UpdateAddressRequest $request, $id)
    {

        $userId = request()->user()->id;

        $address = UserAddress::where('user_id', $userId)->findOrFail($id);

        $isDefault = $request->boolean('is_default');

        // Xử lý an toàn: Nếu user chỉ có 1 địa chỉ duy nhất, thì ép buộc nó luôn là mặc định
        $addressCount = UserAddress::where('user_id', $userId)->where('is_default', '=', true)->count();
        if ($addressCount === 1) {
            $isDefault = true;
        }

        // 3. Xử lý logic chuyển đổi địa chỉ mặc định
        if ($isDefault) {
            // Đưa tất cả các địa chỉ KHÁC của user này về trạng thái không mặc định
            UserAddress::where('user_id', $userId)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        // 4. Lưu bản cập nhật vào database
        $address->update(
            array_merge(
                $request->validated(),
                [
                    'is_default' => $isDefault
                ]
            )
        );

        // KIỂM TRA: Trả về JSON để Javascript cập nhật không cần tải lại trang
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Cập nhật địa chỉ thành công!',
                'data' => $address
            ]);
        }

        return back()->with('success', 'Cập nhật địa chỉ thành công!');
    }

    // ========================================================
    // XÓA ĐỊA CHỈ
    // ========================================================
    public function destroy($id)
    {
        $userId = Auth::id();

        // Tìm địa chỉ cần xóa (chỉ tìm trong danh sách của user hiện tại để bảo mật)
        $address = UserAddress::where('user_id', $userId)->findOrFail($id);

        $isDefault = $address->is_default;

        // Tiến hành xóa
        $address->delete();

        // Xử lý thông minh: Nếu địa chỉ vừa xóa là mặc định, tự động gán cái khác làm mặc định
        if ($isDefault) {
            $anotherAddress = UserAddress::where('user_id', $userId)->first();
            if ($anotherAddress) {
                $anotherAddress->update(['is_default' => true]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đã xóa địa chỉ thành công!'
        ]);
    }
}
