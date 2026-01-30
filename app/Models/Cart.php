<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart'; // Laravel mặc định tìm bảng 'carts', nên phải khai báo lại
    protected $primaryKey = 'cart_id';

    protected $fillable = ['user_id', 'package_id', 'quantity'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package()
    {
        return $this->belongsTo(ProductPackage::class, 'package_id');
    }
}
