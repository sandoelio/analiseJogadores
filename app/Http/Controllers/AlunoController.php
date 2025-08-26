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
        $totalAlunos = Aluno::count();

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
            'aluno_id'     => 'required|exists:alunos,id',
            'arremesso'    => 'required|integer|between:0,10',
            'passe'        => 'required|integer|between:0,10',
            'marcacao'     => 'required|integer|between:0,10',
            'bandeja'      => 'required|integer|between:0,10',
            'rebote'       => 'required|integer|between:0,10',
            'dominio'      => 'required|integer|between:0,10',
        ]);

        $aluno = Aluno::findOrFail($data['aluno_id']);

        $aluno->analises()->create([
            'arremesso'   => $data['arremesso'],
            'passe'       => $data['passe'],
            'marcacao'    => $data['marcacao'],
            'bandeja'     => $data['bandeja'],
            'rebote'      => $data['rebote'],
            'dominio'     => $data['dominio'],
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

        if (! $analise) {
            return response()->json([
                'error' => 'Nenhuma análise encontrada.'
            ], 404);
        }

        // devolvêmos o nome do aluno e cada atributo
        return response()->json([
            'nome'        => $aluno->nome,
            'arremesso'   => $analise->arremesso,
            'passe'       => $analise->passe,
            'marcacao'    => $analise->marcacao,
            'bandeja'     => $analise->bandeja,
            'rebote'      => $analise->rebote,
            'dominio'     => $analise->dominio,
        ]);
    }

    /**
     * Cria ou recupera o Aluno (gerando matrícula só se for novo)
     * e registra uma nova Análise.
     */
    public function store(Request $request)
    {
        //dados do usuário e instituição
        $user          = Auth::user();
        $userId        = $user->id;
        $instituicaoId = $user->instituicao_id;

        // validação (sem 'matricula')
        $data = $request->validate([
            'nome'        => 'required|string|max:255',
            'arremesso'   => 'required|integer|between:0,10',
            'passe'       => 'required|integer|between:0,10',
            'marcacao'    => 'required|integer|between:0,10',
            'bandeja'     => 'required|integer|between:0,10',
            'rebote'      => 'required|integer|between:0,10',
            'dominio'     => 'required|integer|between:0,10',
        ]);
        
        // Checa existência
        $jaCadastrado = Aluno::where('nome', $data['nome'])
            ->where('user_id', $user->id)
            ->where('instituicao_id', $instituicaoId)
            ->exists();

        if ($jaCadastrado) {
            return back()
                ->withErrors(['nome' => 'Este atleta já está cadastrado.'])
                ->withInput();
        }

        // gera matrícula só para novos alunos
        $sigla     = strtoupper(substr($user->instituicao->nome, 0, 3));
        $uid       = Str::random(7);
        $matricula = "{$sigla}-{$uid}";

        // 4. firstOrCreate: busca pelo aluno já existente
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

        // 5. registra a nova análise
        $aluno->analises()->create([
            'arremesso'   => $data['arremesso'],
            'passe'       => $data['passe'],
            'marcacao'    => $data['marcacao'],
            'bandeja'     => $data['bandeja'],
            'rebote'      => $data['rebote'],
            'dominio'     => $data['dominio'],
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
            ->route('aluno.create')
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
}
