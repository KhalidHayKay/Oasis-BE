<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckoutItem extends Model
{
    protected $fillable = [
        'checkout_session_id',
        'product_id',
        'product_name',
        'product_selected_color',
        'product_description',
        'price_at_checkout',
        'quantity',
    ];

    protected $casts = [
        'price_at_checkout' => 'decimal:2',
        'quantity'          => 'integer',
    ];

    public function checkoutSession(): BelongsTo
    {
        return $this->belongsTo(CheckoutSession::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
