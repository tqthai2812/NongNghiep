<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlaceOrderRequest extends FormRequest
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
            'cart_ids'   => 'required|array',
            'cart_ids.*' => 'exists:cart,id',
            'address_id' => 'required|exists:user_addresses,id',
        ];
    }

    public function messages(): array
    {
        return [
            'cart_ids.required' => 'Vui lòng chọn sản phẩm.',
            'cart_ids.array' => 'Dữ liệu sản phẩm không hợp lệ.',
            'cart_ids.*.exists' => 'Sản phẩm trong giỏ hàng không tồn tại.',
            'address_id.required' => 'Vui lòng chọn địa chỉ giao hàng.',
            'address_id.exists' => 'Địa chỉ giao hàng không tồn tại.',
        ];
    }
}
