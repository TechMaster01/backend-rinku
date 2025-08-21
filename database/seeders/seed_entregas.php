<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Entregas;
use App\Models\Empleados;
use Carbon\Carbon;

class seed_entregas extends Seeder
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

        // JUAN CARLOS - 60 entregas en 6 días (01-07 al 06-07) = 10 entregas por día
        $fechaInicio = Carbon::create(2025, 7, 1);
        for ($i = 0; $i < 6; $i++) {
            Entregas::create([
                'empleado_id' => $juanCarlos->id,
                'fecha' => $fechaInicio->copy()->addDays($i)->format('Y-m-d'),
                'cantidad_entregas' => 10
            ]);
        }

        // MARÍA ELENA - 2000 entregas en 20 días (01-07 al 20-07) = 100 entregas por día
        $fechaInicio = Carbon::create(2025, 7, 1);
        for ($i = 0; $i < 20; $i++) {
            Entregas::create([
                'empleado_id' => $mariaElena->id,
                'fecha' => $fechaInicio->copy()->addDays($i)->format('Y-m-d'),
                'cantidad_entregas' => 100
            ]);
        }

        // PEDRO - 50 entregas SOLO en los 5 días que cubrió chofer = 10 entregas por día
        $diasPedroChofer = [
            '2025-07-03', // 3 de julio
            '2025-07-08', // 8 de julio
            '2025-07-12', // 12 de julio
            '2025-07-16', // 16 de julio
            '2025-07-20'  // 20 de julio
        ];
        
        foreach ($diasPedroChofer as $fecha) {
            Entregas::create([
                'empleado_id' => $pedro->id,
                'fecha' => $fecha,
                'cantidad_entregas' => 10
            ]);
        }
    }
}
