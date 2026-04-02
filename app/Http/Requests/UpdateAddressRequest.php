<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAddressRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'receiver_name.required' => 'Tên người nhận không được để trống.',
            'receiver_name.string' => 'Tên người nhận phải là một chuỗi.',
            'receiver_name.max' => 'Tên người nhận không được vượt quá 255 ký tự.',
            'receiver_phone.required' => 'Số điện thoại người nhận không được để trống.',
            'receiver_phone.string' => 'Số điện thoại người nhận phải là một chuỗi.',
            'receiver_phone.max' => 'Số điện thoại người nhận không được vượt quá 20 ký tự.',
            'province.required' => 'Tỉnh/Thành phố không được để trống.',
            'district.required' => 'Quận/Huyện không được để trống.',
            'ward.required' => 'Phường/Xã không được để trống.',
            'province_id.required' => 'ID Tỉnh/Thành phố không được để trống.',
            'district_id.required' => 'ID Quận/Huyện không được để trống.',
            'ward_id.required' => 'ID Phường/Xã không được để trống.',
            'address_detail.required' => 'Địa chỉ chi tiết không được để trống.',
            'address_detail.string' => 'Địa chỉ chi tiết phải là một chuỗi.',
            'address_detail.max' => 'Địa chỉ chi tiết không được vượt quá 255 ký tự.',
            'address_type.required' => 'Loại địa chỉ không được để trống.',
            'address_type.in' => 'Loại địa chỉ phải là "home" hoặc "office".',
        ];
    }
}
