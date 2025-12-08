<?php

namespace App\Http\Requests\Order\CartItem;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'quantity' => 'required|integer|min:1',

            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product is required.',
            'product_id.exists' => 'The selected product does not exist.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity must be at least 1.',
            'price.required' => 'Price is required.',
            'price.min' => 'Price must be at least 0.',
        ];
    }
}
