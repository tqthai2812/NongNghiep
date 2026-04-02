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

    public function cancelAndRestock($userId)
    {
        // 1. Cập nhật trạng thái đơn hàng
        $this->update(['status' => 'cancelled']);

        // 2. Hoàn kho và ghi log
        foreach ($this->items as $item) {
            $package = ProductPackage::find($item->package_id);

            if ($package) {
                $package->increment('stock', $item->quantity);

                InventoryTransaction::create([
                    'package_id' => $item->package_id,
                    'user_id'    => $userId,
                    'type'       => 'in',
                    'quantity'   => $item->quantity,
                    'reason'     => 'Khách hàng tự hủy đơn #' . $this->id
                ]);
            }
        }
    }
}
