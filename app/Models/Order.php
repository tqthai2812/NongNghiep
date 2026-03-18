<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Bỏ delivery_method_id vì DB không có
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'shipping_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    // Thêm hàm này vào Model Order của bạn
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'order_id');
    }
}
