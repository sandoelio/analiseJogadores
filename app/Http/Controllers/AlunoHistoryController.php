<?php

namespace App\Http\Controllers;

use App\Models\Aluno;
use App\Models\AlunoHistory;
use App\Http\Controllers\Controller;

class AlunoHistoryController extends Controller
{
    public function timelineJson($matricula)
    {
        $aluno = Aluno::where('matricula', $matricula)->firstOrFail();

        $events = AlunoHistory::where('aluno_id', $aluno->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($e) {
                return [
                    'id' => $e->id,
                    'evento' => $e->evento,
                    'dados' => $e->dados,
                    'changed_by' => $e->user ? $e->user->name : null,
                    'created_at' => $e->created_at->toDateTimeString(),
                    // incluir aluno apenas para eventos do tipo created (facilita frontend)
                    'aluno' => $e->evento === 'created' ? [
                        'nome' => optional($e->aluno)->nome,
                        'matricula' => optional($e->aluno)->matricula,
                        'instituicao' => optional($e->aluno->instituicao)->nome ?? null,
                    ] : null,
                ];
            });

        return response()->json(['events' => $events]);
    }

    public function eventJson($id)
    {
        $event = AlunoHistory::with('user', 'aluno')->findOrFail($id);
        
        // preparar resposta com flags para o frontend
        return response()->json([
            'id' => $event->id,
            'evento' => $event->evento,
            'dados' => $event->dados,
            'changed_by' => $event->user ? $event->user->name : null,
            'created_at' => $event->created_at->toDateTimeString(),
            'aluno' => $event->aluno ? [
                'id' => $event->aluno->id,
                'nome' => $event->aluno->nome,
                'matricula' => $event->aluno->matricula ?? null,
                'instituicao' => $event->aluno->instituicao->nome ?? null, // ajuste se relação diferente
                'email' => $event->aluno->email ?? null,
            ] : null,
        ]);
    }
}
