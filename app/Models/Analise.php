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
        'bandeja',
        'rebote',
        'dominio',
        'envergadura',
        'velocidade',
        'agilidade',
        'salto_horizontal',
        'resistencia',
        'massa_magra_kg',
        'massa_adiposa_kg',
        'massa_magra_pct',
        'massa_adiposa_pct',
        'peso_residual_kg',
        'problema_saude',
        'atestado_valido',
        'usa_medicacao',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'arremesso'   => 'integer',
        'passe'       => 'integer',
        'marcacao'    => 'integer',
        'bandeja'     => 'integer',
        'rebote'      => 'integer',
        'dominio'     => 'integer',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
        'problema_saude' => 'boolean',
        'atestado_valido' => 'boolean',
        'usa_medicacao' => 'boolean',
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
