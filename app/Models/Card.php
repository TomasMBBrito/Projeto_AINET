<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Operation;

class Card extends Model
{
    use HasFactory;

    protected $table = 'cards';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'card_number',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'card_id');
    }
}
