<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria a tabela de análises vinculadas aos alunos.
     */
    public function up(): void
    {
        Schema::create('analises', function (Blueprint $table) {
            $table->id();

            $table->foreignId('aluno_id')
                  ->constrained('alunos')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('arremesso');
            $table->unsignedTinyInteger('passe');
            $table->unsignedTinyInteger('marcacao');
            $table->unsignedTinyInteger('bandeja');
            $table->unsignedTinyInteger('jogada');
            $table->unsignedTinyInteger('dominio');

            $table->timestampsTz();

            // Índice para otimização de busca por aluno e ordem de criação
            $table->index(['aluno_id', 'created_at']);
        });
    }

    /**
     * Remove a tabela de análises.
     */
    public function down(): void
    {
        Schema::dropIfExists('analises');
    }
};
