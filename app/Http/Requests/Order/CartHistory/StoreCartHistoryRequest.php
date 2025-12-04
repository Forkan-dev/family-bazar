<?php

namespace App\Http\Requests\Order\CartHistory;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCartHistoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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

            'price' => 'required|numeric|min:0',

            'options' => 'nullable|array',
            'options.*' => 'string|max:255',

            'action' => [
                'required',
                'string',
                Rule::in(['added', 'updated', 'removed', 'ordered']),
            ],

            'order_id' => [
                'nullable',
                'integer',
                'exists:orders,id',
            ],

            'action_date' => 'nullable|date',
            'expires_at' => 'nullable|date|after:action_date',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Product is required.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity must be at least 1.',
            'price.required' => 'Price is required.',
            'action.in' => 'Action must be one of: added, updated, removed, ordered.',
            'order_id.exists' => 'The selected order does not exist.',
            'expires_at.after' => 'Expires date must be after the action date.',
        ];
    }

}
