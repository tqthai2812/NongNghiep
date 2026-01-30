<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPackage extends Model
{
    protected $primaryKey = 'package_id';

    protected $fillable = [
        'package_type_id',
        'size',
        'unit',
        'price',
        'stock',
    ];

    protected $casts = [
        'size' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function type()
    {
        return $this->belongsTo(ProductPackageType::class, 'package_type_id');
    }

    // Lấy tên sản phẩm cha (VD: "Phân bón NPK")
    public function getProductNameAttribute()
    {
        return $this->type->product->name ?? 'Unknown Product';
    }

    // Lấy tên đầy đủ (VD: "Phân bón NPK - Bao 10kg")
    public function getFullNameAttribute()
    {
        $productName = $this->type->product->name ?? '';
        $typeName = $this->type->type_name ?? '';
        // Format size kiểu 10kg hoặc 1 lít
        $sizeInfo = floatval($this->size) . ' ' . $this->unit;

        return "{$productName} ({$typeName} {$sizeInfo})";
    }
}
