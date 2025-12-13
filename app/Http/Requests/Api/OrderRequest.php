<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'cart_id' => 'required|exists:carts,id',
            'customer_address_id' => 'required|exists:customer_addresses,id',
            'payment_method' => 'required|in:cod,card,wallet,stripe',
            'coupon_code' => 'nullable|string|exists:coupons,code',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cart_id.required' => 'Cart is required.',
            'cart_id.exists' => 'Invalid cart selected.',
            'customer_address_id.required' => 'Delivery address is required.',
            'customer_address_id.exists' => 'Invalid address selected.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Invalid payment method selected.',
            'coupon_code.exists' => 'Invalid coupon code.',
        ];
    }
}
