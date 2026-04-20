<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterialTecnicoStoreRequest;
use App\Models\MaterialTecnico;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MaterialTecnicoController extends Controller
{
    public function store(MaterialTecnicoStoreRequest $request): RedirectResponse
    {
        $arquivo = $request->file('arquivo_pdf');
        $caminho = $arquivo->store('materiais-tecnicos', 'local');

        MaterialTecnico::create([
            'titulo' => $request->validated('titulo'),
            'descricao' => $request->validated('descricao'),
            'arquivo_path' => $caminho,
            'arquivo_nome_original' => $arquivo->getClientOriginalName(),
            'arquivo_mime' => $arquivo->getClientMimeType() ?: 'application/pdf',
            'arquivo_tamanho' => $arquivo->getSize(),
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('admin.relatorios')
            ->with('material_success', 'PDF anexado com sucesso.');
    }

    public function download(MaterialTecnico $materialTecnico): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($materialTecnico->arquivo_path), 404);

        return Storage::disk('local')->download(
            $materialTecnico->arquivo_path,
            $materialTecnico->arquivo_nome_original,
            ['Content-Type' => $materialTecnico->arquivo_mime ?: 'application/pdf']
        );
    }

    public function destroy(MaterialTecnico $materialTecnico): RedirectResponse
    {
        abort_unless(Auth::user()?->is_admin, 403);

        if (Storage::disk('local')->exists($materialTecnico->arquivo_path)) {
            Storage::disk('local')->delete($materialTecnico->arquivo_path);
        }

        $materialTecnico->delete();

        return redirect()
            ->route('admin.relatorios')
            ->with('material_success', 'PDF removido com sucesso.');
    }
}
