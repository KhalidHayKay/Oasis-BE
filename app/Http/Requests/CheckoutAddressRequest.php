<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *s
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'checkout_token'   => 'required|string|exists:checkout_sessions,public_token',

            'shipping_fname'   => 'required|string|max:255',
            'shipping_lname'   => 'required|string|max:255',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_country' => 'required|string',
            'shipping_city'    => 'required|string',
        ];
    }
}
