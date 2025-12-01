<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
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
            'name'          => $this->name,
            'description'   => $this->description,
            'price'         => json_decode($this->price),
            'popularity'    => $this->popularity_score,
            'colors'        => json_decode($this->colors),
            'rating'        => $this->rating,
            'featuredImage' => $this->whenLoaded('featuredImage', fn () => [
                'src' => $this->featuredImage->image_path,
                'alt' => $this->featuredImage->alt_text,
            ]),
            'images'        => $this->whenLoaded('images', fn () => $this->images->map(fn ($image) => [
                'src' => $image->image_path,
                'alt' => $image->alt_text,
            ])),
            'category'      => $this->whenLoaded('category', fn () => $this->category->name),
            'tags'          => $this->whenLoaded('tags', fn () => $this->tags->pluck('name')),
            'createdAt'     => $this->created_at,
        ];
    }
}
