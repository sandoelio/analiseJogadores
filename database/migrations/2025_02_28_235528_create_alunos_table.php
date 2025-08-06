<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('instituicao_id')
                  ->constrained('instituicoes')
                  ->cascadeOnDelete();

            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->string('nome', 100);
            $table->string('matricula', 50)->unique();

            $table->timestampsTz();
            $table->softDeletesTz();

            $table->index(['instituicao_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
