<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Instituicao;
use Illuminate\Http\Request;
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
            'instituicoes',
            'relatorioMasculino',
            'relatorioFeminino',
            'idadesMasculino',
            'idadesFeminino'
        ));
    }

    public function pendencias(Request $request)
    {
        $instituicoes = Instituicao::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);

        $instituicaoSelecionada = null;
        $pendencias = collect();
        $totalPendencias = 0;

        $instituicaoId = (int) $request->integer('instituicao_id');

        if ($instituicaoId > 0) {
            $instituicaoSelecionada = $instituicoes->firstWhere('id', $instituicaoId);

            abort_unless($instituicaoSelecionada, 404);

            $alunos = Aluno::with(['ultimaAnalise'])
                ->withCount('analises')
                ->where('instituicao_id', $instituicaoId)
                ->orderBy('nome')
                ->get();

            $pendencias = $this->montarPendencias($alunos);
            $totalPendencias = $pendencias->sum('total');
        }

        return view('admin.relatorios.pendencias', compact(
            'instituicoes',
            'instituicaoSelecionada',
            'pendencias',
            'totalPendencias'
        ));
    }

    public function comparativo(Request $request)
    {
        $instituicoes = Instituicao::query()
            ->orderBy('nome')
            ->get(['id', 'nome']);

        $instituicaoA = null;
        $instituicaoB = null;
        $comparativo = null;

        if ($request->filled('instituicao_a_id') || $request->filled('instituicao_b_id')) {
            $dados = $request->validate([
                'instituicao_a_id' => 'required|exists:instituicoes,id',
                'instituicao_b_id' => 'required|exists:instituicoes,id|different:instituicao_a_id',
            ], [
                'instituicao_b_id.different' => 'Selecione duas instituicoes diferentes.',
            ]);

            $instituicaoA = $instituicoes->firstWhere('id', (int) $dados['instituicao_a_id']);
            $instituicaoB = $instituicoes->firstWhere('id', (int) $dados['instituicao_b_id']);

            $alunos = Aluno::with(['ultimaAnalise'])
                ->withCount('analises')
                ->whereIn('instituicao_id', [$instituicaoA->id, $instituicaoB->id])
                ->orderBy('nome')
                ->get()
                ->groupBy('instituicao_id');

            $comparativo = [
                'instituicao_a' => $this->montarDadosComparativoInstituicao(
                    $instituicaoA,
                    $alunos->get($instituicaoA->id, collect())
                ),
                'instituicao_b' => $this->montarDadosComparativoInstituicao(
                    $instituicaoB,
                    $alunos->get($instituicaoB->id, collect())
                ),
                'linhas_volume' => [
                    ['titulo' => 'Total de atletas', 'chave' => 'total_atletas'],
                    ['titulo' => 'Masculino', 'chave' => 'masculino'],
                    ['titulo' => 'Feminino', 'chave' => 'feminino'],
                    ['titulo' => 'Media de idade', 'chave' => 'media_idade'],
                    ['titulo' => 'Sem analise', 'chave' => 'sem_analise'],
                    ['titulo' => 'Sem telefone', 'chave' => 'sem_telefone'],
                    ['titulo' => 'Analise desatualizada', 'chave' => 'analise_desatualizada'],
                    ['titulo' => 'Saude pendente', 'chave' => 'saude_pendente'],
                ],
                'linhas_desempenho' => [
                    ['titulo' => 'Atletas avaliados', 'chave' => 'atletas_avaliados'],
                    ['titulo' => 'Media tecnica geral', 'chave' => 'media_tecnica'],
                    ['titulo' => 'Arremesso medio', 'chave' => 'arremesso'],
                    ['titulo' => 'Passe medio', 'chave' => 'passe'],
                    ['titulo' => 'Marcacao media', 'chave' => 'marcacao'],
                    ['titulo' => 'Agilidade media', 'chave' => 'agilidade'],
                    ['titulo' => 'Potencia MMII media', 'chave' => 'potencia_mmii'],
                    ['titulo' => 'IMC medio', 'chave' => 'imc'],
                ],
            ];
        }

        return view('admin.relatorios.comparativo', compact(
            'instituicoes',
            'instituicaoA',
            'instituicaoB',
            'comparativo'
        ));
    }

    public function alertas()
    {
        $instituicoes = Instituicao::query()
            ->with(['alunos.ultimaAnalise'])
            ->orderBy('nome')
            ->get(['id', 'nome']);

        $alertas = $this->montarAlertasAdministrativos($instituicoes);
        $resumoAlertas = [
            'baixa_presenca_feminina' => $alertas['baixa_presenca_feminina']->count(),
            'concentracao_etaria' => $alertas['concentracao_etaria']->count(),
            'baixa_atualizacao' => $alertas['baixa_atualizacao']->count(),
        ];

        return view('admin.relatorios.alertas', compact('alertas', 'resumoAlertas'));
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

    private function montarAlertasAdministrativos(Collection $instituicoes): array
    {
        $limiteAnalise = now()->subDays(30);
        $baixaPresencaFeminina = collect();
        $concentracaoEtaria = collect();
        $baixaAtualizacao = collect();

        foreach ($instituicoes as $instituicao) {
            $alunos = $instituicao->alunos;
            $total = $alunos->count();

            if ($total === 0) {
                continue;
            }

            $feminino = $alunos->where('sexo', 'Feminino')->count();
            $femininoPct = $total > 0 ? ($feminino / $total) * 100 : 0;

            if ($total >= 3 && $femininoPct < 30) {
                $baixaPresencaFeminina->push([
                    'instituicao' => $instituicao->nome,
                    'total_atletas' => $total,
                    'indicador' => $feminino . ' atleta(s)',
                    'percentual' => $this->formatarNumero($femininoPct, 1) . '%',
                    'observacao' => 'Participacao feminina abaixo de 30% do total da instituicao.',
                ]);
            }

            $idades = $alunos
                ->pluck('idade')
                ->filter(fn($idade) => $idade !== null)
                ->map(fn($idade) => (int) $idade);

            if ($total >= 3 && $idades->isNotEmpty()) {
                $idadePrincipal = $idades->countBy()->sortDesc()->keys()->first();
                $quantidadeIdade = $idades->countBy()->sortDesc()->first();
                $idadePct = $quantidadeIdade ? ($quantidadeIdade / $total) * 100 : 0;

                if ($idadePct >= 45) {
                    $concentracaoEtaria->push([
                        'instituicao' => $instituicao->nome,
                        'total_atletas' => $total,
                        'indicador' => $idadePrincipal . ' anos',
                        'percentual' => $this->formatarNumero($idadePct, 1) . '%',
                        'observacao' => 'Faixa etaria dominante com alta concentracao de atletas nesta idade.',
                    ]);
                }
            }

            $desatualizados = $alunos->filter(function ($aluno) use ($limiteAnalise) {
                return ! $aluno->ultimaAnalise || $aluno->ultimaAnalise->created_at->lt($limiteAnalise);
            })->count();

            $desatualizadosPct = $total > 0 ? ($desatualizados / $total) * 100 : 0;

            if ($total >= 3 && $desatualizadosPct >= 35) {
                $baixaAtualizacao->push([
                    'instituicao' => $instituicao->nome,
                    'total_atletas' => $total,
                    'indicador' => $desatualizados . ' atleta(s)',
                    'percentual' => $this->formatarNumero($desatualizadosPct, 1) . '%',
                    'observacao' => 'Volume alto de atletas sem analise recente nos ultimos 30 dias.',
                ]);
            }
        }

        return [
            'baixa_presenca_feminina' => $baixaPresencaFeminina->values(),
            'concentracao_etaria' => $concentracaoEtaria->values(),
            'baixa_atualizacao' => $baixaAtualizacao->values(),
        ];
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
            'itens' => $itens->values(),
        ];
    }

    private function montarDadosComparativoInstituicao(Instituicao $instituicao, Collection $alunos): array
    {
        $alunosAvaliados = $alunos->filter(function ($aluno) {
            return $aluno->ultimaAnalise !== null;
        });

        $camposTecnicos = ['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'];

        $volume = [
            'total_atletas' => $alunos->count(),
            'masculino' => $alunos->where('sexo', 'Masculino')->count(),
            'feminino' => $alunos->where('sexo', 'Feminino')->count(),
            'media_idade' => $this->formatarNumero($alunos->pluck('idade')->filter()->avg(), 1),
            'sem_analise' => $alunos->where('analises_count', 0)->count(),
            'sem_telefone' => $alunos->filter(fn($aluno) => blank($aluno->telefone))->count(),
            'analise_desatualizada' => $alunos->filter(function ($aluno) {
                return $aluno->ultimaAnalise && $aluno->ultimaAnalise->created_at->lt(now()->subDays(30));
            })->count(),
            'saude_pendente' => $alunos->filter(function ($aluno) {
                if (! $aluno->ultimaAnalise) {
                    return false;
                }

                return ((bool) $aluno->ultimaAnalise->problema_saude && blank($aluno->ultimaAnalise->problema_saude_descricao))
                    || ((bool) $aluno->ultimaAnalise->atestado_valido && ! $aluno->ultimaAnalise->data_atestado);
            })->count(),
        ];

        $desempenho = [
            'atletas_avaliados' => $alunosAvaliados->count(),
            'media_tecnica' => $this->formatarNumero($this->obterMediaTecnica($alunosAvaliados, $camposTecnicos), 1),
            'arremesso' => $this->formatarMediaAnalise($alunosAvaliados, 'arremesso'),
            'passe' => $this->formatarMediaAnalise($alunosAvaliados, 'passe'),
            'marcacao' => $this->formatarMediaAnalise($alunosAvaliados, 'marcacao'),
            'agilidade' => $this->formatarMediaAnalise($alunosAvaliados, 'agilidade'),
            'potencia_mmii' => $this->formatarMediaAnalise($alunosAvaliados, 'potencia_mmii'),
            'imc' => $this->formatarMediaAnalise($alunosAvaliados, 'imc'),
        ];

        return [
            'id' => $instituicao->id,
            'nome' => $instituicao->nome,
            'volume' => $volume,
            'desempenho' => $desempenho,
        ];
    }

    private function obterMediaTecnica(Collection $alunos, array $campos): ?float
    {
        if ($alunos->isEmpty()) {
            return null;
        }

        $medias = collect($campos)
            ->map(function ($campo) use ($alunos) {
                return $alunos
                    ->map(fn($aluno) => $aluno->ultimaAnalise?->{$campo})
                    ->filter(fn($valor) => $valor !== null)
                    ->avg();
            })
            ->filter(fn($valor) => $valor !== null);

        return $medias->isEmpty() ? null : $medias->avg();
    }

    private function formatarMediaAnalise(Collection $alunos, string $campo): string
    {
        $media = $alunos
            ->map(fn($aluno) => $aluno->ultimaAnalise?->{$campo})
            ->filter(fn($valor) => $valor !== null)
            ->avg();

        return $this->formatarNumero($media, 1);
    }

    private function formatarNumero($valor, int $casas = 1): string
    {
        if ($valor === null) {
            return '--';
        }

        return number_format((float) $valor, $casas, ',', '.');
    }
}
