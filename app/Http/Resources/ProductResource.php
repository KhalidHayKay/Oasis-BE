<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'price'          => $this->price,
            'featured_image' => $this->whenLoaded('featuredImage', fn () => $this->featuredImage->image_path),
            'images'         => $this->whenLoaded('images', fn () => $this->images->map(fn ($image) => $image->image_path)),
            'categories'     => $this->whenLoaded('categories', fn () => $this->categories->pluck('name')),
        ];
    }
}
