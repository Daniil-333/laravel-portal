<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
    public function rules() {
        return [
            'name' => 'required|max:100',
            'email' => 'required|email|max:255',
        ];
    }


    public function messages() {
        return [
            'name.required' => 'Введите имя пользователя',
            'name.max' => 'Имя пользователя должно быть не длиннее 100 символов',
            'email.required' => 'Введите адрес электронной почты',
            'email.email' => 'Введите корректный адрес электронной почты',
            'email.max' => 'Адрес электронной почты не должен превышать в длину 255 символов',
        ];
    }
}
