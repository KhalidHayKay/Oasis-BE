<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'checkout_session_id',
        'user_id',
        'order_number',
        'customer_email',
        'shipping_address',
        'billing_address',
        'subtotal',
        'tax',
        'shipping_fee',
        'total',
        'currency',
        'stripe_payment_intent_id',
        'status',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address'  => 'array',
        'subtotal'         => 'decimal:2',
        'tax'              => 'decimal:2',
        'shipping_fee'     => 'decimal:2',
        'total'            => 'decimal:2',
        'status'           => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
