<?php

namespace App\Services\Support;

use App\Models\User;

class AuthResponse
{
    public function __construct(
        public User $user,
        public string $token,
    ) {}
}
