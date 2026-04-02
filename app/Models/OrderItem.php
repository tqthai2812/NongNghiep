<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'package_id', 'quantity', 'price_at_purchase'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function package()
    {
        return $this->belongsTo(ProductPackage::class);
    }

    public static function getSoldQuantities(array $productIds)
    {
        return self::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_packages as pp', 'order_items.package_id', '=', 'pp.id')
            ->join('product_package_types as ppt', 'pp.package_type_id', '=', 'ppt.id')
            ->whereIn('ppt.product_id', $productIds)
            ->where('orders.status', 'completed')
            ->selectRaw('ppt.product_id, SUM(order_items.quantity) as total_sold')
            ->groupBy('ppt.product_id')
            ->pluck('total_sold', 'ppt.product_id');
    }
}
