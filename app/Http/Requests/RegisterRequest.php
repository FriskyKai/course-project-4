<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends ApiRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|min:6|max:64',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|max:255',
        ];
    }

    // Кастомные сообщения об ошибках валидации
    public function messages(): array {
        return [
            'username.required' => 'Поле Имя пользователя обязательно для заполнения',
            'email.required' => 'Поле Электронная почта обязательно для заполнения',
            'password.required' => 'Поле Пароль обязательно для заполнения',

            'username.min' => 'Поле Имя пользователя должно содержать минимум 6 символов',
            'username.max' => 'Поле Имя пользователя должно содержать максимум 64 символа',
            'password.min' => 'Поле Пароль должно содержать минимум 6 символа',
            'password.max' => 'Поле Пароль должно содержать максимум 255 символа',
            'email.max' => 'Поле Электронная почта должно содержать максимум 255 символа',

            'email.email' => 'Электронная почта должна быть в правильном формате электронного адреса',
            'email.unique' => 'Данная Электронная почта уже используется другим пользователем',
        ];
    }
}
