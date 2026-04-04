<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Instituicao;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AlunoResumoController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::orderBy('nome')->get(['id', 'nome']);

        return view('resumo-atleta.index', compact('instituicoes'));
    }

    public function mostrar(string $matricula): JsonResponse
    {
        $aluno = $this->obterAlunoAutorizadoPorMatricula($matricula, true);
        $aluno->load(['ultimaAnalise', 'planosAcao' => function ($query) {
            $query->orderByRaw("CASE WHEN status = 'concluido' THEN 1 ELSE 0 END")
                ->orderByRaw('CASE WHEN prazo IS NULL THEN 1 ELSE 0 END')
                ->orderBy('prazo')
                ->orderByDesc('created_at');
        }]);

        $analises = $aluno->analises()
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        $analiseAtual = $analises->get(0);
        $analiseAnterior = $analises->get(1);

        abort_unless($analiseAtual, 404, 'Nenhuma analise encontrada para este atleta.');

        $progresso = $this->montarProgressoNarrativo($analiseAtual, $analiseAnterior);
        $narrativa = $this->montarNarrativa($progresso, $analiseAnterior !== null);
        $percentil = $this->montarPercentilTecnico($aluno, $analiseAtual);
        $semaforo = $aluno->obterSemaforo();
        $planoAtual = $this->obterPlanoAtual($aluno);

        return response()->json([
            'identificacao' => [
                'nome' => $aluno->nome,
                'matricula' => $aluno->matricula,
                'idade' => $aluno->idade,
                'sexo' => $aluno->sexo,
                'instituicao' => $aluno->instituicao?->nome,
                'ultima_analise' => $analiseAtual->created_at?->toDateString(),
                'analise_anterior' => $analiseAnterior?->created_at?->toDateString(),
            ],
            'narrativa' => $narrativa,
            'percentil' => $percentil,
            'status_principal' => $this->montarStatusPrincipal($semaforo),
            'selos' => $this->montarSelos($aluno, $analiseAtual, $progresso, $percentil, $planoAtual),
            'meta_atual' => $this->montarMetaAtual($planoAtual),
            'recomendacao_curta' => $this->montarRecomendacaoCurta($narrativa, $percentil, $planoAtual),
            'grupo_resumo' => $this->montarGrupoResumo($percentil),
        ]);
    }

    private function montarProgressoNarrativo($analiseAtual, $analiseAnterior): Collection
    {
        $campos = collect([
            ['campo' => 'arremesso', 'label' => 'Arremesso'],
            ['campo' => 'passe', 'label' => 'Passe'],
            ['campo' => 'marcacao', 'label' => 'Marcacao'],
            ['campo' => 'bandeja', 'label' => 'Bandeja'],
            ['campo' => 'rebote', 'label' => 'Rebote'],
            ['campo' => 'dominio', 'label' => 'Dominio de bola'],
            ['campo' => 'agilidade', 'label' => 'Agilidade'],
            ['campo' => 'flexibilidade', 'label' => 'Flexibilidade'],
            ['campo' => 'potencia_mmii', 'label' => 'Potencia MMII'],
            ['campo' => 'potencia_mmss', 'label' => 'Potencia MMSS'],
            ['campo' => 'capacidade_aerobica', 'label' => 'Capacidade aerobica'],
        ]);

        return $campos->map(function (array $item) use ($analiseAtual, $analiseAnterior) {
            $atual = $analiseAtual?->{$item['campo']};
            $anterior = $analiseAnterior?->{$item['campo']};

            if ($anterior === null || $atual === null) {
                $status = 'sem_base';
                $delta = null;
            } else {
                $delta = (float) $atual - (float) $anterior;
                $status = $delta > 0 ? 'subiu' : ($delta < 0 ? 'caiu' : 'manteve');
            }

            return [
                'campo' => $item['campo'],
                'label' => $item['label'],
                'delta' => $delta,
                'status' => $status,
            ];
        });
    }

    private function montarNarrativa(Collection $progresso, bool $temComparacao): array
    {
        $melhoras = $progresso->where('status', 'subiu')->sortByDesc('delta')->pluck('label')->take(3)->values();
        $quedas = $progresso->where('status', 'caiu')->sortBy('delta')->pluck('label')->take(3)->values();
        $estaveis = $progresso->where('status', 'manteve')->pluck('label')->take(3)->values();

        if (! $temComparacao) {
            return [
                'texto_atleta' => 'Esta e a primeira analise registrada. Quando a proxima avaliacao for lancada, o sistema vai resumir sua evolucao.',
                'texto_tecnico' => 'O atleta ainda possui apenas uma analise. A narrativa automatica sera liberada a partir da proxima comparacao.',
                'melhoras' => [],
                'quedas' => [],
                'estaveis' => [],
                'resumo' => ['subiu' => 0, 'caiu' => 0, 'manteve' => 0],
            ];
        }

        return [
            'texto_atleta' => $this->montarTextoNarrativo($melhoras, $quedas, $estaveis, false),
            'texto_tecnico' => $this->montarTextoNarrativo($melhoras, $quedas, $estaveis, true),
            'melhoras' => $melhoras->all(),
            'quedas' => $quedas->all(),
            'estaveis' => $estaveis->all(),
            'resumo' => [
                'subiu' => $progresso->where('status', 'subiu')->count(),
                'caiu' => $progresso->where('status', 'caiu')->count(),
                'manteve' => $progresso->where('status', 'manteve')->count(),
            ],
        ];
    }

    private function montarStatusPrincipal(array $semaforo): array
    {
        return [
            'nivel' => $semaforo['nivel'],
            'rotulo' => match ($semaforo['nivel']) {
                'verde' => 'Voce esta em dia',
                'amarelo' => 'Atencao ao seu acompanhamento',
                default => 'Acao recomendada',
            },
            'motivo' => $semaforo['motivo'],
            'mensagem' => match ($semaforo['motivo']) {
                'Sem analise' => 'Ainda nao existe analise suficiente para acompanhar seu desempenho.',
                'Saude pendente' => 'Existe uma pendencia de saude que precisa ser revisada com o tecnico.',
                'Plano atrasado' => 'Sua meta atual esta atrasada e merece atencao nesta semana.',
                'Sem telefone' => 'Seu cadastro ainda precisa de alguns ajustes simples.',
                'Analise desatualizada' => 'Sua ultima analise ja passou do periodo ideal de acompanhamento.',
                default => 'Seu acompanhamento atual esta regular e sem pendencias importantes.',
            },
        ];
    }

    private function montarTextoNarrativo(Collection $melhoras, Collection $quedas, Collection $estaveis, bool $tecnico): string
    {
        $partes = [];

        if ($melhoras->isNotEmpty()) {
            $partes[] = ($tecnico ? 'Evoluiu em ' : 'Voce evoluiu em ') . $this->listarRotulos($melhoras);
        }

        if ($estaveis->isNotEmpty()) {
            $partes[] = 'manteve ' . $this->listarRotulos($estaveis);
        }

        if ($quedas->isNotEmpty()) {
            $partes[] = 'apresentou queda em ' . $this->listarRotulos($quedas);
        }

        if (empty($partes)) {
            return $tecnico
                ? 'Nao houve variacao relevante entre a ultima analise e a anterior.'
                : 'Voce manteve os indicadores sem variacoes relevantes na ultima comparacao.';
        }

        return rtrim(ucfirst(implode(', ', $partes)), '.') . '.';
    }

    private function listarRotulos(Collection $itens): string
    {
        $valores = $itens->values()->all();
        $total = count($valores);

        if ($total === 1) {
            return $valores[0];
        }

        if ($total === 2) {
            return $valores[0] . ' e ' . $valores[1];
        }

        $ultimo = array_pop($valores);

        return implode(', ', $valores) . ' e ' . $ultimo;
    }

    private function obterPlanoAtual(Aluno $aluno)
    {
        return $aluno->planosAcao
            ->first(function ($plano) {
                return $plano->status !== 'concluido';
            });
    }

    private function montarPercentilTecnico(Aluno $aluno, $analiseAtual): array
    {
        $scoreAtleta = $this->calcularMediaTecnica($analiseAtual);

        if ($scoreAtleta === null) {
            return $this->retornoPercentilSemBase(
                'Ainda nao foi possivel calcular a media tecnica atual deste atleta.',
                null,
                null,
                0,
                null
            );
        }

        $grupo = Aluno::query()
            ->with('ultimaAnalise')
            ->where('instituicao_id', $aluno->instituicao_id)
            ->where('sexo', $aluno->sexo)
            ->where('idade', $aluno->idade)
            ->get();

        $ranking = $this->montarRankingTecnicoDoGrupo($grupo);

        if ($ranking->count() < 2) {
            return $this->retornoPercentilSemBase(
                'O grupo de referencia ainda nao tem atletas suficientes com analise para comparar os fundamentos tecnicos.',
                $scoreAtleta,
                $ranking->avg('score_bruto'),
                $ranking->count(),
                $this->montarPosicaoLista($ranking, $aluno->id)
            );
        }

        $posicao = $ranking->search(fn(array $item) => (int) $item['aluno_id'] === (int) $aluno->id);

        if ($posicao === false) {
            return $this->retornoPercentilSemBase(
                'Nao foi possivel localizar este atleta dentro do grupo de comparacao.',
                $scoreAtleta,
                $ranking->avg('score_bruto'),
                $ranking->count(),
                null
            );
        }

        $percentil = (int) round((($posicao + 1) / $ranking->count()) * 100);
        $classificacao = 'Dentro do grupo';
        $classKey = 'dentro';

        if ($percentil < 35) {
            $classificacao = 'Abaixo do grupo';
            $classKey = 'abaixo';
        } elseif ($percentil > 65) {
            $classificacao = 'Acima do grupo';
            $classKey = 'acima';
        }

        return [
            'base_suficiente' => true,
            'classificacao' => $classificacao,
            'class_key' => $classKey,
            'percentil' => $percentil,
            'posicao_lista' => ($posicao + 1) . ' de ' . $ranking->count(),
            'score_atleta' => $this->formatarNumero($scoreAtleta),
            'media_grupo' => $this->formatarNumero($ranking->avg('score_bruto')),
            'total_grupo' => $ranking->count(),
            'descricao' => 'Comparacao com atletas da mesma instituicao, idade e sexo, cruzando os 6 fundamentos tecnicos mais recentes de cada atleta.',
        ];
    }

    private function montarSelos(Aluno $aluno, $analiseAtual, Collection $progresso, array $percentil, $planoAtual): array
    {
        $selos = [];
        $pontoForte = $this->obterMelhorIndicadorAtual($analiseAtual);
        $melhorEvolucao = $progresso->where('status', 'subiu')->sortByDesc('delta')->first();

        if ($pontoForte) {
            $selos[] = [
                'icone' => 'star-fill',
                'titulo' => 'Ponto forte',
                'texto' => $pontoForte['label'] . ' e o seu melhor indicador atual.',
            ];
        }

        if ($melhorEvolucao) {
            $selos[] = [
                'icone' => 'graph-up-arrow',
                'titulo' => 'Melhor evolucao',
                'texto' => $melhorEvolucao['label'] . ' foi o destaque positivo da ultima comparacao.',
            ];
        }

        if ($percentil['class_key'] === 'acima') {
            $selos[] = [
                'icone' => 'award-fill',
                'titulo' => 'Acima do grupo',
                'texto' => 'Seu desempenho tecnico esta acima do grupo da sua idade e sexo.',
            ];
        } elseif ($planoAtual) {
            $selos[] = [
                'icone' => 'flag-fill',
                'titulo' => 'Meta em andamento',
                'texto' => 'Voce tem uma meta ativa para acompanhar junto com o tecnico.',
            ];
        } elseif ($aluno->ultimaAnalise && $aluno->ultimaAnalise->created_at->gte(now()->subDays(30))) {
            $selos[] = [
                'icone' => 'check-circle-fill',
                'titulo' => 'Analise atualizada',
                'texto' => 'Seu acompanhamento esta dentro do periodo ideal.',
            ];
        }

        return collect($selos)->take(3)->values()->all();
    }

    private function montarMetaAtual($planoAtual): array
    {
        if (! $planoAtual) {
            return [
                'tem_meta' => false,
                'titulo' => 'Nenhuma meta ativa',
                'texto' => 'No momento nao ha plano de acao em aberto para este atleta.',
                'status' => '--',
                'prazo' => '--',
                'prioridade' => '--',
            ];
        }

        return [
            'tem_meta' => true,
            'titulo' => $planoAtual->titulo,
            'texto' => $planoAtual->descricao ?: 'Acompanhe esta meta com o tecnico ao longo das proximas analises.',
            'status' => $planoAtual->obterRotuloStatus(),
            'prazo' => $planoAtual->prazo?->format('d/m/Y') ?? '--',
            'prioridade' => ucfirst($planoAtual->prioridade),
        ];
    }

    private function montarRecomendacaoCurta(array $narrativa, array $percentil, $planoAtual): string
    {
        if (! empty($narrativa['melhoras']) && ! empty($narrativa['quedas'])) {
            return 'Voce evoluiu em ' . $narrativa['melhoras'][0] . ', mas agora vale focar mais em ' . $narrativa['quedas'][0] . '.';
        }

        if (! empty($narrativa['melhoras'])) {
            return 'Voce esta evoluindo em ' . $narrativa['melhoras'][0] . '. Continue esse ritmo na proxima avaliacao.';
        }

        if (! empty($narrativa['quedas'])) {
            return 'Seu foco imediato pode ser ' . $narrativa['quedas'][0] . ' para recuperar rendimento.';
        }

        if ($planoAtual) {
            return 'Sua meta atual e ' . $planoAtual->titulo . '. Mantenha atencao ao prazo combinado.';
        }

        return $percentil['class_key'] === 'acima'
            ? 'Seu desempenho tecnico esta acima do grupo. O objetivo agora e manter consistencia.'
            : 'Continue acompanhando suas analises para identificar os proximos pontos de evolucao.';
    }

    private function montarGrupoResumo(array $percentil): array
    {
        return [
            'titulo' => match ($percentil['class_key']) {
                'acima' => 'Voce esta acima do grupo',
                'abaixo' => 'Voce esta abaixo do grupo',
                'dentro' => 'Voce esta dentro do grupo',
                default => 'Base de comparacao reduzida',
            },
            'texto' => match ($percentil['class_key']) {
                'acima' => 'Seu desempenho tecnico recente esta acima do grupo da mesma idade e sexo.',
                'abaixo' => 'Existe espaco para crescer em relacao ao grupo da mesma idade e sexo.',
                'dentro' => 'Seu desempenho tecnico esta dentro da faixa esperada para o grupo.',
                default => 'Ainda nao existem atletas suficientes com analise para uma leitura completa do grupo.',
            },
        ];
    }

    private function retornoPercentilSemBase(string $descricao, ?float $scoreAtleta, $mediaGrupo, int $totalGrupo, ?string $posicaoLista): array
    {
        return [
            'base_suficiente' => false,
            'classificacao' => 'Base reduzida',
            'class_key' => 'sem_base',
            'percentil' => '--',
            'posicao_lista' => $posicaoLista ?? '--',
            'score_atleta' => $this->formatarNumero($scoreAtleta),
            'media_grupo' => $this->formatarNumero($mediaGrupo),
            'total_grupo' => $totalGrupo,
            'descricao' => $descricao,
        ];
    }

    private function obterMelhorIndicadorAtual($analise): ?array
    {
        if (! $analise) {
            return null;
        }

        return collect([
            ['campo' => 'arremesso', 'label' => 'Arremesso'],
            ['campo' => 'passe', 'label' => 'Passe'],
            ['campo' => 'marcacao', 'label' => 'Marcacao'],
            ['campo' => 'bandeja', 'label' => 'Bandeja'],
            ['campo' => 'rebote', 'label' => 'Rebote'],
            ['campo' => 'dominio', 'label' => 'Dominio de bola'],
            ['campo' => 'agilidade', 'label' => 'Agilidade'],
            ['campo' => 'flexibilidade', 'label' => 'Flexibilidade'],
            ['campo' => 'potencia_mmii', 'label' => 'Potencia MMII'],
            ['campo' => 'potencia_mmss', 'label' => 'Potencia MMSS'],
            ['campo' => 'capacidade_aerobica', 'label' => 'Capacidade aerobica'],
        ])->map(function (array $item) use ($analise) {
            return [
                'label' => $item['label'],
                'valor' => $analise->{$item['campo']},
            ];
        })->filter(fn(array $item) => $item['valor'] !== null)
            ->sortByDesc('valor')
            ->first();
    }

    private function calcularMediaTecnica($analise): ?float
    {
        if (! $analise) {
            return null;
        }

        $campos = ['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'];
        $valores = collect($campos)->map(fn(string $campo) => $analise->{$campo})->filter(fn($valor) => $valor !== null);

        return $valores->isEmpty() ? null : (float) $valores->avg();
    }

    private function montarRankingTecnicoDoGrupo(Collection $grupo): Collection
    {
        $campos = ['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'];

        return $grupo
            ->map(function (Aluno $colega) use ($grupo, $campos) {
                $percentis = collect($campos)
                    ->map(function (string $campo) use ($grupo, $colega) {
                        $grupoCampo = $grupo
                            ->filter(fn(Aluno $item) => $item->ultimaAnalise?->{$campo} !== null)
                            ->sortBy(fn(Aluno $item) => $item->ultimaAnalise->{$campo})
                            ->values();

                        if ($grupoCampo->count() < 2 || $colega->ultimaAnalise?->{$campo} === null) {
                            return null;
                        }

                        $posicao = $grupoCampo->search(fn(Aluno $item) => (int) $item->id === (int) $colega->id);

                        if ($posicao === false) {
                            return null;
                        }

                        return (($posicao + 1) / $grupoCampo->count()) * 100;
                    })
                    ->filter(fn($valor) => $valor !== null);

                if ($percentis->isEmpty()) {
                    return null;
                }

                return [
                    'aluno_id' => $colega->id,
                    'percentil_medio' => (float) $percentis->avg(),
                    'score_bruto' => $this->calcularMediaTecnica($colega->ultimaAnalise),
                ];
            })
            ->filter(fn($item) => $item !== null)
            ->sortBy('percentil_medio')
            ->values();
    }

    private function formatarNumero($valor): string
    {
        if ($valor === null) {
            return '--';
        }

        return number_format((float) $valor, 1, ',', '.');
    }

    private function montarPosicaoLista(Collection $grupo, int $alunoId): ?string
    {
        $posicao = $grupo->search(fn(array $item) => (int) $item['aluno_id'] === $alunoId);

        if ($posicao === false) {
            return null;
        }

        return ($posicao + 1) . ' de ' . $grupo->count();
    }

    private function obterAlunoAutorizadoPorMatricula(string $matricula, bool $carregarRelacoes = false): Aluno
    {
        $query = Aluno::query()->where('matricula', $matricula);

        if ($carregarRelacoes) {
            $query->with(['instituicao:id,nome']);
        }

        if (Auth::check() && Auth::user()->is_admin) {
            return $query->firstOrFail();
        }

        $instituicaoId = Auth::guard('athlete')->id();

        if (! $instituicaoId && Auth::check()) {
            $instituicaoId = Auth::user()->instituicao_id;
        }

        abort_unless($instituicaoId, 403, 'Acesso nao autorizado.');

        return $query->where('instituicao_id', $instituicaoId)->firstOrFail();
    }
}
