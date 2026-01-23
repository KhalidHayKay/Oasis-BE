<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'checkout_token'      => 'required|string|exists:checkout_sessions,public_token',

            'billing_name'        => 'required_if:is_same_as_shipping,false|string|max:255',
            'billing_phone'       => 'required_if:is_same_as_shipping,false|string|max:20',
            'billing_address'     => 'required_if:is_same_as_shipping,false|string',
            'billing_city'        => 'required_if:is_same_as_shipping,false|string',
            'billing_state'       => 'required_if:is_same_as_shipping,false|string',
            'billing_lga'         => 'required_if:is_same_as_shipping,false|string',

            'is_same_as_shipping' => 'required|boolean',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_same_as_shipping' => filter_var($this->is_same_as_shipping, FILTER_VALIDATE_BOOLEAN),
        ]);
    }

}
