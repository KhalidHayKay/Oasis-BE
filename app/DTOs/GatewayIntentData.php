<?php

namespace App\DTOs;

class GatewayIntentData
{
    public function __construct(
        /**
         * The client secret used to authenticate payment intent operations on the client-side.
         * Required to initialize payment methods and confirm payments.
         */
        public readonly string $clientSecret,

        /**
         * The unique payment intent identifier/reference from the payment gateway (e.g., Stripe Payment Intent ID).
         * Used to track and retrieve payment status.
         */
        public readonly string $reference,

        /**
         * Additional metadata from the payment gateway response.
         * Contains the full payment intent object or other gateway-specific data for reference.
         */
        public readonly array $additionalData = [],

        /**
         * The current status of the payment intent (e.g., 'succeeded', 'processing', 'requires_action').
         * Nullable as it may not be available during initial payment setup.
         */
        public readonly ?string $status = null,
    ) {}
}
