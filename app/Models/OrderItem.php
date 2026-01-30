<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_id',
        'package_id',
        'quantity',
        'price_at_purchase',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function package()
    {
        return $this->belongsTo(ProductPackage::class, 'package_id');
    }
}
