<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubmitReviewRequest extends FormRequest
{
    public function authorize()
    {
        // Kiểm tra bảo mật ngay tại đây: User phải sở hữu đơn hàng đã hoàn thành
        return $this->user()->orders()
            ->where('id', $this->order_id)
            ->where('status', 'completed')
            ->exists();
    }

    public function rules()
    {
        return [
            'order_id'   => 'required|exists:orders,id',
            'package_id' => 'required|exists:product_packages,id',
            'rating'     => 'required|integer|min:1|max:5',
            'comment'    => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'order_id.required'   => 'Mã đơn hàng là bắt buộc.',
            'order_id.exists'     => 'Đơn hàng không tồn tại.',
            'package_id.required' => 'Mã gói sản phẩm là bắt buộc.',
            'package_id.exists'   => 'Gói sản phẩm không tồn tại.',
            'rating.required'     => 'Đánh giá sao là bắt buộc.',
            'rating.integer'      => 'Đánh giá sao phải là số nguyên.',
            'rating.min'          => 'Đánh giá sao phải ít nhất 1 sao.',
            'rating.max'          => 'Đánh giá sao không được vượt quá 5 sao.',
            'comment.string'      => 'Bình luận phải là chuỗi ký tự.',
            'comment.max'         => 'Bình luận không được vượt quá 1000 ký tự.',
        ];
    }
}
