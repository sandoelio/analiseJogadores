<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analise extends Model {
    use HasFactory;

    protected $fillable = ['aluno_id', 'arremesso', 'passe', 'marcacao', 'finalizacao', 'jogada', "dominio"];

    public function aluno() {
        return $this->belongsTo(Aluno::class);
    }
}
