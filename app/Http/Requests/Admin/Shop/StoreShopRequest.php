<?php

namespace App\Http\Requests\Admin\Shop;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'shop_owner_id' => ['nullable', 'integer', 'exists:shop_owners,id'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'zone_id' => ['required', 'integer', 'exists:zones,id'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lon' => ['nullable', 'numeric', 'between:-180,180'],
            'type' => ['required', 'string', 'in:retail,wholesale,distributor'],
            'is_commission_based' => ['boolean'],
            'status' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Shop name is required',
            'zone_id.required' => 'Zone selection is required',
            'zone_id.exists' => 'Selected zone does not exist',
            'type.required' => 'Shop type is required',
            'type.in' => 'Shop type must be retail, wholesale, or distributor',
            'commission_rate.min' => 'Commission rate cannot be negative',
            'commission_rate.max' => 'Commission rate cannot exceed 100%',
        ];
    }
}
