<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (bool) auth()->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'instituicao_nome' => 'required|string|max:255',
            'athlete_email' => 'required|email|unique:instituicoes,athlete_email',
            'athlete_password' => 'required|string|min:8',
        ];
    }
}
