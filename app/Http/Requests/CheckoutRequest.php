<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'checkout_token'   => 'required|uuid',

            'customer_email'   => 'nullable|email|required_if:user,null',

            // Shipping address
            'shipping_name'    => 'required|string|max:255',
            'shipping_phone'   => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city'    => 'required|string',
            'shipping_state'   => 'required|string',
            'shipping_lga'     => 'required|string',
        ];
    }
}
