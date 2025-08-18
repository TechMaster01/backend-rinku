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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade')->onUpdate('cascade');
            $table->date('fecha');
            $table->boolean('cubrio_turno')->default(false);
            $table->enum('turno_cubierto', ['chofer', 'cargador'])->nullable();
            
            $table->timestampsTz();
            $table->unique(['empleado_id', 'fecha'], 'unique_empleado_fecha_asistencias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
