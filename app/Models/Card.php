<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Operation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Card extends Model
{
    USE HasFactory;
    protected $table = 'cards'; // ou 'cartoes' conforme a migração

    protected $fillable = [
        'id', // user_id
        'card_number',
        'balance',
    ];

    public $timestamps = false; // Se não tiver `created_at` e `updated_at`

    public function user()
    {
        return $this->belongsTo(User::class, 'id'); //user_id na base de dados, ter atenção
    }

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }
}
