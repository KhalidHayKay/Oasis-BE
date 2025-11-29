<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspirationResource extends JsonResource
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
            'title'        => $this->title,
            'imageUrl'     => $this->image_url,
            'category'     => $this->category,
            'displayOrder' => $this->display_order,
            'height'       => $this->height,
            'isActive'     => $this->is_active,
            'createdAt'    => $this->created_at,
        ];
    }
}
