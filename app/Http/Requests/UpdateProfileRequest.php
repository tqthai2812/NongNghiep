<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $this->user()->id,
            'phone_number' => 'nullable|string|max:20',
            'avatar'       => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Vui lòng nhập tên.',
            'name.string' => 'Tên phải là chuỗi ký tự.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',
            'email.required'     => 'Vui lòng nhập email.',
            'email.email'        => 'Vui lòng nhập đúng định dạng email.',
            'email.max'          => 'Email không được vượt quá 255 ký tự.',
            'email.unique'       => 'Email này đã được sử dụng.',
            'phone_number.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone_number.max' => 'Số điện thoại không được vượt quá 20 ký tự.',
            'avatar.max'         => 'Dung lượng ảnh tối đa là 1MB.',
            'avatar.image'       => 'File tải lên phải là hình ảnh.',
        ];
    }
}
