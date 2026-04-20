<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlanoAcaoStoreRequest;
use App\Http\Requests\PlanoAcaoUpdateRequest;
use App\Models\Aluno;
use App\Models\PlanoAcao;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TecnicoMonitoramentoController extends Controller
{
    public function ranking(Request $request)
    {
        $usuario = Auth::user();

        $this->atualizarPlanosVencidosDaInstituicao((int) $usuario->instituicao_id);

        $alunos = Aluno::query()
            ->with(['ultimaAnalise', 'planosAcao'])
            ->withCount('analises')
            ->where('instituicao_id', $usuario->instituicao_id)
            ->when($request->filled('sexo'), fn($query) => $query->where('sexo', $request->string('sexo')))
            ->when($request->filled('idade'), fn($query) => $query->where('idade', (int) $request->integer('idade')))
            ->orderBy('nome')
            ->get();

        $ranking = $alunos
            ->map(function (Aluno $aluno) {
                $media = $aluno->mediaTecnicaAtual();
                $semaforo = $aluno->obterSemaforo();

                return [
                    'aluno' => $aluno,
                    'media_tecnica' => $media,
                    'media_tecnica_formatada' => $media !== null ? number_format($media, 1, ',', '.') : '--',
                    'semaforo' => $semaforo,
                    'planos_abertos' => $aluno->planosAcao->whereIn('status', ['aberto', 'em_andamento', 'vencido'])->count(),
                ];
            })
            ->filter(fn(array $item) => $item['media_tecnica'] !== null)
            ->sortByDesc('media_tecnica')
            ->values()
            ->map(function (array $item, int $indice) {
                $item['posicao'] = $indice + 1;
                return $item;
            });

        $paginaAtual = LengthAwarePaginator::resolveCurrentPage();
        $porPagina = 3;
        $ranking = new LengthAwarePaginator(
            $ranking->forPage($paginaAtual, $porPagina)->values(),
            $ranking->count(),
            $porPagina,
            $paginaAtual,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        $idades = Aluno::query()
            ->where('instituicao_id', $usuario->instituicao_id)
            ->pluck('idade')
            ->filter(fn($idade) => $idade !== null)
            ->map(fn($idade) => (int) $idade)
            ->unique()
            ->sort()
            ->values();

        return view('tecnico.monitoramento.ranking', [
            'instituicao' => $usuario->instituicao,
            'ranking' => $ranking,
            'idades' => $idades,
            'sexoSelecionado' => (string) $request->string('sexo'),
            'idadeSelecionada' => $request->filled('idade') ? (int) $request->integer('idade') : null,
        ]);
    }

    public function plano(Aluno $aluno)
    {
        $aluno = $this->obterAlunoDaInstituicao($aluno->id);

        $this->atualizarPlanosVencidosDoAluno($aluno->id);

        $aluno->load(['ultimaAnalise', 'planosAcao' => function ($query) {
            $query->orderByRaw("CASE WHEN status = 'vencido' THEN 0 WHEN status = 'concluido' THEN 2 ELSE 1 END")
                ->orderByRaw('CASE WHEN prazo IS NULL THEN 1 ELSE 0 END')
                ->orderBy('prazo')
                ->orderByDesc('created_at');
        }])->loadCount('analises');

        $planosVencidos = $aluno->planosAcao->where('status', 'vencido')->count();

        return view('tecnico.monitoramento.plano', [
            'aluno' => $aluno,
            'semaforo' => $aluno->obterSemaforo(),
            'mediaTecnica' => $aluno->mediaTecnicaAtual(),
            'planosVencidos' => $planosVencidos,
        ]);
    }

    public function storePlano(PlanoAcaoStoreRequest $request, Aluno $aluno)
    {
        $aluno = $this->obterAlunoDaInstituicao($aluno->id);
        $dados = $request->validated();
        $dados = $this->normalizarDadosPlano($dados);

        $dados['aluno_id'] = $aluno->id;
        $dados['user_id'] = Auth::id();

        PlanoAcao::create($dados);

        return redirect()
            ->route('tecnico.plano.show', $aluno)
            ->with('success', 'Plano de acao cadastrado com sucesso.');
    }

    public function updatePlano(PlanoAcaoUpdateRequest $request, PlanoAcao $plano)
    {
        $plano = $this->obterPlanoDaInstituicao($plano->id);
        $dados = $request->validated();
        $dados = $this->normalizarDadosPlano($dados);

        $plano->update($dados);

        return redirect()
            ->route('tecnico.plano.show', $plano->aluno_id)
            ->with('success', 'Plano de acao atualizado com sucesso.');
    }

    public function destroyPlano(PlanoAcao $plano)
    {
        $plano = $this->obterPlanoDaInstituicao($plano->id);
        $alunoId = $plano->aluno_id;
        $plano->delete();

        return redirect()
            ->route('tecnico.plano.show', $alunoId)
            ->with('success', 'Plano de acao removido com sucesso.');
    }

    private function obterAlunoDaInstituicao(int $alunoId): Aluno
    {
        return Aluno::query()
            ->where('instituicao_id', Auth::user()->instituicao_id)
            ->findOrFail($alunoId);
    }

    private function obterPlanoDaInstituicao(int $planoId): PlanoAcao
    {
        return PlanoAcao::query()
            ->whereHas('aluno', function ($query) {
                $query->where('instituicao_id', Auth::user()->instituicao_id);
            })
            ->with('aluno')
            ->findOrFail($planoId);
    }

    private function atualizarPlanosVencidosDaInstituicao(int $instituicaoId): void
    {
        PlanoAcao::query()
            ->whereIn('status', ['aberto', 'em_andamento'])
            ->whereDate('prazo', '<', now()->toDateString())
            ->whereHas('aluno', function ($query) use ($instituicaoId) {
                $query->where('instituicao_id', $instituicaoId);
            })
            ->update([
                'status' => 'vencido',
                'updated_at' => now(),
            ]);
    }

    private function atualizarPlanosVencidosDoAluno(int $alunoId): void
    {
        PlanoAcao::query()
            ->where('aluno_id', $alunoId)
            ->whereIn('status', ['aberto', 'em_andamento'])
            ->whereDate('prazo', '<', now()->toDateString())
            ->update([
                'status' => 'vencido',
                'updated_at' => now(),
            ]);
    }

    private function normalizarDadosPlano(array $dados): array
    {
        $prazo = ! empty($dados['prazo']) ? Carbon::parse($dados['prazo'])->startOfDay() : null;

        if (($dados['status'] ?? null) === 'concluido') {
            $dados['concluido_em'] = now()->toDateString();
            return $dados;
        }

        $dados['concluido_em'] = null;

        if ($prazo && $prazo->lt(now()->startOfDay())) {
            $dados['status'] = 'vencido';
        } elseif (($dados['status'] ?? null) === 'vencido') {
            $dados['status'] = 'aberto';
        }

        return $dados;
    }
}
