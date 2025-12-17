<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            // Adiciona após a coluna 'matricula' para manter ordem lógica
            $table->date('data_nascimento')->nullable()->after('matricula');
            $table->unsignedInteger('idade')->nullable()->after('data_nascimento');
            $table->enum('sexo', ['Masculino', 'Feminino'])->nullable()->after('idade');
        });
    }

    public function down(): void
    {
        Schema::table('alunos', function (Blueprint $table) {
            // Remove as colunas adicionadas
            $table->dropColumn(['data_nascimento', 'idade', 'sexo']);
        });
    }
};
