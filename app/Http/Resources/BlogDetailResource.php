<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'slug'        => $this->slug,
            'description' => $this->description,
            'coverImage'  => $this->cover_image,
            'body'        => $this->body,

            'hashtags'    => $this->hashtags,      // ["#interior-design", "#minimalism"]
            'displayTags' => $this->display_tags,  // ["interior-design", "minimalism"]

            'createdAt'   => $this->created_at->toDateString(),
        ];
    }
}
