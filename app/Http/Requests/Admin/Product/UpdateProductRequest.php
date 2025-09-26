<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'title_en' => ['sometimes', 'required', 'string', 'max:255'],
            'title_bn' => ['nullable', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'unique:products,slug,' . $this->product->id],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'required', 'numeric'],
            'stock' => ['sometimes', 'required', 'integer'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'tags' => ['nullable', 'array'],
        ];
    }
}
