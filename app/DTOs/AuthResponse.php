<?php

namespace App\DTOs;

use App\Models\User;

class AuthResponse
{
    public function __construct(
        public User $user,
        public string $token,
    ) {}
}
