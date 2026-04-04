<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (bool) auth()->user()->is_admin;
    }

    public function rules(): array
    {
        $usuario = $this->route('usuario');
        $usuarioId = $usuario?->id;
        $instituicaoId = $usuario?->instituicao_id;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $usuarioId,
            'password' => 'nullable|string|min:6|confirmed',
            'athlete_email' => 'required|email|unique:instituicoes,athlete_email,' . $instituicaoId,
            'athlete_password' => 'nullable|string|min:8',
        ];
    }
}
