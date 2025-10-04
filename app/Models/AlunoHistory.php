<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlunoHistory extends Model
{
    protected $fillable = ['aluno_id', 'evento', 'dados', 'changed_by'];
    protected $casts = ['dados' => 'array'];

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}

