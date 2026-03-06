<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
        return [
            'name'    => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6',
            'phone_number' => 'nullable|string|max:15',
            'role'         => 'required|in:admin,customer',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active'    => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'Vui lòng nhập họ và tên.',
            'email.required'        => 'Email không được để trống.',
            'email.email'           => 'Địa chỉ email không đúng định dạng.',
            'email.unique'          => 'Email này đã tồn tại trên hệ thống.',
            'password.required'     => 'Vui lòng nhập mật khẩu.',
            'password.min'          => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'role.required'         => 'Vui lòng chọn vai trò người dùng.',
            'role.in'               => 'Vai trò không hợp lệ.',
            'avatar.image'          => 'Tệp tải lên phải là hình ảnh.',
            'avatar.mimes'          => 'Ảnh đại diện chỉ chấp nhận định dạng: jpg, jpeg, png, gif.',
            'avatar.max'            => 'Dung lượng ảnh không được vượt quá 2MB.',
        ];
    }
}
