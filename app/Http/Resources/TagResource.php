<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
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
            'name'         => $this->name,
            'slug'         => $this->slug,
            'category'     => $this->whenLoaded('category', fn () => CategoryResource::make($this->category)),
            'products'     => $this->whenLoaded('products', fn () => ProductResource::collection($this->products)),
            'productCount' => $this->when(isset($this->products_count), $this->products_count),
        ];
    }
}
