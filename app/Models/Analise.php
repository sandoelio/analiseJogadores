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
        // Técnicos 
        'arremesso',
        'passe',
        'marcacao',
        'bandeja',
        'rebote',
        'dominio',
        // Físicos (renomeados) 
        'potencia_mmss',
        'agilidade',
        'capacidade_aerobica',
        'flexibilidade',
        'potencia_mmii',
        // Corporal (renomeados) 
        'massa_corporal_kg',
        'gordura_pct',
        'massa_magra_pct',
        'envergadura_cm',
        'imc',
        // Saúde 
        'problema_saude',
        'atestado_valido',
        'usa_medicacao',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        // Técnicos
        'arremesso' => 'integer',
        'passe' => 'integer',
        'marcacao' => 'integer',
        'bandeja' => 'integer',
        'rebote' => 'integer',
        'dominio' => 'integer',
        // Físicos 
        'potencia_mmss' => 'float',
        'agilidade' => 'float',
        'capacidade_aerobica' => 'float',
        'flexibilidade' => 'float',
        'potencia_mmii' => 'float',
        // Corporal 
        'massa_corporal_kg' => 'float',
        'gordura_pct' => 'float',
        'massa_magra_pct' => 'float',
        'envergadura_cm' => 'float',
        'imc' => 'float',
        // Saúde 
        'problema_saude' => 'boolean',
        'atestado_valido' => 'boolean',
        'usa_medicacao' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
