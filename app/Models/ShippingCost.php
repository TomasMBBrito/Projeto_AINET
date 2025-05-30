<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingCost extends Model
{
    protected $table = 'settings_shipping_costs';

    protected $fillable = [
        'min_value_threshold',
        'max_value_threshold',
        'shipping_cost',
    ];

    protected $casts = [
        'min_value_threshold' => 'decimal:2',
        'max_value_threshold' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    // ======================
    // === MÉTODOS ÚTEIS ===
    // ======================

    /**
     * Devolve o custo de envio aplicável a um dado valor de encomenda.
     */
    public static function getShippingCostForOrderTotal(float $orderTotal): float
    {
        $setting = self::where('min_value_threshold', '<=', $orderTotal)
            ->where('max_value_threshold', '>', $orderTotal)
            ->first();

        return $setting ? $setting->shipping_cost : 0.00;
    }
}
