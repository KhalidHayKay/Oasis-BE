<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPreviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'orderNumber'   => $this->order_number,
            'total'         => (float) $this->total,
            'currency'      => $this->currency,
            'status'        => $this->status,
            'createdAt'     => $this->created_at?->toIso8601String(),
            'itemsLength'   => $this->items->count(),
            'productImages' => $this->items->map(fn ($item) => [
                'src' => $item->product->featuredImage?->image_path,
                'alt' => $item->product->featuredImage?->alt_text,
            ])->values(),
        ];
    }
}
