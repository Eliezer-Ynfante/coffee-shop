<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'attendant_id',
        'channel',
        'status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total',
        'payment_method',
        'payment_status',
        'customer_name',
        'customer_phone',
        'notes',
        'prepared_at',
        'completed_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
