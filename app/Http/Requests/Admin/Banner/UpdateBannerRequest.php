<?php

namespace App\Http\Requests\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
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


    public function getProcessedData(): array
    {
        $data = $this->validated();

        return [
            'title' => json_encode([
                'en' => $data['title_en'],
                'bn' => $data['title_bn']
            ]),
            'sub_title' => json_encode([
                'en' => $data['sub_title_en'] ?? '',
                'bn' => $data['sub_title_bn'] ?? ''
            ]),
            'description' => json_encode([
                'en' => $data['description_en'] ?? '',
                'bn' => $data['description_bn'] ?? ''
            ]),
            'position' => $data['position'],
            'status' => $data['status'],
            'button_text_1' => $data['button_text_1'],
            'button_url_1' => $data['button_url_1'],
            'button_text_2' => $data['button_text_2'],
            'button_url_2' => $data['button_url_2'],
            'type_id' => $data['type_id'],
        ];
    }
}
