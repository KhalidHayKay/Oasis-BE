<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'checkout_session_id',
        'transaction_reference',
        'payment_gateway',
        'amount',
        'currency',
        'status',
        'failure_reason',
        'gateway_response',
        'paid_at',
    ];

    protected $casts = [
        'status'           => 'string',
        'paid_at'          => 'datetime',
        'gateway_response' => 'json',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checkoutSession()
    {
        return $this->belongsTo(CheckoutSession::class);
    }
}
