<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('analises', function (Blueprint $table) {
            $table->float('envergadura')->nullable();
            $table->float('velocidade')->nullable();
            $table->float('agilidade')->nullable();
            $table->float('salto_horizontal')->nullable();
            $table->float('resistencia')->nullable();

            $table->float('massa_magra_kg')->nullable();
            $table->float('massa_adiposa_kg')->nullable();
            $table->float('massa_magra_pct')->nullable();
            $table->float('massa_adiposa_pct')->nullable();
            $table->float('peso_residual_kg')->nullable();
            $table->boolean('problema_saude')->nullable();
            $table->boolean('atestado_valido')->nullable();
            $table->boolean('usa_medicacao')->nullable();
            // $table->timestamp('created_at')->nullable();
            // $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('analises', function (Blueprint $table) {
            //
        });
    }
};
