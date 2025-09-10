<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

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
        $user            = Auth::user();
        $instituicaoId   = $user->instituicao_id;

        // Puxa todos os alunos da mesma instituição
        $alunos = Aluno::where('instituicao_id', $instituicaoId)
            ->orderBy('nome')
            ->paginate(10);

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
        $data = $request->validate([
            'aluno_id'           => 'required|exists:alunos,id',

            // Habilidades Técnicas
            'arremesso'          => 'required|integer|between:0,10',
            'passe'              => 'required|integer|between:0,10',
            'marcacao'           => 'required|integer|between:0,10',
            'bandeja'            => 'required|integer|between:0,10',
            'rebote'             => 'required|integer|between:0,10',
            'dominio'            => 'required|integer|between:0,10',

            // Atributos Físicos
            'envergadura'        => 'required|numeric|min:0',
            'velocidade'         => 'required|numeric|min:0',
            'agilidade'          => 'required|numeric|min:0',
            'salto_horizontal'   => 'required|numeric|min:0',
            'resistencia'        => 'required|numeric|min:0|max:100',

            // Composição Corporal
            'massa_magra_kg'     => 'required|numeric|min:0',
            'massa_adiposa_kg'   => 'required|numeric|min:0',
            'massa_magra_pct'    => 'required|numeric|min:0|max:100',
            'massa_adiposa_pct'  => 'required|numeric|min:0|max:100',
            'peso_residual_kg'   => 'required|numeric|min:0',

            // Informações de Saúde
            'problema_saude'     => 'required|boolean',
            'atestado_valido'    => 'required|boolean',
            'usa_medicacao'      => 'required|boolean',
        ]);

        $aluno = Aluno::findOrFail($data['aluno_id']);

        $aluno->analises()->create([
            // Técnicas
            'arremesso'          => $data['arremesso'],
            'passe'              => $data['passe'],
            'marcacao'           => $data['marcacao'],
            'bandeja'            => $data['bandeja'],
            'rebote'             => $data['rebote'],
            'dominio'            => $data['dominio'],

            // Físicos
            'envergadura'        => $data['envergadura'],
            'velocidade'         => $data['velocidade'],
            'agilidade'          => $data['agilidade'],
            'salto_horizontal'   => $data['salto_horizontal'],
            'resistencia'        => $data['resistencia'],

            // Corporal
            'massa_magra_kg'     => $data['massa_magra_kg'],
            'massa_adiposa_kg'   => $data['massa_adiposa_kg'],
            'massa_magra_pct'    => $data['massa_magra_pct'],
            'massa_adiposa_pct'  => $data['massa_adiposa_pct'],
            'peso_residual_kg'   => $data['peso_residual_kg'],

            // Saúde
            'problema_saude'     => $data['problema_saude'],
            'atestado_valido'    => $data['atestado_valido'],
            'usa_medicacao'      => $data['usa_medicacao'],
        ]);

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

        if (!$analise) {
            return response()->json([
                'error' => 'Nenhuma análise encontrada.'
            ], 404);
        }

        return response()->json([
            'nome'               => $aluno->nome,

            // Habilidades Técnicas
            'arremesso'          => $analise->arremesso,
            'passe'              => $analise->passe,
            'marcacao'           => $analise->marcacao,
            'bandeja'            => $analise->bandeja,
            'rebote'             => $analise->rebote,
            'dominio'            => $analise->dominio,

            // Atributos Físicos
            'envergadura'        => $analise->envergadura,
            'velocidade'         => $analise->velocidade,
            'agilidade'          => $analise->agilidade,
            'salto_horizontal'   => $analise->salto_horizontal,
            'resistencia'        => $analise->resistencia,

            // Composição Corporal
            'massa_magra_kg'     => $analise->massa_magra_kg,
            'massa_adiposa_kg'   => $analise->massa_adiposa_kg,
            'massa_magra_pct'    => $analise->massa_magra_pct,
            'massa_adiposa_pct'  => $analise->massa_adiposa_pct,
            'peso_residual_kg'   => $analise->peso_residual_kg,

            // Informações de Saúde
            'problema_saude'     => $analise->problema_saude,
            'atestado_valido'    => $analise->atestado_valido,
            'usa_medicacao'      => $analise->usa_medicacao,
        ]);
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

        // Validação completa
        $data = $request->validate([
            'nome'               => 'required|string|max:255',
            'arremesso'          => 'required|integer|between:0,100',
            'passe'              => 'required|integer|between:0,100',
            'marcacao'           => 'required|integer|between:0,100',
            'bandeja'            => 'required|integer|between:0,100',
            'rebote'             => 'required|integer|between:0,100',
            'dominio'            => 'required|integer|between:0,100',

            // Atributos físicos (float → numeric)
            'envergadura'        => 'required|numeric|min:0',
            'velocidade'         => 'required|numeric|min:0',
            'agilidade'          => 'required|numeric|min:0',
            'salto_horizontal'   => 'required|numeric|min:0',
            'resistencia'        => 'required|numeric|min:0',

            // Composição corporal (float → numeric)
            'massa_magra_kg'     => 'required|numeric|min:0',
            'massa_adiposa_kg'   => 'required|numeric|min:0',
            'massa_magra_pct'    => 'required|numeric|min:0|max:100',
            'massa_adiposa_pct'  => 'required|numeric|min:0|max:100',
            'peso_residual_kg'   => 'required|numeric|min:0',
            'problema_saude'     => 'required|boolean',
            'atestado_valido'    => 'required|boolean',
            'usa_medicacao'      => 'required|boolean',

        ]);
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

        // Cria aluno
        $aluno = Aluno::firstOrCreate(
            [
                'nome'           => $data['nome'],
                'user_id'        => $userId,
                'instituicao_id' => $instituicaoId,
            ],
            [
                'matricula'      => $matricula,
            ]
        );

        // Registra análise completa
        $aluno->analises()->create([
            'arremesso'          => $data['arremesso'],
            'passe'              => $data['passe'],
            'marcacao'           => $data['marcacao'],
            'bandeja'            => $data['bandeja'],
            'rebote'             => $data['rebote'],
            'dominio'            => $data['dominio'],
            'envergadura'        => $data['envergadura'],
            'velocidade'         => $data['velocidade'],
            'agilidade'          => $data['agilidade'],
            'salto_horizontal'   => $data['salto_horizontal'],
            'resistencia'        => $data['resistencia'],
            'massa_magra_kg'     => $data['massa_magra_kg'],
            'massa_adiposa_kg'   => $data['massa_adiposa_kg'],
            'massa_magra_pct'    => $data['massa_magra_pct'],
            'massa_adiposa_pct'  => $data['massa_adiposa_pct'],
            'peso_residual_kg'   => $data['peso_residual_kg'],
            'problema_saude'     => $data['problema_saude'],
            'atestado_valido'    => $data['atestado_valido'],
            'usa_medicacao'      => $data['usa_medicacao'],
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
            ->route('aluno.create')
            ->with('success', "Nome do aluno atualizado para “{$aluno->nome}”.");
    }

    /** Remove o aluno definitivamente */
    public function destroy(Aluno $aluno)
    {
        $this->authorize('delete', $aluno);

        $aluno->delete();

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
                    'Envergadura',
                    'Velocidade',
                    'Agilidade',
                    'Salto Horizontal',
                    'Resistência'
                ],
                'anterior' => [
                    $analiseAnterior?->envergadura,
                    $analiseAnterior?->velocidade,
                    $analiseAnterior?->agilidade,
                    $analiseAnterior?->salto_horizontal,
                    $analiseAnterior?->resistencia
                ],
                'atual' => [
                    $analiseAtual->envergadura,
                    $analiseAtual->velocidade,
                    $analiseAtual->agilidade,
                    $analiseAtual->salto_horizontal,
                    $analiseAtual->resistencia
                ]
            ],
            'clinico' => [
                'labels' => [
                    'Massa Magra (kg)',
                    'Massa Adiposa (kg)',
                    'Massa Magra (%)',
                    'Massa Adiposa (%)',
                    'Peso Residual (kg)'
                ],
                'anterior' => [
                    $analiseAnterior?->massa_magra_kg,
                    $analiseAnterior?->massa_adiposa_kg,
                    $analiseAnterior?->massa_magra_pct,
                    $analiseAnterior?->massa_adiposa_pct,
                    $analiseAnterior?->peso_residual_kg
                ],
                'atual' => [
                    $analiseAtual->massa_magra_kg,
                    $analiseAtual->massa_adiposa_kg,
                    $analiseAtual->massa_magra_pct,
                    $analiseAtual->massa_adiposa_pct,
                    $analiseAtual->peso_residual_kg
                ]
            ],
            'classificacao' => match (true) {
                $analiseAtual->massa_adiposa_pct < 5  => 'Muito Baixo',
                $analiseAtual->massa_adiposa_pct < 10 => 'Baixo',
                $analiseAtual->massa_adiposa_pct < 16 => 'Ideal',
                default                                => 'Acima do Ideal',
            }
        ]);
    }
}
