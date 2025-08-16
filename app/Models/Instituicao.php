<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instituicao extends Model
{
    use HasFactory;

    protected $table = 'instituicoes';

    protected $fillable = [
        'nome',
    ];

    /**
     * Usuários vinculados a esta instituição.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Alunos vinculados a esta instituição.
     */
    public function alunos(): HasMany
    {
        return $this->hasMany(Aluno::class);
    }
}
