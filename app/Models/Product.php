<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'price', 'stock', 'description', 'photo',
        'discount_min_qty', 'discount', 'stock_lower_limit', 'stock_upper_limit', 'custom'
    ];

    // Relação com a tabela categories
    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    public function itemsOrders()
    {
        return $this->hasMany(ItemOrder::class, 'product_id');
    }


}
