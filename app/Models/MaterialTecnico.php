<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialTecnico extends Model
{
    use HasFactory;

    protected $table = 'materiais_tecnicos';

    protected $fillable = [
        'titulo',
        'descricao',
        'arquivo_path',
        'arquivo_nome_original',
        'arquivo_mime',
        'arquivo_tamanho',
        'created_by',
    ];

    protected $casts = [
        'arquivo_tamanho' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
