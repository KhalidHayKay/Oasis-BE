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
        'customer_email',
        'order_number',
        'user_id',
        'shipping_address',
        'billing_address',
        'status',
        'subtotal',
        'tax',
        'currency',
        'shipping_fee',
        'total',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address'  => 'array',
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
