<?php

namespace App\Exceptions;

use RuntimeException;

class InvalidLoginCredentialException extends RuntimeException
{
    public function __construct(protected $message = 'Invalid credentials')
    {
        parent::__construct($message);
    }
}
