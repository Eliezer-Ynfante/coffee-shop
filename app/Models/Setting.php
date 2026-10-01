<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    /**
     * Obtiene el valor de una configuración desde la BD con fallback a config/cafe.php.
     */
    public static function get(string $key, $default = null)
    {
        try {
            $setting = Cache::remember("setting_{$key}", 3600, function () use ($key) {
                return self::where('key', $key)->first();
            });

            if ($setting && $setting->value !== null && $setting->value !== '') {
                if ($setting->type === 'json' || is_array(json_decode($setting->value, true))) {
                    return json_decode($setting->value, true);
                }
                if ($setting->type === 'boolean') {
                    return filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
                }
                return $setting->value;
            }
        } catch (\Throwable $e) {
            // Si la tabla no está disponible, continuar al fallback
        }

        // Fallback a config/cafe.php y luego al default
        return config("cafe.{$key}", $default);
    }

    /**
     * Guarda o actualiza un valor de configuración.
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'text')
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            $type = 'json';
        }

        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type'  => $type,
            ]
        );

        Cache::forget("setting_{$key}");

        return $setting;
    }
}
