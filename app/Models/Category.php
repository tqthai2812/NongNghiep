<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'category_id';

    protected $fillable = ['name', 'description'];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
