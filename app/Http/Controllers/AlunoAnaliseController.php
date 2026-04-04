<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlunoUpdateHabilidadeRequest;
use App\Models\Aluno;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AlunoAnaliseController extends Controller
{
    /**
     * Exibe o formulario de atualizacao das analises.
     */
    public function habilidade()
    {
        $instId = Auth::user()->instituicao_id;

        $alunos = Aluno::where('instituicao_id', $instId)
            ->orderBy('nome')
            ->get();

        return view('aluno.habilidade', compact('alunos'));
    }

    /**
     * Atualiza as habilidades do aluno e registra nova analise.
     */
    public function updateHabilidade(AlunoUpdateHabilidadeRequest $request)
    {
        $data = $request->validated();

        $aluno = Aluno::findOrFail($data['aluno_id']);
        $this->garantirAcessoAoAluno($aluno);

        $alunoUpdates = [];

        if (array_key_exists('data_nascimento', $data)) {
            $alunoUpdates['data_nascimento'] = $data['data_nascimento'];
        }

        if (array_key_exists('sexo', $data)) {
            $sexo = $data['sexo'];

            if (in_array($sexo, ['M', 'm', 'Masculino', 'masculino'], true)) {
                $sexo = 'Masculino';
            } elseif (in_array($sexo, ['F', 'f', 'Feminino', 'feminino'], true)) {
                $sexo = 'Feminino';
            } else {
                $sexo = null;
            }

            $alunoUpdates['sexo'] = $sexo;
        }

        if (array_key_exists('idade', $data)) {
            $alunoUpdates['idade'] = $data['idade'];
        }

        if (array_key_exists('telefone', $data)) {
            $alunoUpdates['telefone'] = $data['telefone'];
        }

        if (!empty($alunoUpdates)) {
            $aluno->update($alunoUpdates);
        }

        if (($data['problema_saude'] ?? null) != 1) {
            $data['problema_saude_descricao'] = null;
        }

        if (($data['atestado_valido'] ?? null) != 1) {
            $data['data_atestado'] = null;
        }

        $allAttrs = [
            'arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio',
            'potencia_mmss', 'capacidade_aerobica', 'agilidade', 'flexibilidade', 'potencia_mmii',
            'massa_corporal_kg', 'gordura_pct', 'massa_magra_pct', 'envergadura_cm', 'imc',
            'problema_saude', 'problema_saude_descricao', 'atestado_valido', 'data_atestado', 'usa_medicacao',
        ];

        $ultima = $aluno->analises()->orderBy('created_at', 'desc')->first();

        $base = [];
        foreach ($allAttrs as $attr) {
            $base[$attr] = $ultima ? $ultima->{$attr} : null;
        }

        foreach ($allAttrs as $attr) {
            if (array_key_exists($attr, $data)) {
                $base[$attr] = $data[$attr];
            }
        }

        $analise = $aluno->analises()->create($base);

        $payloadAtual = [
            'identificacao' => [
                'aluno_id' => $aluno->id,
                'nome' => $aluno->nome,
                'data_nascimento' => $aluno->data_nascimento ? $aluno->data_nascimento->toDateString() : null,
                'sexo' => $aluno->sexo ?? null,
                'idade' => $aluno->idade ?? null,
                'telefone' => $aluno->telefone ?? null,
            ],
            'tecnicos' => [
                'arremesso' => $analise->arremesso,
                'passe' => $analise->passe,
                'marcacao' => $analise->marcacao,
                'bandeja' => $analise->bandeja,
                'rebote' => $analise->rebote,
                'dominio' => $analise->dominio,
            ],
            'fisicos' => [
                'potencia_mmss' => $analise->potencia_mmss,
                'capacidade_aerobica' => $analise->capacidade_aerobica,
                'agilidade' => $analise->agilidade,
                'flexibilidade' => $analise->flexibilidade,
                'potencia_mmii' => $analise->potencia_mmii,
                'envergadura_cm' => $analise->envergadura_cm,
            ],
            'composicao' => [
                'massa_corporal_kg' => $analise->massa_corporal_kg,
                'gordura_pct' => $analise->gordura_pct,
                'massa_magra_pct' => $analise->massa_magra_pct,
                'imc' => $analise->imc,
            ],
            'saude' => [
                'problema_saude' => $analise->problema_saude,
                'problema_saude_descricao' => $analise->problema_saude_descricao,
                'atestado_valido' => $analise->atestado_valido,
                'data_atestado' => $analise->data_atestado ? $analise->data_atestado->toDateString() : null,
                'usa_medicacao' => $analise->usa_medicacao,
            ],
            'analise_id' => $analise->id,
        ];

        $diff = [];
        if ($ultima) {
            $groups = [
                'tecnicos' => ['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'],
                'fisicos' => ['potencia_mmss', 'capacidade_aerobica', 'agilidade', 'flexibilidade', 'potencia_mmii', 'envergadura_cm'],
                'composicao' => ['massa_corporal_kg', 'gordura_pct', 'massa_magra_pct', 'imc'],
                'saude' => ['problema_saude', 'problema_saude_descricao', 'atestado_valido', 'data_atestado', 'usa_medicacao'],
            ];

            foreach ($groups as $grupo => $attrs) {
                foreach ($attrs as $attr) {
                    $antes = $ultima->{$attr};
                    $depois = $analise->{$attr};

                    if ($antes !== $depois) {
                        $diff[$grupo][$attr] = [
                            'antes' => $antes,
                            'depois' => $depois,
                        ];
                    }
                }

                if (empty($diff[$grupo])) {
                    unset($diff[$grupo]);
                }
            }
        }

        $dadosHistorico = !empty($diff) ? ['diff' => $diff] : $payloadAtual;

        app(\App\Observers\AlunoObserver::class)
            ->recordAnalise($aluno, $dadosHistorico, Auth::id(), $analise->created_at);

        return redirect()
            ->route('aluno.updateForm')
            ->with('success', "Nova analise registrada para {$aluno->nome}.");
    }

    /**
     * Retorna em JSON a ultima analise do aluno.
     */
    public function fetchLastAnalysis(Aluno $aluno)
    {
        $this->garantirAcessoAoAluno($aluno);

        $analise = $aluno->analises()->latest('created_at')->first();

        $dataNasc = $aluno->data_nascimento ? $aluno->data_nascimento->toDateString() : null;
        $idade = $aluno->idade ?? ($dataNasc ? Carbon::parse($dataNasc)->age : null);

        $identificacao = [
            'aluno_id' => $aluno->id,
            'nome' => $aluno->nome,
            'data_nascimento' => $dataNasc,
            'sexo' => $aluno->sexo ?? null,
            'idade' => $idade,
            'telefone' => $aluno->telefone ?? null,
        ];

        if (!$analise) {
            return response()->json([
                'identificacao' => $identificacao,
                'error' => 'Nenhuma analise encontrada.'
            ], 404);
        }

        return response()->json(array_merge(
            [
                'identificacao' => $identificacao,
                'nome' => $aluno->nome,
            ],
            [
                'arremesso' => $analise->arremesso,
                'passe' => $analise->passe,
                'marcacao' => $analise->marcacao,
                'bandeja' => $analise->bandeja,
                'rebote' => $analise->rebote,
                'dominio' => $analise->dominio,
                'potencia_mmss' => $analise->potencia_mmss,
                'capacidade_aerobica' => $analise->capacidade_aerobica,
                'agilidade' => $analise->agilidade,
                'flexibilidade' => $analise->flexibilidade,
                'potencia_mmii' => $analise->potencia_mmii,
                'massa_corporal_kg' => $analise->massa_corporal_kg,
                'gordura_pct' => $analise->gordura_pct,
                'massa_magra_pct' => $analise->massa_magra_pct,
                'envergadura_cm' => $analise->envergadura_cm,
                'imc' => $analise->imc,
                'problema_saude' => $analise->problema_saude,
                'problema_saude_descricao' => $analise->problema_saude_descricao,
                'atestado_valido' => $analise->atestado_valido,
                'data_atestado' => $analise->data_atestado ? $analise->data_atestado->toDateString() : null,
                'usa_medicacao' => $analise->usa_medicacao,
                'analise_id' => $analise->id,
            ]
        ));
    }

    /**
     * Compara as duas ultimas analises do aluno.
     */
    public function showComparativo(int $id)
    {
        $query = Aluno::where('id', $id);

        if (!Auth::user()->is_admin) {
            $query->where('instituicao_id', Auth::user()->instituicao_id);
        }

        $aluno = $query->firstOrFail();

        $analises = $aluno->analises()
            ->latest()
            ->take(2)
            ->get();

        if ($analises->count() < 2) {
            return back()
                ->with('warning', 'Este aluno ainda nao possui duas analises para comparacao.');
        }

        return view('aluno.comparativo', [
            'aluno' => $aluno,
            'atual' => $analises[0],
            'anterior' => $analises[1],
        ]);
    }

    /**
     * Retorna dados fisicos e corporais para os graficos extras.
     */
    public function fetchExtras($matricula)
    {
        $aluno = $this->obterAlunoAutorizadoPorMatricula($matricula);

        $analises = $aluno->analises()->latest()->take(2)->get();
        $analiseAtual = $analises->first();
        $analiseAnterior = $analises->count() > 1 ? $analises->last() : null;

        if (!$analiseAtual) {
            return response()->json(['error' => 'Nenhuma analise encontrada.'], 404);
        }

        return response()->json([
            'fisico' => [
                'labels' => [
                    'Potencia MMSS', 'Capacidade Aerobica', 'Agilidade', 'Flexibilidade', 'Potencia MMII', 'Envergadura (cm)'
                ],
                'anterior' => [
                    $analiseAnterior?->potencia_mmss,
                    $analiseAnterior?->capacidade_aerobica,
                    $analiseAnterior?->agilidade,
                    $analiseAnterior?->flexibilidade,
                    $analiseAnterior?->potencia_mmii,
                    $analiseAnterior?->envergadura_cm
                ],
                'atual' => [
                    $analiseAtual->potencia_mmss,
                    $analiseAtual->capacidade_aerobica,
                    $analiseAtual->agilidade,
                    $analiseAtual->flexibilidade,
                    $analiseAtual->potencia_mmii,
                    $analiseAtual->envergadura_cm
                ]
            ],
            'clinico' => [
                'labels' => [
                    'Massa Corporal (kg)',
                    'Gordura (%)',
                    'Massa Magra (%)',
                    'IMC'
                ],
                'anterior' => [
                    $analiseAnterior?->massa_corporal_kg,
                    $analiseAnterior?->gordura_pct,
                    $analiseAnterior?->massa_magra_pct,
                    $analiseAnterior?->imc
                ],
                'atual' => [
                    $analiseAtual->massa_corporal_kg,
                    $analiseAtual->gordura_pct,
                    $analiseAtual->massa_magra_pct,
                    $analiseAtual->imc
                ]
            ],
            'classificacao' => match (true) {
                $analiseAtual->gordura_pct < 5 => 'Muito Baixo',
                $analiseAtual->gordura_pct < 10 => 'Baixo',
                $analiseAtual->gordura_pct < 16 => 'Ideal',
                default => 'Acima do Ideal',
            }
        ]);
    }

    private function garantirAcessoAoAluno(Aluno $aluno): void
    {
        $usuario = Auth::user();

        if ($usuario && $usuario->is_admin) {
            return;
        }

        abort_unless(
            $usuario && (int) $usuario->instituicao_id === (int) $aluno->instituicao_id,
            403,
            'Acesso nao autorizado.'
        );
    }

    private function obterAlunoAutorizadoPorMatricula(string $matricula): Aluno
    {
        $query = Aluno::query()->where('matricula', $matricula);

        if (Auth::check() && Auth::user()->is_admin) {
            return $query->firstOrFail();
        }

        $instituicaoId = Auth::guard('athlete')->id();

        if (!$instituicaoId && Auth::check()) {
            $instituicaoId = Auth::user()->instituicao_id;
        }

        abort_unless($instituicaoId, 403, 'Acesso nao autorizado.');

        return $query->where('instituicao_id', $instituicaoId)->firstOrFail();
    }
}
