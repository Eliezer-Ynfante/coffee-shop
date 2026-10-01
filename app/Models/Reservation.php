<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'fecha',
        'hora',
        'personas',
        'mesa_id',
        'zona',
        'ocasion',
        'comentarios',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'fecha'    => 'date',
            'personas' => 'integer',
        ];
    }
}
