<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'description',
        'image_path',
        'price',
        'cost_price',
        'stock',
        'min_stock_alert',
        'available_in_pos',
        'available_in_store',
        'is_active',
        'preparation_time',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price'              => 'decimal:2',
            'cost_price'         => 'decimal:2',
            'is_active'          => 'boolean',
            'is_featured'        => 'boolean',
            'available_in_pos'   => 'boolean',
            'available_in_store' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
