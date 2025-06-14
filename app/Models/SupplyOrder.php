<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'registered_by_user_id',
        'status',
        'quantity',
        'custom'
    ];

    protected $casts = [
        'custom' => 'array',
    ];

    public const STATUS_REQUESTED = 'requested';
    public const STATUS_COMPLETED = 'completed';


    public static function getStatuses()
    {
        return [
            self::STATUS_REQUESTED => 'Requested',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }

    public function scopeRequested($query)
    {
        return $query->where('status', self::STATUS_REQUESTED);
    }


    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function isRequested()
    {
        return $this->status === self::STATUS_REQUESTED;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}
