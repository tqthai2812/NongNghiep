<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart'; // Laravel thường mặc định số nhiều, nên set cứng tên bảng nếu cần
    protected $fillable = ['user_id', 'package_id', 'quantity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(ProductPackage::class, 'package_id');
    }
}
