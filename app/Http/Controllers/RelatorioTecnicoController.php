<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class RelatorioTecnicoController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $instituicao = $usuario->instituicao;

        $alunos = Aluno::query()
            ->where('instituicao_id', $usuario->instituicao_id)
            ->get(['idade', 'sexo']);

        $idadesMasculino = $this->obterIdadesPorSexo($alunos, 'Masculino');
        $idadesFeminino = $this->obterIdadesPorSexo($alunos, 'Feminino');

        $relatorioMasculino = $this->montarRelatorioPorSexo($alunos, $idadesMasculino, 'Masculino');
        $relatorioFeminino = $this->montarRelatorioPorSexo($alunos, $idadesFeminino, 'Feminino');

        return view('tecnico.relatorios.index', compact(
            'instituicao',
            'idadesMasculino',
            'idadesFeminino',
            'relatorioMasculino',
            'relatorioFeminino'
        ));
    }

    private function obterIdadesPorSexo(Collection $alunos, string $sexo): Collection
    {
        return $alunos
            ->where('sexo', $sexo)
            ->pluck('idade')
            ->filter(fn($idade) => $idade !== null)
            ->map(fn($idade) => (int) $idade)
            ->unique()
            ->sort()
            ->values();
    }

    private function montarRelatorioPorSexo(Collection $alunos, Collection $idades, string $sexo): array
    {
        $alunosSexo = $alunos->where('sexo', $sexo);
        $idadesRelatorio = [];

        foreach ($idades as $idade) {
            $idadesRelatorio[$idade] = $alunosSexo->where('idade', $idade)->count();
        }

        return [
            'idades' => $idadesRelatorio,
            'total' => array_sum($idadesRelatorio),
        ];
    }
}
