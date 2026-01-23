<?php

namespace App\Exceptions;

use Exception;

class CartValidationException extends Exception
{
    public function __construct(
        public readonly string $reason,
        public readonly array $issues = [],
    ) {
        parent::__construct("Cart validation failed: {$reason}");
    }

    public function render()
    {
        return response()->json([
            'success' => false,
            'reason'  => $this->reason,
            'issues'  => $this->issues,
        ], 422);
    }
}
