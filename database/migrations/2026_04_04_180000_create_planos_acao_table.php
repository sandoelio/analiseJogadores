<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planos_acao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('titulo', 120);
            $table->text('descricao')->nullable();
            $table->string('prioridade', 20)->default('media');
            $table->string('status', 20)->default('aberto');
            $table->date('prazo')->nullable();
            $table->date('concluido_em')->nullable();
            $table->timestamps();

            $table->index(['aluno_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planos_acao');
    }
};
