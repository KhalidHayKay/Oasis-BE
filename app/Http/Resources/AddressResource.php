<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'firstName' => $this['fname'],
            'lastName'  => $this['lname'],
            'phone'     => $this['phone'],
            'address'   => $this['address'],
            'country'   => $this['country'],
            'city'      => $this['city'],
        ];
    }
}
