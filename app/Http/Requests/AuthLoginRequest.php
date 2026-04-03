<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O e-mail e obrigatorio.',
            'email.email' => 'Formato de e-mail invalido.',
            'password.required' => 'A senha e obrigatoria.',
        ];
    }
}
