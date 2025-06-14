<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'card_id',
        'type',
        'value',
        'date',
        'debit_type',
        'credit_type',
        'payment_type',
        'payment_reference',
        'order_id',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'date' => 'date',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function isCredit()
    {
        return $this->type === 'credit';
    }

    public function isDebit()
    {
        return $this->type === 'debit';
    }

    public function isPayment()
    {
        return $this->credit_type === 'payment';
    }

    public function isMembershipFee()
    {
        return $this->debit_type === 'membership_fee';
    }

    public function isOrderCancellation()
    {
        return $this->credit_type === 'order_cancellation';
    }

    public function isOrderPayment()
    {
        return $this->debit_type === 'order';
    }
}
