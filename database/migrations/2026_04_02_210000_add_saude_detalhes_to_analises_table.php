<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('analises', function (Blueprint $table) {
            $table->string('problema_saude_descricao', 255)->nullable();
            $table->date('data_atestado')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('analises', function (Blueprint $table) {
            $table->dropColumn(['problema_saude_descricao', 'data_atestado']);
        });
    }
};
