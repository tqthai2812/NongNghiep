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
}
