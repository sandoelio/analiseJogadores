<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
}
