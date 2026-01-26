<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutSession extends Model
{
    protected $fillable = [
        'public_token',
        'user_id',
        'cart_id',
        'status',
        'current_step',
        'expires_at',
        'customer_email',
        'shipping_address',
        'billing_address',
        'stripe_payment_intent_id',
        'subtotal',
        'tax',
        'shipping_fee',
        'total',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address'  => 'array',
        'expires_at'       => 'datetime',
        'status'           => 'string',
        'current_step'     => 'string',
        'subtotal'         => 'integer',
        'tax'              => 'integer',
        'shipping_fee'     => 'integer',
        'total'            => 'integer',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
