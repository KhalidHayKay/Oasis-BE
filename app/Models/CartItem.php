<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'product_name',
        'product_image_id',
        'product_description',
        'color',
        'unit_price',
        'quantity',
        'subtotal',
    ];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function image()
    {
        return $this->belongsTo(ProductImage::class, 'product_image_id', 'id');
    }
}
