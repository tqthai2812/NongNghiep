<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate dữ liệu gửi lên
        $request->validate([
            'receiver_name'  => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'province'       => 'required|string',
            'district'       => 'required|string',
            'ward'           => 'required|string',
            'province_id'    => 'required|string',
            'district_id'    => 'required|string',
            'ward_id'        => 'required|string',
            'address_detail' => 'required|string|max:255',
            'address_type'   => 'required|in:home,office',
        ], [
            // Tùy chỉnh câu thông báo lỗi (nếu cần)
            'receiver_name.required' => 'Vui lòng nhập họ tên người nhận.',
            'receiver_phone.required' => 'Vui lòng nhập số điện thoại.',
            'province_id.required' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'district_id.required' => 'Vui lòng chọn Quận/Huyện.',
            'ward_id.required' => 'Vui lòng chọn Phường/Xã.',
            'address_detail.required' => 'Vui lòng nhập địa chỉ cụ thể.',
        ]);

        $userId = Auth::id();
        $isDefault = $request->has('is_default') ? true : false;

        // Nếu đây là địa chỉ đầu tiên của user, tự động cho nó làm mặc định
        $addressCount = UserAddress::where('user_id', $userId)->count();
        if ($addressCount === 0) {
            $isDefault = true;
        }

        // 2. Xử lý logic địa chỉ mặc định
        if ($isDefault) {
            // Cập nhật tất cả địa chỉ cũ của user này thành không mặc định
            UserAddress::where('user_id', $userId)->update(['is_default' => false]);
        }

        // 3. Lưu dữ liệu vào Database
        $address = UserAddress::create([
            'user_id'        => $userId,
            'receiver_name'  => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'province'       => $request->province, // Tên tỉnh (lấy từ input hidden)
            'district'       => $request->district, // Tên huyện
            'ward'           => $request->ward,     // Tên xã
            'province_id'    => $request->province_id,
            'district_id'    => $request->district_id,
            'ward_id'        => $request->ward_id,
            'address_detail' => $request->address_detail,
            'address_type'   => $request->address_type,
            'is_default'     => $isDefault,
            // latitude và longitude tạm thời để trống (null) vì form chưa có Google Map API
        ]);

        // KIỂM TRA: Nếu là request AJAX thì trả về JSON
        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Thêm địa chỉ thành công!',
                'data' => $address
            ]);
        }

        // Backup: Dành cho trường hợp lỡ submit form theo cách truyền thống
        return back()->with('success', 'Thêm địa chỉ nhận hàng thành công!');
    }

    // ========================================================
    // HÀM MỚI: XỬ LÝ CẬP NHẬT ĐỊA CHỈ
    // ========================================================
    public function update(Request $request, $id)
    {
        // 1. Validate dữ liệu gửi lên (Giống như thêm mới)
        $request->validate([
            'receiver_name'  => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:20',
            'province'       => 'required|string',
            'district'       => 'required|string',
            'ward'           => 'required|string',
            'province_id'    => 'required|string',
            'district_id'    => 'required|string',
            'ward_id'        => 'required|string',
            'address_detail' => 'required|string|max:255',
            'address_type'   => 'required|in:home,office',
        ]);

        $userId = Auth::id();

        // 2. Tìm địa chỉ cần sửa (bắt buộc phải thuộc về user đang đăng nhập để tránh lỗi bảo mật)
        $address = UserAddress::where('user_id', $userId)->findOrFail($id);

        $isDefault = $request->has('is_default') ? true : false;

        // Xử lý an toàn: Nếu user chỉ có 1 địa chỉ duy nhất, thì ép buộc nó luôn là mặc định
        $addressCount = UserAddress::where('user_id', $userId)->count();
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
        $address->update([
            'receiver_name'  => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'province'       => $request->province,
            'district'       => $request->district,
            'ward'           => $request->ward,
            'province_id'    => $request->province_id,
            'district_id'    => $request->district_id,
            'ward_id'        => $request->ward_id,
            'address_detail' => $request->address_detail,
            'address_type'   => $request->address_type,
            'is_default'     => $isDefault,
        ]);

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
