<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CheckoutSession extends Model
{
    protected $fillable = [
        'public_token',
        'user_id',
        'cart_id',
        'status',
        'current_step',
        'items_captured_at',
        'expires_at',
        'customer_email',
        'shipping_address',
        'billing_address',
        'subtotal',
        'tax',
        'shipping_fee',
        'total',
        'currency',
    ];

    protected $casts = [
        'shipping_address' => 'array',
        'billing_address'  => 'array',
        'expires_at'       => 'datetime',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function checkoutItems(): HasMany
    {
        return $this->hasMany(CheckoutItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    /**
     * Check if checkout items have been captured (locked in)
     */
    public function hasItemsCaptured(): bool
    {
        return ! is_null($this->items_captured_at) || $this->checkoutItems()->exists();
    }

    /**
     * Check if this session requires admin attention
     */
    public function requiresAttention(): bool
    {
        return $this->status === 'requires_attention';
    }
}
