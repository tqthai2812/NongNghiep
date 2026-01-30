<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'package_id',
        'user_id',
        'type', // in, out, adjust
        'quantity',
        'reason',
    ];

    public function package()
    {
        return $this->belongsTo(ProductPackage::class, 'package_id');
    }

    public function user() // Admin thực hiện
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
