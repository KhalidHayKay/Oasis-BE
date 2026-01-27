<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheckoutSessionResource extends JsonResource
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
            'publicToken'           => $this->public_token,
            'customerEmail'         => $this->customer_email,
            'userId'                => $this->user_id,
            'cartId'                => $this->cart_id,
            'shippingAddress'       => new AddressResource($this->shipping_address),
            'billingAddress'        => new AddressResource($this->billing_address),
            'stripePaymentIntentId' => $this->stripe_payment_intent_id,
            'subtotal'              => $this->subtotal,
            'tax'                   => $this->tax,
            'shippingFee'           => $this->shipping_fee,
            'total'                 => $this->total,
            'status'                => $this->status,
            'currentStep'           => $this->current_step,
            'expiresAt'             => $this->expires_at,
            'createdAt'             => $this->created_at,
            'updatedAt'             => $this->updated_at,
            'cart'                  => [
                'id'         => $this->cart->id,
                'totalPrice' => $this->cart->total_price,
                'items'      => CartItemResource::collection($this->cart->items),
            ],
        ];
    }
}
