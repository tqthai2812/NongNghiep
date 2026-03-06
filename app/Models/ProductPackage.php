<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPackage extends Model
{
    protected $fillable = ['package_type_id', 'size', 'unit', 'price', 'stock'];

    public function packageType()
    {
        return $this->belongsTo(ProductPackageType::class, 'package_type_id');
    }

    // Một trick nhỏ để lấy ngược lại Product cha từ gói con
    public function getProductAttribute()
    {
        return $this->packageType->product ?? null;
    }

    // Sử dụng: $package->full_name
    public function getFullNameAttribute()
    {
        // Load relationship nếu chưa có để tránh lỗi
        $this->loadMissing(['packageType.product']);

        $productName = $this->packageType->product->name ?? 'Unknown Product';
        $typeName = $this->packageType->type_name ?? '';
        $sizeUnit = $this->size . ' ' . $this->unit;

        return "{$productName} - {$typeName} {$sizeUnit}";
    }
}
