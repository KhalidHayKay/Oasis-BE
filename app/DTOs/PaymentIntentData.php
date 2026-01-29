<?php

namespace App\DTOs;

/**
 * Data Transfer Object for payment intent information from a payment gateway.
 *
 * @property-read string $clientSecret The client secret used to authenticate payment intent operations on the client-side
 * @property-read string $reference The unique payment intent identifier from the payment gateway (e.g., Stripe Payment Intent ID)
 * @property-read array $additionalData Additional metadata from the payment gateway response
 * @property-read string|null $status The current status of the payment intent (e.g., 'succeeded', 'processing', 'requires_action')
 */
class PaymentIntentData
{
    public function __construct(
        public readonly string $clientSecret,
        public readonly string $reference,
        public readonly array $additionalData = [],
        public readonly ?string $status = null,
    ) {}
}
