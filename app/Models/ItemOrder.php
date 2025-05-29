<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemOrder extends Model
{
    protected $table = 'items_orders';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
