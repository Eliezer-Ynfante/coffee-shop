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
        'cafe_table_id',
        'zona',
        'zone_code',
        'ocasion',
        'comentarios',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'personas' => 'integer',
        ];
    }

    public function table()
    {
        return $this->belongsTo(CafeTable::class, 'cafe_table_id');
    }
}
