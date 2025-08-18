<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parametros_nomina', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('turno_hora')->default(8);
            $table->decimal('pago_hora', 10, 2)->default(0);
            $table->decimal('bono_entrega', 10, 2)->default(0);
            $table->decimal('bono_chofer', 10, 2)->default(0);
            $table->decimal('bono_cargador', 10, 2)->default(0);
            $table->decimal('vales', 5, 2)->default(0);
            $table->timestampsTz();
        });

        DB::table('parametros_nomina')->insert([
            'turno_hora'    => 8,
            'pago_hora'     => 30.00,
            'bono_entrega'  => 5.00,
            'bono_chofer'   => 10.00,
            'bono_cargador' => 5.00,
            'vales'         => 0.04,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros_nomina');
    }
};
