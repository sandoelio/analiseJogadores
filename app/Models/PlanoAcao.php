<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanoAcao extends Model
{
    use HasFactory;

    protected $table = 'planos_acao';

    protected $fillable = [
        'aluno_id',
        'user_id',
        'titulo',
        'descricao',
        'prioridade',
        'status',
        'prazo',
        'concluido_em',
    ];

    protected $casts = [
        'prazo' => 'date',
        'concluido_em' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function estaVencido(): bool
    {
        return $this->status !== 'concluido'
            && $this->prazo !== null
            && $this->prazo->lt(now()->startOfDay());
    }

    public function obterStatusAtual(): string
    {
        return $this->estaVencido() ? 'vencido' : $this->status;
    }

    public function obterRotuloStatus(): string
    {
        return match ($this->obterStatusAtual()) {
            'em_andamento' => 'Em andamento',
            'concluido' => 'Concluido',
            'vencido' => 'Vencido',
            default => 'Aberto',
        };
    }
}
