<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CafeTable extends Model
{
    protected $fillable = [
        'code',
        'zone',
        'zone_name',
        'name',
        'capacity',
        'status',
        'coord_x',
        'coord_y',
        'icon',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'coord_x' => 'integer',
            'coord_y' => 'integer',
            'is_active' => 'boolean',
        ];
    }
}
