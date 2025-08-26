<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Instituicao extends Model
{
    use HasFactory;

    protected $table = 'instituicoes';

    protected $fillable = [
        'nome',
        'athlete_email',
        'athlete_password',
    ];

    /**
     * Não exibe athlete_password em arrays/JSON
     */
    protected $hidden = [
        'athlete_password',
    ];

    /**
     * Sempre que atribuir athlete_password,
     * o valor é automaticamente hasheado.
     */
    public function setAthletePasswordAttribute($value)
    {
        // caso já esteja hasheado, não re‐hashea
        if (! Hash::needsRehash($value)) {
            $this->attributes['athlete_password'] = $value;
        } else {
            $this->attributes['athlete_password'] = Hash::make($value);
        }
    }

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
