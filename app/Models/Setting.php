<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public $timestamps = true;

    public static function getValue($key, $default = null)
    {
        $setting = self::where('membership_fee', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
