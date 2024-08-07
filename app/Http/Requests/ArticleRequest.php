<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'short_desc' => 'string|max:255|nullable'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Название не заполнено',
            'title.string' => 'Название должно быть только из букв',
            'title.max' => 'Название не должно быть более 255 символов',
            'short_desc.string' => 'Краткое описание должно быть только из букв',
            'short_desc.max' => 'Краткое описание не должно быть более 255 символов'
        ];
    }
}
