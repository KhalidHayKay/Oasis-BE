<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'productImage' => $this->product->featuredImage ? [
                'src' => $this->product->featuredImage->image_path,
                'alt' => $this->product->featuredImage->alt_text,
            ] : null,
            'productDesc'  => $this->product_description,
            'color'        => $this->product_selected_color,
            'unitPrice'    => (float) $this->price_at_checkout,
            'quantity'     => $this->quantity,
        ];
    }
}
