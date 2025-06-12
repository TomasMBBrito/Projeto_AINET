<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'price',
        'stock',
        'description',
        'photo',
        'discount_min_qty',
        'discount',
        'stock_lower_limit',
        'stock_upper_limit',
        'custom'
    ];

    // Relação com a tabela categories
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    // Relação com items_orders (pivot)
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'items_orders')
        ->withPivot(['quantity', 'unit_price', 'discount', 'subtotal'])
        ->as('order_item');
    }



    // Relação alternativa (se precisar)
    // public function orderItems()
    // {
    //     return $this->hasMany(OrderItem::class, 'product_id');
    // }
}
