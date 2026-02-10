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
            'id'              => $this->id,
            'userId'          => $this->user_id,
            'orderNumber'     => $this->order_number,
            'customerEmail'   => $this->customer_email,
            'shippingAddress' => AddressResource::make($this->shipping_address),
            'billingAddress'  => AddressResource::make($this->billing_address),
            'subtotal'        => (float) $this->subtotal,
            'tax'             => (float) $this->tax,
            'shippingFee'     => (float) $this->shipping_fee,
            'total'           => (float) $this->total,
            'currency'        => $this->currency,
            'status'          => $this->status,
            'createdAt'       => $this->created_at?->toIso8601String(),
            'paymentRef'      => $this->whenLoaded(
                'payment',
                fn () => $this->payment->transaction_reference
            ),
            'items'           => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
