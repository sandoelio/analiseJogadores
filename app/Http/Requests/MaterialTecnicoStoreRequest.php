<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialTecnicoStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (bool) auth()->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'arquivo_pdf' => 'required|file|mimetypes:application/pdf|mimes:pdf|max:10240',
        ];
    }
}
