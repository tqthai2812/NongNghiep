<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPackageType extends Model
{
    protected $fillable = ['product_id', 'type_name'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function packages()
    {
        return $this->hasMany(ProductPackage::class, 'package_type_id');
    }
}
