<?php

namespace App\DTOs;

class GatewayInitResult
{
    public function __construct(
        public readonly string $clientSecret,
        public readonly string $reference,
    ) {}
}
