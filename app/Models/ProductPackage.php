<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductPackageType;
use Illuminate\Support\Facades\DB; // Thêm dòng này

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

    // Thêm relationship này để lấy danh sách đánh giá của gói sản phẩm
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'package_id');
    }

    // Trong Model ProductPackage
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'package_id');
    }

    public function getTotalSalesAttribute()
    {
        // Tổng = Số lượng * Giá tại thời điểm mua
        return $this->orderItems()->sum(DB::raw('quantity * price_at_purchase'));
    }
}
