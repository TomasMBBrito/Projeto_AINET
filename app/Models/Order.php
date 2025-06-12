<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    //use SoftDeletes;

    protected $fillable = [
        'member_id',
        'status',
        'date',
        'total_items',
        'shipping_cost',
        'total',
        'nif',
        'delivery_address',
        'pdf_receipt',
        'cancel_reason',
    ];

    protected $casts = [
        'total_items' => 'decimal:2',
        'shipping_costs' => 'decimal:2',
        'total' => 'decimal:2',
        'date' => 'date',
    ];

    // ======================
    // === RELAÇÕES ========
    // ======================

    public function member()
    {
        return $this->belongsTo(User::class, 'member_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function operations()
    {
        return $this->hasOne(Operation::class, 'order_id');
    }

    // ======================
    // === MÉTODOS ÚTEIS ===
    // ======================

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCanceled()
    {
        return $this->status === 'canceled';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // public function hasReceipt()
    // {
    //     return !is_null($this->pdf_receipt);
    // }
}
