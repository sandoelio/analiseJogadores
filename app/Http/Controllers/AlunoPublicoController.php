<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\Instituicao;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AlunoPublicoController extends Controller
{
    public function index()
    {
        $instituicoes = Instituicao::orderBy('nome')->get();

        return view('analise.index', compact('instituicoes'));
    }

    public function listarPorInstituicao($instituicaoId): JsonResponse
    {
        abort_unless($this->podeAcessarInstituicao((int) $instituicaoId), 403, 'Acesso nao autorizado.');

        $alunos = Aluno::where('instituicao_id', $instituicaoId)
            ->select('nome', 'matricula', 'idade')
            ->orderBy('idade', 'asc')
            ->get();

        return response()->json($alunos);
    }

    public function mostrar($matricula): JsonResponse
    {
        $aluno = $this->obterAlunoAutorizadoPorMatricula($matricula);

        $analises = $aluno->analises()
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();

        $campos = ['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'];
        $atual = $analises->get(0)?->only($campos) ?? array_fill_keys($campos, 0);
        $anterior = $analises->get(1)?->only($campos) ?? array_fill_keys($campos, 0);

        return response()->json([
            'labels' => [
                'Arremesso',
                'Passe',
                'Marcacao',
                'Bandeja',
                'Rebote',
                'Dominio de Bola',
            ],
            'anterior' => array_values($anterior),
            'atual' => array_values($atual),
        ]);
    }

    public function cvEsportivo($matricula): JsonResponse
    {
        abort_unless($this->podeVisualizarCvEsportivo(), 403, 'Acesso nao autorizado.');

        $aluno = $this->obterAlunoAutorizadoPorMatricula($matricula, true);
        $ultimaAnalise = $aluno->analises()
            ->latest('created_at')
            ->first();

        return response()->json([
            'identificacao' => [
                'nome' => $aluno->nome,
                'matricula' => $aluno->matricula,
                'projeto' => $aluno->instituicao?->nome,
                'tecnico_responsavel' => $aluno->user?->name,
                'idade' => $aluno->idade,
                'sexo' => $aluno->sexo,
                'telefone' => $aluno->telefone,
                'data_nascimento' => $aluno->data_nascimento?->toDateString(),
                'total_analises' => $aluno->analises()->count(),
                'ultima_analise' => $ultimaAnalise?->created_at?->toDateTimeString(),
            ],
            'tecnicos' => [
                'Arremesso' => $ultimaAnalise?->arremesso,
                'Passe' => $ultimaAnalise?->passe,
                'Marcacao' => $ultimaAnalise?->marcacao,
                'Bandeja' => $ultimaAnalise?->bandeja,
                'Rebote' => $ultimaAnalise?->rebote,
                'Dominio de Bola' => $ultimaAnalise?->dominio,
            ],
            'fisicos' => [
                'Potencia MMSS' => $ultimaAnalise?->potencia_mmss,
                'Capacidade Aerobica' => $ultimaAnalise?->capacidade_aerobica,
                'Agilidade' => $ultimaAnalise?->agilidade,
                'Flexibilidade' => $ultimaAnalise?->flexibilidade,
                'Potencia MMII' => $ultimaAnalise?->potencia_mmii,
                'Envergadura (cm)' => $ultimaAnalise?->envergadura_cm,
            ],
            'composicao' => [
                'Massa Corporal (kg)' => $ultimaAnalise?->massa_corporal_kg,
                'Gordura (%)' => $ultimaAnalise?->gordura_pct,
                'Massa Magra (%)' => $ultimaAnalise?->massa_magra_pct,
                'IMC' => $ultimaAnalise?->imc,
            ],
            'saude' => [
                'Problema de Saude' => $ultimaAnalise?->problema_saude,
                'Descricao do Problema' => $ultimaAnalise?->problema_saude_descricao,
                'Atestado Valido' => $ultimaAnalise?->atestado_valido,
                'Data do Atestado' => $ultimaAnalise?->data_atestado?->toDateString(),
                'Usa Medicacao' => $ultimaAnalise?->usa_medicacao,
            ],
        ]);
    }

    private function obterAlunoAutorizadoPorMatricula(string $matricula, bool $carregarRelacoes = false): Aluno
    {
        $query = Aluno::query()->where('matricula', $matricula);

        if ($carregarRelacoes) {
            $query->with(['instituicao:id,nome', 'user:id,name']);
        }

        if (Auth::check() && Auth::user()->is_admin) {
            return $query->firstOrFail();
        }

        $instituicaoId = session('aluno_instituicao_id');

        if (! $instituicaoId && Auth::check()) {
            $instituicaoId = Auth::user()->instituicao_id;
        }

        abort_unless($instituicaoId, 403, 'Acesso nao autorizado.');

        return $query->where('instituicao_id', $instituicaoId)->firstOrFail();
    }

    private function podeAcessarInstituicao(int $instituicaoId): bool
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return true;
        }

        if (session()->has('aluno_instituicao_id')) {
            return (int) session('aluno_instituicao_id') === $instituicaoId;
        }

        if (Auth::check()) {
            return (int) Auth::user()->instituicao_id === $instituicaoId;
        }

        return false;
    }

    private function podeVisualizarCvEsportivo(): bool
    {
        return Auth::check() && ! session()->has('aluno_instituicao_id');
    }
}
