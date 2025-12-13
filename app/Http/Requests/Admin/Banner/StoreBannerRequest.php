<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
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
            'title_en' => 'required|string|max:255',
            'title_bn' => 'nullable|string|max:255',

            'sub_title_en' => 'nullable|string|max:255',
            'sub_title_bn' => 'nullable|string|max:255',

            'description_en' => 'nullable|string',
            'description_bn' => 'nullable|string',

            'position' => 'nullable|integer',
            'status' => 'nullable|in:active,inactive',
            'button_text_1' => 'nullable|string',
            'button_url_1' => 'nullable|string',
            'button_text_2' => 'nullable|string',
            'button_url_2' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'type_id' => 'required|integer|exists:types,id',
        ];
    }
}
