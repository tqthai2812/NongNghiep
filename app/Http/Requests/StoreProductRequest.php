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
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'name.max' => 'Tên sản phẩm không được vượt quá 255 ký tự.',
            'name.string' => 'Tên sản phẩm phải là một chuỗi ký tự.',
            'category_id.required' => 'Vui lòng chọn ngành hàng.',
            'category_id.exists' => 'Ngành hàng không hợp lệ.',
            'brand.required' => 'Vui lòng nhập thương hiệu.',
            'brand.max' => 'Thương hiệu không được vượt quá 255 ký tự.',
            'brand.string' => 'Thương hiệu phải là một chuỗi ký tự.',
            'description.required' => 'Vui lòng nhập mô tả sản phẩm.',
            'description.string' => 'Mô tả sản phẩm phải là một chuỗi ký tự.',
            'package_type_name.required' => 'Vui lòng nhập tên phân loại.',
            'package_type_name.max' => 'Tên phân loại không được vượt quá 255 ký tự.',
            'package_type_name.string' => 'Tên phân loại phải là một chuỗi ký tự.',
            'package_type_unit.max' => 'Đơn vị phân loại không được vượt quá 50 ký tự.',
            'package_type_unit.string' => 'Đơn vị phân loại phải là một chuỗi ký tự.',
            'images.*.image' => 'Tệp phải là hình ảnh.',
            'images.*.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, webp.',
            'images.*.max' => 'Kích thước hình ảnh không được vượt quá 2MB.',
        ];
    }
}
