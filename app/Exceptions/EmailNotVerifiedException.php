<?php

namespace App\Exceptions;

use RuntimeException;

class EmailNotVerifiedException extends RuntimeException
{
    public function __construct(protected string $message = 'Email not verified')
    {
        parent::__construct($message);
    }
}
