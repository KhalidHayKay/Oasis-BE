<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'productId'    => $this->product_id,
            'productName'  => $this->product_name,
            'productImage' => $this->image ? [
                'src' => $this->image->image_path,
                'alt' => $this->image->alt_text,
            ] : null,
            'productDesc'  => $this->product_description,
            'color'        => $this->color,
            'unitPrice'    => $this->unit_price,
            'quantity'     => $this->quantity,
            'subtotal'     => $this->subtotal,
        ];
    }
}
