<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('analises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aluno_id')->constrained()->onDelete('cascade');
            $table->integer('arremesso')->unsigned();
            $table->integer('passe')->unsigned();
            $table->integer('marcacao')->unsigned();
            $table->integer('finalizacao')->unsigned();
            $table->integer('jogada')->unsigned();
            $table->integer('dominio')->unsigned();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('analises');
    }
};
