<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Aluno;
use App\Models\AlunoHistory;
use DB;

class BackfillAlunoHistories extends Command
{
    protected $signature = 'aluno:backfill-histories {--batch=100}';
    protected $description = 'Backfill: cria registros em aluno_histories para alunos e análises existentes';

    public function handle()
    {
        $this->info('Iniciando backfill de aluno_histories...');

        // 1) Registra evento 'created' para cada aluno (a partir do created_at do aluno)
        Aluno::with('analises')->chunk((int) $this->option('batch'), function ($alunos) {
            foreach ($alunos as $aluno) {
                // verifica se já existe created com mesmo timestamp para evitar duplicata
                $exists = AlunoHistory::where('aluno_id', $aluno->id)
                    ->where('evento', 'created')
                    ->whereDate('created_at', $aluno->created_at->toDateString())
                    ->exists();

                if (!$exists) {
                    AlunoHistory::create([
                        'aluno_id' => $aluno->id,
                        'evento' => 'created',
                        'dados' => $aluno->only(['nome', 'email', 'telefone', 'data_nascimento']),
                        'changed_by' => null,
                        'created_at' => $aluno->created_at,
                        'updated_at' => $aluno->created_at,
                    ]);
                }

                // 2) Para cada análise (analises), criar evento 'analise_created'
                foreach ($aluno->analises as $analise) {
                    // prevenir duplicata com checagem por analise_id no JSON (ou por evento+timestamp)
                    $already = AlunoHistory::where('aluno_id', $aluno->id)
                        ->where('evento', 'analise_created')
                        ->whereJsonContains('dados->analise_id', $analise->id)
                        ->exists();

                    if ($already) continue;

                    $dados = [
                        'analise_id' => $analise->id,
                        'tecnicos' => $analise->only(['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio']),
                        'fisicos' => $analise->only(['envergadura', 'velocidade', 'agilidade', 'salto_horizontal', 'resistencia']),
                        'composicao' => $analise->only(['massa_magra_kg', 'massa_adiposa_kg', 'massa_magra_pct', 'massa_adiposa_pct', 'peso_residual_kg']),
                        'saude' => [
                            'problema_saude' => $analise->problema_saude ?? null,
                            'atestado_valido' => $analise->atestado_valido ?? null,
                            'usa_medicacao' => $analise->usa_medicacao ?? null,
                        ],
                    ];

                    AlunoHistory::create([
                        'aluno_id' => $aluno->id,
                        'evento' => 'analise_created',
                        'dados' => $dados,
                        'changed_by' => $analise->created_by ?? null, // ajuste conforme seu campo
                        'created_at' => $analise->created_at,
                        'updated_at' => $analise->created_at,
                    ]);
                }
            }
        });

        $this->info('Backfill concluído.');
        return 0;
    }
}
