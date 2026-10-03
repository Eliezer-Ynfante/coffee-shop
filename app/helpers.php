<?php

use App\Models\Setting;

if (! function_exists('setting')) {
    /**
     * Obtiene el valor de una configuración de la BD con fallback a config/cafe.php
     *
     * @param  mixed  $default
     * @return mixed
     */
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}
