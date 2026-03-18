<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $table = 'product_reviews';

    protected $fillable = [
        'package_id',
        'user_id',
        'order_id',
        'rating',
        'comment'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(ProductPackage::class, 'package_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
