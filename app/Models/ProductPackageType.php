<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPackageType extends Model
{
    protected $primaryKey = 'package_type_id';

    protected $fillable = ['product_id', 'type_name'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function packages()
    {
        return $this->hasMany(ProductPackage::class, 'package_type_id');
    }
}
