<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $fillable = ['package_id', 'user_id', 'type', 'quantity', 'reason'];

    public function package()
    {
        return $this->belongsTo(ProductPackage::class);
    }

    // Người thực hiện giao dịch (Admin)
    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
