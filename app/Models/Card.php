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
        'id',           // Changed from user_id to id to match the database schema
        'card_number',
        'balance',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id'); // Specify id as the foreign key
    }

    public function operations()
    {
        return $this->hasMany(Operation::class, 'card_id');
    }
}
