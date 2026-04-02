<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'highlights',
        'description'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Lấy ảnh đại diện (ảnh primary đầu tiên)
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function packageTypes()
    {
        return $this->hasMany(ProductPackageType::class);
    }

    public function scopeWithStats($query)
    {
        return $query->addSelect([
            'min_price' => ProductPackage::join('product_package_types as ppt', 'product_packages.package_type_id', '=', 'ppt.id')
                ->whereColumn('ppt.product_id', 'products.id')
                ->selectRaw('MIN(price)'),

            'avg_rating' => ProductReview::join('product_packages as pp', 'product_reviews.package_id', '=', 'pp.id')
                ->join('product_package_types as ppt', 'pp.package_type_id', '=', 'ppt.id')
                ->whereColumn('ppt.product_id', 'products.id')
                ->selectRaw('COALESCE(AVG(rating), 0)')
        ]);
    }

    /**
     * Scope: Bộ lọc tìm kiếm tổng hợp
     */
    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['query'] ?? null, function ($q, $keyword) {
            $q->where(function ($inner) use ($keyword) {
                $inner->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('description', 'LIKE', "%{$keyword}%");
            });
        })
            ->when($filters['categories'] ?? null, function ($q, $categories) {
                $q->whereIn('category_id', $categories);
            })
            ->when($filters['min_price'] ?? null, function ($q, $min) {
                $q->having('min_price', '>=', $min);
            })
            ->when($filters['max_price'] ?? null, function ($q, $max) {
                $q->having('min_price', '<=', $max);
            })
            ->when($filters['rating'] ?? null, function ($q, $rating) {
                $q->having('avg_rating', '>=', $rating);
            });
    }
}
