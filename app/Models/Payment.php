<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_reference',
        'payment_gateway',
        'amount',
        'currency',
        'status',
        'gateway_response',
        'paid_at',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'status'           => 'string',
        'paid_at'          => 'datetime',
        'gateway_response' => 'json',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
