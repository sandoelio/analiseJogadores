<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Aluno;
use Illuminate\Support\Str;
use App\Models\AlunoHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class AlunoController extends Controller
{
    public function __construct()
    {
        // Aplica checagem de sessão em todas as routes deste controller
        $this->middleware(\App\Http\Middleware\CheckSession::class);
    }

    /**
     * Exibe o dashboard do aluno.
     */
    // Apenas para usuários autenticados
    public function dashboard()
    {
        $instId = session('aluno_instituicao_id');
        $alunos = Aluno::where('instituicao_id', $instId)->get();

        return view('aluno.dashboard', compact('alunos'));
    }

    /**
     * Lista todos os alunos do usuário autenticado.
     */
    public function index()
    {
        $user = Auth::user();
        $instituicaoId = $user->instituicao_id;

        // Detecta dispositivo 
        $agent = new Agent();
        $perPage = $agent->isMobile() ? 6 : 10;

        // Puxa todos os alunos da mesma instituição com paginação dinâmica 
        $alunos = Aluno::where('instituicao_id', $instituicaoId)->orderByRaw('CASE WHEN idade IS NULL THEN 1 ELSE 0 END, idade ASC')->paginate($perPage);

        // Total absoluto (query separada) 
        $totalAlunos = $alunos->total();

        return view('aluno.index', compact('alunos', 'totalAlunos'));
    }

    /**
     * Exibe formulário para inserir aluno + análise.
     */
    public function create()
    {
        return view('aluno.create');
    }

    /**
     * Atualiza as habilidades do aluno.
     * Registra uma nova análise com os dados fornecidos.
     */
    public function updateHabilidade(Request $request)
    {
        // Salva apenas os digitos para manter o valor consistente no banco.
        $telefone = preg_replace('/\D+/', '', (string) $request->input('telefone', ''));
        $request->merge([
            'telefone' => $telefone !== '' ? $telefone : null,
        ]);
        // validação permissiva: os campos podem ou não vir no request (só validar formato quando vierem)
        $rules = [
            'aluno_id' => 'required|exists:alunos,id',
            'data_nascimento' => 'sometimes|nullable|date',
            'sexo' => 'sometimes|nullable|in:Masculino,Feminino',
            'idade' => 'sometimes|nullable|integer|min:0',
            'telefone' => 'sometimes|nullable|digits_between:10,11',
            // Técnicos 
            'arremesso' => 'sometimes|nullable|integer|between:0,10',
            'passe' => 'sometimes|nullable|integer|between:0,10',
            'marcacao' => 'sometimes|nullable|integer|between:0,10',
            'bandeja' => 'sometimes|nullable|integer|between:0,10',
            'rebote' => 'sometimes|nullable|integer|between:0,10',
            'dominio' => 'sometimes|nullable|integer|between:0,10',
            // Físicos
            'potencia_mmss' => 'sometimes|nullable|numeric|min:0',
            'capacidade_aerobica' => 'sometimes|nullable|numeric|min:0',
            'agilidade' => 'sometimes|nullable|numeric|min:0',
            'flexibilidade' => 'sometimes|nullable|numeric|min:0',
            'potencia_mmii' => 'sometimes|nullable|numeric|min:0',
            // Corporal
            'massa_corporal_kg' => 'sometimes|nullable|numeric|min:0',
            'gordura_pct' => 'sometimes|nullable|numeric|min:0',
            'massa_magra_pct' => 'sometimes|nullable|numeric|min:0',
            'envergadura_cm' => 'sometimes|nullable|numeric|min:0',
            'imc' => 'sometimes|nullable|numeric|min:0',
            // Saúde 
            'problema_saude' => 'sometimes|nullable|boolean',
            'atestado_valido' => 'sometimes|nullable|boolean',
            'usa_medicacao' => 'sometimes|nullable|boolean',
        ];

        $data = $request->validate($rules);

        $aluno = Aluno::findOrFail($data['aluno_id']);

        // Se vierem campos de identificação no request, atualiza o aluno 
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

        // atributos completos que a tabela analises espera
        $allAttrs = ['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio', 'potencia_mmss', 'capacidade_aerobica', 'agilidade', 'flexibilidade', 'potencia_mmii', 'massa_corporal_kg', 'gordura_pct', 'massa_magra_pct', 'envergadura_cm', 'imc', 'problema_saude', 'atestado_valido', 'usa_medicacao',];

        // buscar ultima analise existente (para usar como base)
        $ultima = $aluno->analises()->orderBy('created_at', 'desc')->first();

        // montar um array base com valores padrões (null) ou da última análise
        $base = [];
        foreach ($allAttrs as $attr) {
            if ($ultima) {
                $base[$attr] = $ultima->{$attr};
            } else {
                $base[$attr] = null; // ou defina um default específico se preferir
            }
        }

        // sobrescrever base com os valores enviados no request (somente os que vieram)
        foreach ($allAttrs as $attr) {
            if (array_key_exists($attr, $data)) {
                $base[$attr] = $data[$attr];
            }
        }

        // agora criar a nova análise com todos os campos (preenchidos parcialmente)
        $analise = $aluno->analises()->create($base);

        // montar payloadAtual organizado (como antes)
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
            ],
            'composicao' => [
                'massa_corporal_kg' => $analise->massa_corporal_kg,
                'gordura_pct' => $analise->gordura_pct,
                'massa_magra_pct' => $analise->massa_magra_pct,
                'envergadura_cm' => $analise->envergadura_cm,
                'imc' => $analise->imc,
            ],
            'saude' => [
                'problema_saude' => $analise->problema_saude,
                'atestado_valido' => $analise->atestado_valido,
                'usa_medicacao' => $analise->usa_medicacao,
            ],
            'analise_id' => $analise->id,
        ];
        // calcular diff comparando com última análise (se existir)
        $diff = [];
        if ($ultima) {
            $groups = [
                'tecnicos' => ['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'],
                'fisicos' => ['potencia_mmss', 'capacidade_aerobica', 'agilidade', 'flexibilidade', 'potencia_mmii'],
                'composicao' => ['massa_corporal_kg', 'gordura_pct', 'massa_magra_pct', 'envergadura_cm', 'imc'],
                'saude' => ['problema_saude', 'atestado_valido', 'usa_medicacao'],
            ];

            foreach ($groups as $g => $attrs) {
                foreach ($attrs as $a) {
                    $antes = $ultima->{$a};
                    $depois = $analise->{$a};
                    // considerar diferença mesmo entre null e valor
                    if ($antes !== $depois) {
                        $diff[$g][$a] = ['antes' => $antes, 'depois' => $depois];
                    }
                }
                if (empty($diff[$g])) unset($diff[$g]);
            }
        } else {
            // primeira análise: opcionalmente considerar diff vazio (gravamos payload completo)
            $diff = [];
        }

        // montar dados a gravar: gravar somente diff quando houver, senão payload completo
        if (!empty($diff)) {
            $dadosHistorico = ['diff' => $diff];
        } else {
            $dadosHistorico = $payloadAtual;
        }

        // registra usando o observer (mantém changed_by e timestamp consistente)
        app(\App\Observers\AlunoObserver::class)
            ->recordAnalise($aluno, $dadosHistorico, Auth::id(), $analise->created_at);

        return redirect()
            ->route('aluno.updateForm')
            ->with('success', "Nova análise registrada para {$aluno->nome}.");
    }


    /**
     * Exibe o formulário para criar uma nova habilidade.
     */
    public function habilidade(Request $request)
    {
        $user = Auth::user();
        $instId = $user->instituicao_id;

        // Sempre passo para a view a lista de alunos da instituição
        $alunos = Aluno::where('instituicao_id', $instId)
            ->orderBy('nome')
            ->get();
        return view('aluno.habilidade', compact('alunos'));
    }

    /**
     * Retorna em JSON a última análise do aluno.
     */
    public function fetchLastAnalysis(Aluno $aluno)
    {
        $analise = $aluno
            ->analises()
            ->latest('created_at')
            ->first();

        // Monta identificação a partir do aluno 
        $dataNasc = $aluno->data_nascimento ? $aluno->data_nascimento->toDateString() : null;
        $idade = $aluno->idade ?? ($dataNasc ? \Carbon\Carbon::parse($dataNasc)->age : null);
        $identificacao = [
            'aluno_id' => $aluno->id,
            'nome' => $aluno->nome,
            'data_nascimento' => $dataNasc,
            'sexo' => $aluno->sexo ?? null,
            'idade' => $idade,
            'telefone' => $aluno->telefone ?? null,
        ];
        if (!$analise) {
            // Retorna identificação mesmo quando não há análise (útil para preencher a aba 1)
            return response()->json([
                'identificacao' => $identificacao,
                'error' => 'Nenhuma análise encontrada.'
            ], 404);
        }
        // Resposta: inclui identificação (aninhada) e mantém os campos de análise no topo 
        return response()->json(array_merge(
            [
                'identificacao' => $identificacao,
                'nome' => $aluno->nome,
            ],
            [ // Habilidades Técnicas 
                'arremesso' => $analise->arremesso,
                'passe' => $analise->passe,
                'marcacao' => $analise->marcacao,
                'bandeja' => $analise->bandeja,
                'rebote' => $analise->rebote,
                'dominio' => $analise->dominio,

                // Atributos Físicos 
                'potencia_mmss' => $analise->potencia_mmss,
                'capacidade_aerobica' => $analise->capacidade_aerobica,
                'agilidade' => $analise->agilidade,
                'flexibilidade' => $analise->flexibilidade,
                'potencia_mmii' => $analise->potencia_mmii,

                // Composição Corporal 
                'massa_corporal_kg' => $analise->massa_corporal_kg,
                'gordura_pct' => $analise->gordura_pct,
                'massa_magra_pct' => $analise->massa_magra_pct,
                'envergadura_cm' => $analise->envergadura_cm,
                'imc' => $analise->imc,

                // Informações de Saúde 
                'problema_saude' => $analise->problema_saude,
                'atestado_valido' => $analise->atestado_valido,
                'usa_medicacao' => $analise->usa_medicacao,

                // id da análise 
                'analise_id' => $analise->id,
            ]
        ));
    }

    /**
     * Cria ou recupera o Aluno (gerando matrícula só se for novo)
     * e registra uma nova Análise.
     */
    public function store(Request $request)
    {
        $user          = Auth::user();
        $userId        = $user->id;
        $instituicaoId = $user->instituicao_id;

        // Salva apenas os digitos para manter o valor consistente no banco.
        $telefone = preg_replace('/\D+/', '', (string) $request->input('telefone', ''));
        $request->merge([
            'telefone' => $telefone !== '' ? $telefone : null,
        ]);

        // Validação completa
        $data = $request->validate([

            'nome' => 'required|string|max:255',
            'data_nascimento' => 'nullable|date',
            'sexo' => 'nullable|in:Masculino,Feminino,M,F',
            'telefone' => 'nullable|digits_between:10,11',
            // Técnicos 
            'arremesso' => 'required|integer|between:0,10',
            'passe' => 'required|integer|between:0,10',
            'marcacao' => 'required|integer|between:0,10',
            'bandeja' => 'required|integer|between:0,10',
            'rebote' => 'required|integer|between:0,10',
            'dominio' => 'required|integer|between:0,10',
            // Atributos físicos 
            'potencia_mmss' => 'required|numeric|min:0',
            'capacidade_aerobica' => 'required|numeric|min:0',
            'agilidade' => 'required|numeric|min:0',
            'flexibilidade' => 'required|numeric|min:0',
            'potencia_mmii' => 'required|numeric|min:0',
            // Composição corporal 
            'massa_corporal_kg' => 'required|numeric|min:0',
            'gordura_pct' => 'required|numeric|min:0',
            'massa_magra_pct' => 'required|numeric|min:0',
            'envergadura_cm' => 'required|numeric|min:0',
            'imc' => 'required|numeric|min:0',
            // Saúde 
            'problema_saude' => 'nullable|boolean',
            'atestado_valido' => 'nullable|boolean',
            'usa_medicacao' => 'nullable|boolean',
        ]);

        // Normaliza campos opcionais (evita Undefined array key) 
        $data['problema_saude'] = $request->has('problema_saude') ? (int) $request->input('problema_saude') : null;
        $data['atestado_valido'] = $request->has('atestado_valido') ? (int) $request->input('atestado_valido') : null;
        $data['usa_medicacao'] = $request->has('usa_medicacao') ? (int) $request->input('usa_medicacao') : null;

        // Verifica se já existe
        $jaCadastrado = Aluno::where('nome', $data['nome'])
            ->where('user_id', $userId)
            ->where('instituicao_id', $instituicaoId)
            ->exists();

        if ($jaCadastrado) {
            return back()
                ->withErrors(['nome' => 'Este atleta já está cadastrado.'])
                ->withInput();
        }

        // Gera matrícula
        $sigla     = strtoupper(substr($user->instituicao->nome, 0, 3));
        $uid       = Str::random(7);
        $matricula = "{$sigla}-{$uid}";

        // Normaliza sexo para 'M' ou 'F' (ou null) 
        $sexo = null;
        if (!empty($data['sexo'])) {
            $s = $data['sexo'];
            if (in_array($s, ['Masculino', 'M'], true)) {
                $sexo = 'Masculino';
            } elseif (in_array($s, ['Feminino', 'F'], true)) {
                $sexo = 'Feminino';
            }
        }

        // Calcula idade a partir da data de nascimento (se fornecida) 
        $idade = null;
        if (!empty($data['data_nascimento'])) {
            try {
                $idade = Carbon::parse($data['data_nascimento'])->age;
                // garante valor não-negativo 
                if ($idade < 0) {
                    $idade = null;
                }
            } catch (\Exception $e) {
                $idade = null;
            }
        }

        // Cria aluno
        $aluno = Aluno::firstOrCreate(
            [
                'nome'           => $data['nome'],
                'user_id'        => $userId,
                'instituicao_id' => $instituicaoId,
            ],
            [
                'matricula'      => $matricula,
                // preenche os novos campos (data_nascimento, sexo, idade) 
                'data_nascimento' => $data['data_nascimento'] ?? null,
                'sexo' => $sexo,
                'idade' => $idade,
                'telefone' => $data['telefone'] ?? null,
            ]
        );

        // Registra análise completa
        $aluno->analises()->create([

            'arremesso' => $data['arremesso'],
            'passe' => $data['passe'],
            'marcacao' => $data['marcacao'],
            'bandeja' => $data['bandeja'],
            'rebote' => $data['rebote'],
            'dominio' => $data['dominio'],
            'potencia_mmss' => $data['potencia_mmss'],
            'capacidade_aerobica' => $data['capacidade_aerobica'],
            'agilidade' => $data['agilidade'],
            'flexibilidade' => $data['flexibilidade'],
            'potencia_mmii' => $data['potencia_mmii'],
            'massa_corporal_kg' => $data['massa_corporal_kg'],
            'gordura_pct' => $data['gordura_pct'],
            'massa_magra_pct' => $data['massa_magra_pct'],
            'envergadura_cm' => $data['envergadura_cm'],
            'imc' => $data['imc'],
            'problema_saude' => $data['problema_saude'],
            'atestado_valido' => $data['atestado_valido'],
            'usa_medicacao' => $data['usa_medicacao'],
        ]);

        return redirect()
            ->route('aluno.create')
            ->with('success', "Análise registrada para {$aluno->nome} (Matrícula: {$aluno->matricula}).");
    }

    /** Exibe o formulário para editar apenas o nome do aluno */
    public function edit(Aluno $aluno)
    {
        $this->authorize('update', $aluno);
        return view('aluno.edit', compact('aluno'));
    }

    /** Atualiza somente o nome do aluno */
    public function update(Request $request, Aluno $aluno)
    {
        $this->authorize('update', $aluno);

        $data = $request->validate([
            'nome' => 'required|string|max:255',
        ]);

        $aluno->update(['nome' => $data['nome']]);

        return redirect()
            ->route('aluno.index')
            ->with('success', "Nome do aluno atualizado para “{$aluno->nome}”.");
    }

    /** Remove o aluno definitivamente */
    public function destroy(Aluno $aluno)
    {
        $this->authorize('delete', $aluno);

        DB::transaction(function () use ($aluno) {
            // 1) registra histórico de exclusão antes de remover o aluno
            AlunoHistory::create([
                'aluno_id'   => $aluno->id,
                'evento'     => 'deleted',
                'dados'      => ['motivo' => 'exclusão manual'], // adapte conforme necessário
                'changed_by' => Auth::id(),
            ]);

            // 2) apagar outras dependências manualmente caso não use ON DELETE CASCADE
            \App\Models\Analise::where('aluno_id', $aluno->id)->delete();

            // 3) por fim apaga o aluno (DELETE definitivo)
            $aluno->delete();
        });

        return redirect()
            ->route('aluno.index')
            ->with('success', "Aluno “{$aluno->nome}” excluído com sucesso.");
    }
    /**
     * Compara as duas últimas análises do aluno.
     */
    public function showComparativo(int $id)
    {
        $userId = session('user_id');

        $aluno = Aluno::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        $analises = $aluno->analises()
            ->latest()
            ->take(2)
            ->get();

        if ($analises->count() < 2) {
            return back()
                ->with('warning', 'Este aluno ainda não possui duas análises para comparação.');
        }

        return view('aluno.comparativo', [
            'aluno'    => $aluno,
            'atual'    => $analises[0],
            'anterior' => $analises[1],
        ]);
    }

    public function fetchExtras($matricula)
    {
        $aluno = Aluno::where('matricula', $matricula)->firstOrFail();

        $analise = $aluno->analises()->latest()->take(2)->get();
        $analiseAtual = $analise->first();
        $analiseAnterior = $analise->count() > 1 ? $analise->last() : null;

        if (!$analise) {
            return response()->json(['error' => 'Nenhuma análise encontrada.'], 404);
        }
        return response()->json([
            'fisico' => [
                'labels' => [
                    'Potência MMSS', 'Capacidade Aeróbica', 'Agilidade', 'Flexibilidade', 'Potência MMII'
                ], 
                'anterior' => [
                    $analiseAnterior?->potencia_mmss, 
                    $analiseAnterior?->capacidade_aerobica, 
                    $analiseAnterior?->agilidade, 
                    $analiseAnterior?->flexibilidade, 
                    $analiseAnterior?->potencia_mmii
                ], 
                'atual' => [
                    $analiseAtual->potencia_mmss, 
                    $analiseAtual->capacidade_aerobica, 
                    $analiseAtual->agilidade, 
                    $analiseAtual->flexibilidade, 
                    $analiseAtual->potencia_mmii
                ]
            ],
            'clinico' => [
                'labels' => [
                    'Massa Corporal (kg)', 
                    'Envergadura (cm)', 
                    'Massa Magra (%)', 
                    'Gordura (%)', 
                    'IMC'
                ], 
                'anterior' => [
                    $analiseAnterior?->massa_corporal_kg, 
                    $analiseAnterior?->envergadura_cm, 
                    $analiseAnterior?->massa_magra_pct, 
                    $analiseAnterior?->gordura_pct, 
                    $analiseAnterior?->imc
                ], 
                'atual' => [
                    $analiseAtual->massa_corporal_kg, 
                    $analiseAtual->envergadura_cm, 
                    $analiseAtual->massa_magra_pct, 
                    $analiseAtual->gordura_pct, 
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
}
