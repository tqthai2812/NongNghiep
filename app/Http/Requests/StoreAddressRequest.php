<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAddressRequest extends FormRequest
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

    public function messages(): array
    {
        return [
            'receiver_name.required' => 'Tên người nhận là bắt buộc.',
            'receiver_name.string' => 'Tên người nhận phải là chuỗi ký tự.',
            'receiver_name.max' => 'Tên người nhận không được vượt quá 255 ký tự.',
            'receiver_phone.required' => 'Số điện thoại người nhận là bắt buộc.',
            'receiver_phone.string' => 'Số điện thoại người nhận phải là chuỗi ký tự.',
            'receiver_phone.max' => 'Số điện thoại người nhận không được vượt quá 20 ký tự.',
            'province.required' => 'Tỉnh/Thành phố là bắt buộc.',
            'province.string' => 'Tỉnh/Thành phố phải là chuỗi ký tự.',
            'district.required' => 'Quận/Huyện là bắt buộc.',
            'district.string' => 'Quận/Huyện phải là chuỗi ký tự.',
            'ward.required' => 'Phường/Xã là bắt buộc.',
            'ward.string' => 'Phường/Xã phải là chuỗi ký tự.',
            'province_id.required' => 'ID Tỉnh/Thành phố là bắt buộc.',
            'province_id.string' => 'ID Tỉnh/Thành phố phải là chuỗi ký tự.',
            'district_id.required' => 'ID Quận/Huyện là bắt buộc.',
            'district_id.string' => 'ID Quận/Huyện phải là chuỗi ký tự.',
            'ward_id.required' => 'ID Phường/Xã là bắt buộc.',
            'ward_id.string' => 'ID Phường/Xã phải là chuỗi ký tự.',
            'address_detail.required' => 'Địa chỉ chi tiết là bắt buộc.',
            'address_detail.string' => 'Địa chỉ chi tiết phải là chuỗi ký tự.',
            'address_detail.max' => 'Địa chỉ chi tiết không được vượt quá 255 ký tự.',
            'address_type.required' => 'Loại địa chỉ là bắt buộc.',
            'address_type.in' => 'Loại địa chỉ phải là "home" hoặc "office".',
        ];
    }
}
