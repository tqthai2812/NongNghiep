<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'receiver_name',
        'receiver_phone',
        'province',
        'district',
        'ward',
        'province_id',
        'district_id',
        'ward_id',
        'address_detail',
        'address_type',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc');
    }
}
