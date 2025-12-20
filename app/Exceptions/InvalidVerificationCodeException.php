<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvalidVerificationCodeException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): Response
    {
        //
    }
}
