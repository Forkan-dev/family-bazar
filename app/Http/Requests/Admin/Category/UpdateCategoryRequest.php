<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'title_bn' => ['sometimes', 'nullable', 'string', 'max:255'],
            'slug' => ['sometimes', 'required', 'string', 'unique:categories,slug,'.$this->category->id],
            'description_en' => ['nullable', 'string'],
            'description_bn' => ['nullable', 'string'],
            'icon' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'parent_id' => ['nullable', 'exists:categories,id'],
        ];
    }
}
