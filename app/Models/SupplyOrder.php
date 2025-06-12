<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\SoftDeletes;

class SupplyOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'registered_by_user_id',
        'status',
        'quantity',
        'custom'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'custom' => 'array',
    ];

    /**
     * Possible status values for supply orders
     */
    public const STATUS_REQUESTED = 'requested';
    public const STATUS_COMPLETED = 'completed';

    /**
     * Get all possible statuses
     *
     * @return array
     */
    public static function getStatuses()
    {
        return [
            self::STATUS_REQUESTED => 'Requested',
            self::STATUS_COMPLETED => 'Completed',
        ];
    }

    /**
     * Relationship with the product being ordered
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Relationship with the user who registered the order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function registeredBy()
    {
        return $this->belongsTo(User::class, 'registered_by_user_id');
    }

    /**
     * Scope a query to only include requested supply orders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRequested($query)
    {
        return $query->where('status', self::STATUS_REQUESTED);
    }

    /**
     * Scope a query to only include completed supply orders.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    /**
     * Check if the supply order is requested
     *
     * @return bool
     */
    public function isRequested()
    {
        return $this->status === self::STATUS_REQUESTED;
    }

    /**
     * Check if the supply order is completed
     *
     * @return bool
     */
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}
