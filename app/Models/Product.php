<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'highlights',
        'description',
    ];

    // 1. Thuộc danh mục nào
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // 2. Có nhiều ảnh
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id')->orderBy('sort_order');
    }

    // Helper: Lấy ảnh đại diện (ưu tiên is_primary, hoặc ảnh đầu tiên)
    public function thumbnail()
    {
        return $this->hasOne(ProductImage::class, 'product_id')
            ->orderByDesc('is_primary')
            ->orderBy('sort_order');
    }

    // 3. Có nhiều loại bao bì (Bao, Chai)
    public function packageTypes()
    {
        return $this->hasMany(ProductPackageType::class, 'product_id');
    }

    // 4. Lấy TẤT CẢ các gói (SKU) của sản phẩm này (Băng qua bảng trung gian PackageType)
    // Rất tiện khi muốn show list giá: Bao 10kg - 50k, Chai 1L - 20k
    public function packages()
    {
        return $this->hasManyThrough(
            ProductPackage::class,      // Model đích
            ProductPackageType::class,  // Model trung gian
            'product_id',               // FK ở bảng trung gian
            'package_type_id',          // FK ở bảng đích
            'product_id',               // PK ở bảng hiện tại
            'package_type_id'           // PK ở bảng trung gian
        );
    }
}
