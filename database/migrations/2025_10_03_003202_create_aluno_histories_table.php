<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunoHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('aluno_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignId('aluno_id')->constrained('alunos')->cascadeOnDelete();
            $table->string('evento', 50); // created, updated, analise_created, etc.
            $table->json('dados')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps(); // created_at = data do evento
            $table->index(['aluno_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('aluno_histories');
    }
}
