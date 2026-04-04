<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aluno extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'matricula',
        'user_id',
        'instituicao_id',
        'data_nascimento',
        'idade',
        'sexo',
        'telefone',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'idade' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Instituição a que este aluno pertence.
     */
    public function instituicao(): BelongsTo
    {
        return $this->belongsTo(Instituicao::class);
    }

    /**
     * Usuário responsável por este aluno.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Análises associadas ao aluno.
     */
    public function analises(): HasMany
    {
        return $this->hasMany(Analise::class);
    }

    /**
     * Planos de acao vinculados ao atleta.
     */
    public function planosAcao(): HasMany
    {
        return $this->hasMany(PlanoAcao::class);
    }

    /**
     * Ultima analise registrada para o aluno.
     */
    public function ultimaAnalise(): HasOne
    {
        return $this->hasOne(Analise::class)->latestOfMany();
    }
    
    /** * Mutator para data_nascimento. * Calcula e preenche a idade automaticamente. */ 
    public function setDataNascimentoAttribute($value)
    {
        $this->attributes['data_nascimento'] = $value ? Carbon::parse($value)->toDateString() : null;
        if ($value) {
            $this->attributes['idade'] = Carbon::parse($value)->age;
        } else {
            $this->attributes['idade'] = null;
        }
    }

    /** * Accessor para idade (opcional). */ 
    public function getIdadeAttribute($value)
    {
        return $value;
    }

    /**
     * Media tecnica com base na ultima analise.
     */
    public function mediaTecnicaAtual(): ?float
    {
        $analise = $this->relationLoaded('ultimaAnalise')
            ? $this->ultimaAnalise
            : $this->ultimaAnalise()->first();

        if (! $analise) {
            return null;
        }

        $campos = ['arremesso', 'passe', 'marcacao', 'bandeja', 'rebote', 'dominio'];

        $valores = collect($campos)
            ->map(fn(string $campo) => $analise->{$campo})
            ->filter(fn($valor) => $valor !== null);

        return $valores->isEmpty() ? null : (float) $valores->avg();
    }

    /**
     * Retorna o semaforo operacional do atleta.
     */
    public function obterSemaforo(): array
    {
        $analise = $this->relationLoaded('ultimaAnalise')
            ? $this->ultimaAnalise
            : $this->ultimaAnalise()->first();

        $planos = $this->relationLoaded('planosAcao')
            ? $this->planosAcao
            : $this->planosAcao()->get();

        $totalAnalises = $this->getAttribute('analises_count');

        if ($totalAnalises === null) {
            $totalAnalises = $this->analises()->count();
        }

        $hoje = now()->startOfDay();
        $temPlanoAtrasado = $planos->contains(function ($plano) use ($hoje) {
            return in_array($plano->status, ['aberto', 'em_andamento'], true)
                && $plano->prazo
                && $plano->prazo->lt($hoje);
        });

        $saudePendente = $analise
            && (((bool) $analise->problema_saude && blank($analise->problema_saude_descricao))
                || ((bool) $analise->atestado_valido && ! $analise->data_atestado));

        if ($totalAnalises === 0 || $saudePendente || $temPlanoAtrasado) {
            return [
                'nivel' => 'vermelho',
                'rotulo' => 'Critico',
                'motivo' => $totalAnalises === 0
                    ? 'Sem analise'
                    : ($saudePendente ? 'Saude pendente' : 'Plano atrasado'),
            ];
        }

        $analiseDesatualizada = $analise && $analise->created_at->lt(now()->subDays(30));

        if (blank($this->telefone) || $analiseDesatualizada) {
            return [
                'nivel' => 'amarelo',
                'rotulo' => 'Atencao',
                'motivo' => blank($this->telefone) ? 'Sem telefone' : 'Analise desatualizada',
            ];
        }

        return [
            'nivel' => 'verde',
            'rotulo' => 'Em dia',
            'motivo' => 'Cadastro e analise em dia',
        ];
    }
}
