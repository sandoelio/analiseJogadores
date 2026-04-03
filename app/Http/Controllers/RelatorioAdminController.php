<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Instituicao;
use Illuminate\Support\Collection;

class RelatorioAdminController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);

        $alunos = Aluno::query()
            ->get(['instituicao_id', 'idade', 'sexo']);

        $idadesMasculino = $this->obterIdadesPorSexo($alunos, 'Masculino');
        $idadesFeminino = $this->obterIdadesPorSexo($alunos, 'Feminino');

        $relatorioMasculino = $this->montarRelatorioPorSexo($instituicoes, $alunos, $idadesMasculino, 'Masculino');
        $relatorioFeminino = $this->montarRelatorioPorSexo($instituicoes, $alunos, $idadesFeminino, 'Feminino');

        return view('admin.relatorios.index', compact(
            'relatorioMasculino',
            'relatorioFeminino',
            'idadesMasculino',
            'idadesFeminino'
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

    private function montarRelatorioPorSexo(
        Collection $instituicoes,
        Collection $alunos,
        Collection $idades,
        string $sexo
    ): array {
        $alunosSexo = $alunos->where('sexo', $sexo);
        $linhas = [];
        $somatoriaIdades = [];

        foreach ($idades as $idade) {
            $somatoriaIdades[$idade] = 0;
        }

        foreach ($instituicoes as $instituicao) {
            $idadesProjeto = [];

            foreach ($idades as $idade) {
                $quantidade = $alunosSexo
                    ->where('instituicao_id', $instituicao->id)
                    ->where('idade', $idade)
                    ->count();

                $idadesProjeto[$idade] = $quantidade;
                $somatoriaIdades[$idade] += $quantidade;
            }

            $linhas[] = [
                'projeto' => $instituicao->nome,
                'idades' => $idadesProjeto,
                'total' => array_sum($idadesProjeto),
            ];
        }

        return [
            'linhas' => $linhas,
            'somatoria_idades' => $somatoriaIdades,
            'total_geral' => array_sum($somatoriaIdades),
        ];
    }
}
