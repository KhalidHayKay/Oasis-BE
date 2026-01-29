<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'userId'                => $this->user_id,
            'paymentId'             => $this->payment_id,
            'orderNumber'           => $this->order_number,
            'customerEmail'         => $this->customer_email,
            'shippingAddress'       => $this->shipping_address,
            'billingAddress'        => $this->billing_address,
            'subtotal'              => (float) $this->subtotal,
            'tax'                   => (float) $this->tax,
            'shippingFee'           => (float) $this->shipping_fee,
            'total'                 => (float) $this->total,
            'currency'              => $this->currency,
            'stripePaymentIntentId' => $this->stripe_payment_intent_id,
            'status'                => $this->status,
            'createdAt'             => $this->created_at?->toIso8601String(),
            'updatedAt'             => $this->updated_at?->toIso8601String(),
            'items'                 => OrderItemResource::collection($this->whenLoaded('items')),
            'user'                  => new UserResource($this->whenLoaded('user')),
        ];
    }
}
