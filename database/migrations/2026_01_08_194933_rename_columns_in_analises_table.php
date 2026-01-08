<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('analises', function (Blueprint $table) {
            // Físicos
            $table->renameColumn('envergadura', 'potencia_mmss');
            // agilidade permanece
            $table->renameColumn('velocidade', 'capacidade_aerobica');
            $table->renameColumn('salto_horizontal', 'flexibilidade');
            $table->renameColumn('resistencia', 'potencia_mmii');

            // Corporal
            $table->renameColumn('massa_magra_kg', 'massa_corporal_kg');
            $table->renameColumn('massa_adiposa_pct', 'gordura_pct');
            // massa_magra_pct permanece
            $table->renameColumn('massa_adiposa_kg', 'envergadura_cm');
            $table->renameColumn('peso_residual_kg', 'imc');
        });
    }

    public function down(): void
    {
        Schema::table('analises', function (Blueprint $table) {
            // Reverte os nomes
            $table->renameColumn('potencia_mmss', 'envergadura');
            $table->renameColumn('capacidade_aerobica', 'velocidade');
            $table->renameColumn('flexibilidade', 'salto_horizontal');
            $table->renameColumn('potencia_mmii', 'resistencia');

            $table->renameColumn('massa_corporal_kg', 'massa_magra_kg');
            $table->renameColumn('gordura_pct', 'massa_adiposa_pct');
            $table->renameColumn('envergadura_cm', 'massa_adiposa_kg');
            $table->renameColumn('imc', 'peso_residual_kg');
        });
    }
};
