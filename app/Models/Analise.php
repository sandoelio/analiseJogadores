<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Analise extends Model
{
    use HasFactory;

    protected $fillable = [
        'aluno_id',
        'arremesso',
        'passe',
        'marcacao',
        'finalizacao',
        'jogada',
        'dominio',
    ];

    protected $casts = [
        'arremesso'   => 'integer',
        'passe'       => 'integer',
        'marcacao'    => 'integer',
        'finalizacao' => 'integer',
        'jogada'      => 'integer',
        'dominio'     => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /**
     * Cada análise pertence a um aluno.
     */
    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    /**
     * Scope para retornar as últimas N análises de forma decrescente.
     */
    public function scopeUltimas(Builder $query, int $limit = 2): Builder
    {
        return $query->orderBy('created_at', 'desc')
                     ->limit($limit);
    }

    /**
     * Scope para análises de um aluno específico.
     */
    public function scopeLatestByAluno(Builder $query, int $alunoId): Builder
    {
        return $query->where('aluno_id', $alunoId)
                     ->orderBy('created_at', 'desc');
    }
}
