<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Lấy object user từ route
        $user = $this->route('user');

        // Nếu $user là một Object (do dùng Route Model Binding), ta lấy ID của nó
        // Nếu $user chỉ là ID (chuỗi số), ta dùng trực tiếp
        $userId = is_object($user) ? $user->id : $user;

        return [
            'name'         => 'required|string|max:255',
            // Sửa lại đoạn này: unique:bảng,cột,ID_cần_bỏ_qua
            'email'        => 'required|email|unique:users,email,' . $userId,
            'password'     => 'nullable|string|min:6',
            'phone_number' => 'nullable|string|max:15',
            'role'         => 'required|in:admin,customer',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'    => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Vui lòng nhập họ và tên.',
            'name.string'      => 'Họ và tên phải là một chuỗi ký tự.',
            'name.max'         => 'Họ và tên không được vượt quá 255 ký tự.',
            'email.required'        => 'Email không được để trống.',
            'email.email'           => 'Địa chỉ email không đúng định dạng.',
            'email.unique'          => 'Email này đã tồn tại trên hệ thống.',
            'password.string'       => 'Mật khẩu phải là một chuỗi ký tự.',
            'password.min'          => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'role.required'         => 'Vui lòng chọn vai trò người dùng.',
            'role.in'               => 'Vai trò không hợp lệ.',
            'avatar.image'          => 'Tệp tải lên phải là hình ảnh.',
            'avatar.mimes'          => 'Ảnh đại diện chỉ chấp nhận định dạng: jpg, jpeg, png.',
            'avatar.max'            => 'Dung lượng ảnh không được vượt quá 2MB.',
            'phone_number.string'   => 'Số điện thoại phải là một chuỗi ký tự.',
            'phone_number.max'      => 'Số điện thoại không được vượt quá 15 ký tự.',
            'is_active.boolean'     => 'Trạng thái kích hoạt không hợp lệ.',
        ];
    }
}
