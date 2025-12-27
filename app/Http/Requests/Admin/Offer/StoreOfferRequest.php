<?php

namespace App\Http\Requests\Admin\Offer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreOfferRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'name_bn' => ['nullable', 'string', 'max:255'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date'],
            'discount_type' => ['required', 'in:percentage,flat'],
            'flat_amount' => ['exclude_unless:discount_type,flat', 'required', 'numeric'],
            'percentage' => ['required_if:discount_type,percentage', 'numeric', 'between:0,100'],
            'type' => ['required', 'in:product,category'],
            'targets' => ['required', 'array'],
            'targets.*.id' => ['required_with:targets', 'integer'],
            'targets.*.type' => ['required_with:targets', 'in:product,category'],
        ];
    }

   

    public function messages(): array
    {
        return [
            'flat_amount.required' => 'Flat amount is required when discount type is flat.',
            'flat_amount.numeric' => 'Flat amount must be a number.',
            'percentage.required_if' => 'Percentage is required.',
            'percentage.numeric' => 'Percentage must be a number.',
            'percentage.between' => 'Percentage must be between 0 and 100.',
            'targets.*.id.required_with' => 'Each item must include an id when targets are provided.',
            'targets.*.type.required_with' => 'Each item must include a type when targets are provided.',
        ];
    }
}
