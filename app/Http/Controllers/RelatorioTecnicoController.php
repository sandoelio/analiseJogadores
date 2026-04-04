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

    public function pendencias()
    {
        $usuario = Auth::user();
        $instituicao = $usuario->instituicao;
        $alunos = Aluno::with(['ultimaAnalise'])
            ->withCount('analises')
            ->where('instituicao_id', $usuario->instituicao_id)
            ->orderBy('nome')
            ->get();

        $pendencias = $this->montarPendencias($alunos);
        $totalPendencias = $pendencias->sum('total');

        return view('tecnico.relatorios.pendencias', compact(
            'instituicao',
            'pendencias',
            'totalPendencias'
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

    private function montarPendencias(Collection $alunos): Collection
    {
        $limiteAnalise = now()->subDays(30);

        $semTelefone = $alunos->filter(function ($aluno) {
            return blank($aluno->telefone);
        });

        $semAnalise = $alunos->filter(function ($aluno) {
            return (int) $aluno->analises_count === 0;
        });

        $analiseDesatualizada = $alunos->filter(function ($aluno) use ($limiteAnalise) {
            if (! $aluno->ultimaAnalise) {
                return false;
            }

            return $aluno->ultimaAnalise->created_at->lt($limiteAnalise);
        });

        $saudePendente = $alunos->filter(function ($aluno) {
            $analise = $aluno->ultimaAnalise;

            if (! $analise) {
                return false;
            }

            $problemaSemDescricao = (bool) $analise->problema_saude
                && blank($analise->problema_saude_descricao);

            $atestadoPendente = (bool) $analise->atestado_valido
                && ! $analise->data_atestado;

            return $problemaSemDescricao || $atestadoPendente;
        });

        return collect([
            $this->montarCardPendencia(
                'Sem telefone',
                'bi-telephone-x',
                $semTelefone,
                'Atletas sem contato preenchido no cadastro.',
                'Ultima analise',
                $semTelefone->map(function ($aluno) {
                    return [
                        'nome' => $aluno->nome,
                        'idade' => $aluno->idade,
                        'sexo' => $aluno->sexo,
                        'observacao' => 'Telefone nao informado',
                        'data_referencia' => $aluno->ultimaAnalise?->created_at,
                    ];
                })
            ),
            $this->montarCardPendencia(
                'Sem analise',
                'bi-clipboard-x',
                $semAnalise,
                'Atletas cadastrados que ainda nao receberam avaliacao.',
                'Ultima analise',
                $semAnalise->map(function ($aluno) {
                    return [
                        'nome' => $aluno->nome,
                        'idade' => $aluno->idade,
                        'sexo' => $aluno->sexo,
                        'observacao' => 'Sem historico de analise',
                        'data_referencia' => null,
                    ];
                })
            ),
            $this->montarCardPendencia(
                'Analise desatualizada',
                'bi-hourglass-split',
                $analiseDesatualizada,
                'Ultima analise registrada ha mais de 30 dias.',
                'Ultima analise',
                $analiseDesatualizada->map(function ($aluno) {
                    return [
                        'nome' => $aluno->nome,
                        'idade' => $aluno->idade,
                        'sexo' => $aluno->sexo,
                        'observacao' => 'Ultima analise em ' . optional($aluno->ultimaAnalise?->created_at)->format('d/m/Y'),
                        'data_referencia' => $aluno->ultimaAnalise?->created_at,
                    ];
                })
            ),
            $this->montarCardPendencia(
                'Saude pendente',
                'bi-heart-pulse',
                $saudePendente,
                'Problema de saude sem descricao ou atestado sem data preenchida.',
                'Data do atestado',
                $saudePendente->map(function ($aluno) {
                    $analise = $aluno->ultimaAnalise;
                    $motivos = [];

                    if ((bool) $analise->problema_saude && blank($analise->problema_saude_descricao)) {
                        $motivos[] = 'Problema de saude sem descricao';
                    }

                    if ((bool) $analise->atestado_valido && ! $analise->data_atestado) {
                        $motivos[] = 'Atestado sem data preenchida';
                    }

                    return [
                        'nome' => $aluno->nome,
                        'idade' => $aluno->idade,
                        'sexo' => $aluno->sexo,
                        'observacao' => implode(' | ', $motivos),
                        'data_referencia' => $analise?->data_atestado,
                    ];
                })
            ),
        ]);
    }

    private function montarCardPendencia(
        string $titulo,
        string $icone,
        Collection $alunos,
        string $descricao,
        string $colunaData,
        Collection $itens
    ): array {
        return [
            'titulo' => $titulo,
            'icone' => $icone,
            'total' => $alunos->count(),
            'descricao' => $descricao,
            'coluna_data' => $colunaData,
            'nomes' => $alunos->pluck('nome')->take(5)->values(),
            'itens' => $itens->values(),
        ];
    }
}
