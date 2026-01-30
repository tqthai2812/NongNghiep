<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryMethod extends Model
{
    protected $primaryKey = 'delivery_method_id';

    protected $fillable = ['name', 'cost', 'description'];
}
