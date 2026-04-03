<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;

class AlunoPainelController extends Controller
{
    /**
     * Exibe o dashboard do modulo do tecnico ou do atleta.
     */
    public function dashboard()
    {
        $instId = session('aluno_instituicao_id') ?: Auth::user()?->instituicao_id;
        $alunos = Aluno::where('instituicao_id', $instId)->get();

        return view('aluno.dashboard', compact('alunos'));
    }

    /**
     * Lista os alunos da instituicao do usuario autenticado.
     */
    public function index()
    {
        $instituicaoId = Auth::user()->instituicao_id;

        $agent = new Agent();
        $perPage = $agent->isMobile() ? 6 : 10;

        $alunos = Aluno::where('instituicao_id', $instituicaoId)
            ->orderByRaw('CASE WHEN idade IS NULL THEN 1 ELSE 0 END, idade ASC')
            ->paginate($perPage);

        $totalAlunos = $alunos->total();

        return view('aluno.index', compact('alunos', 'totalAlunos'));
    }
}
