<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'blocked',
        'gender',
        'photo',
        'nif',
        'email_verified_at',
        'default_delivery_address',
        'default_payment_type',
        'default_payment_reference',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts():array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Tipos válidos de utilizador.
     */
    public const TYPES = ['pending_member', 'member', 'board', 'employee'];

    // Métodos auxiliares de tipo

    public function isMember(): bool
    {
        return $this->type === 'member';
    }

    public function isBoard(): bool
    {
        return $this->type === 'board';
    }

    public function isEmployee(): bool
    {
        return $this->type === 'employee';
    }

    public function isPendingMember(): bool
    {
        return $this->type === 'pending_member';
    }

    /**
     * Relação com o cartão virtual.
     */
    public function cards()
    {
        return $this->hasMany(Card::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'member_id');
    }

    /**
     * Histórico de operações (através do cartão).
     */
    public function operations()
    {
        return $this->hasManyThrough(Operation::class, Card::class, 'id', 'card_id');
    }
}
