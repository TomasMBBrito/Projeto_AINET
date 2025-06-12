<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    protected $fillable = [
        'member_id',
        'status',
        'date',
        'total_items',
        'shipping_costs',
        'total',
        'nif',
        'delivery_address',
        'pdf_receipt',
        'cancel_reason'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'items_orders')
        ->withPivot(['quantity', 'unit_price', 'discount', 'subtotal'])
        ->as('order_item');;
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }



    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }
}
