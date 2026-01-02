<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckoutSession extends Model
{
    protected $fillable = [
        'public_token',
        'customer_email',
        'user_id',
        'cart_id',
        'shipping_address',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'expires_at'       => 'datetime',
    ];
}
