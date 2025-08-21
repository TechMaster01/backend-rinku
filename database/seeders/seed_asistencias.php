<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Asistencias;
use App\Models\Empleados;
use Carbon\Carbon;

class seed_asistencias extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los empleados por número de empleado
        $juanCarlos = Empleados::where('numero_empleado', 1001)->first();
        $mariaElena = Empleados::where('numero_empleado', 1002)->first();
        $pedro = Empleados::where('numero_empleado', 1003)->first();

        // Asistencias para Juan Carlos (01-07-2025 al 22-07-2025) - Sin cobertura
        $fechaInicio = Carbon::create(2025, 7, 1);
        $fechaFin = Carbon::create(2025, 7, 22);
        
        for ($fecha = $fechaInicio->copy(); $fecha->lte($fechaFin); $fecha->addDay()) {
            Asistencias::create([
                'empleado_id' => $juanCarlos->id,
                'fecha' => $fecha->format('Y-m-d'),
                'cubrio_turno' => false,
                'turno_cubierto' => null
            ]);
        }

        // Asistencias para María Elena (01-07-2025 al 30-07-2025) - Sin cobertura
        $fechaInicio = Carbon::create(2025, 7, 1);
        $fechaFin = Carbon::create(2025, 7, 30);
        
        for ($fecha = $fechaInicio->copy(); $fecha->lte($fechaFin); $fecha->addDay()) {
            Asistencias::create([
                'empleado_id' => $mariaElena->id,
                'fecha' => $fecha->format('Y-m-d'),
                'cubrio_turno' => false,
                'turno_cubierto' => null
            ]);
        }

        // Asistencias para Pedro (01-07-2025 al 22-07-2025) - Con algunas coberturas
        $fechaInicio = Carbon::create(2025, 7, 1);
        $fechaFin = Carbon::create(2025, 7, 22);
        
        // Días específicos donde Pedro cubrió turno de chofer
        $diasConCobertura = [
            '2025-07-03', // 3 de julio
            '2025-07-08', // 8 de julio
            '2025-07-12', // 12 de julio
            '2025-07-16', // 16 de julio
            '2025-07-20'  // 20 de julio
        ];
        
        for ($fecha = $fechaInicio->copy(); $fecha->lte($fechaFin); $fecha->addDay()) {
            $fechaStr = $fecha->format('Y-m-d');
            $cubrioTurno = in_array($fechaStr, $diasConCobertura);
            
            Asistencias::create([
                'empleado_id' => $pedro->id,
                'fecha' => $fechaStr,
                'cubrio_turno' => $cubrioTurno,
                'turno_cubierto' => $cubrioTurno ? 'chofer' : null
            ]);
        }
    }
}
