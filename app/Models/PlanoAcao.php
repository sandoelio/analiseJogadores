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
}
