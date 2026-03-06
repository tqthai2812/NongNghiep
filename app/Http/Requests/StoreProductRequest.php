<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|max:255',
            'description' => 'required|string',

            // Validate phân loại
            'package_type_name' => 'required|string|max:255',
            'package_type_unit' => 'nullable|string|max:50',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048'
        ];
    }

    public function messages()
    {
        return [
            'category_id.required' => 'Vui lòng chọn ngành hàng.',
        ];
    }
}
