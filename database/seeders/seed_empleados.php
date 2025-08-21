<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Empleados;

class seed_empleados extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $empleados = [
            [
                'numero_empleado' => 1001,
                'nombres' => 'Juan Carlos',
                'apellido_paterno' => 'García',
                'apellido_materno' => 'López',
                'rol' => 'chofer',
                'tipo_empleado' => 'interno'
            ],
            [
                'numero_empleado' => 1002,
                'nombres' => 'María Elena',
                'apellido_paterno' => 'Rodríguez',
                'apellido_materno' => 'Martínez',
                'rol' => 'chofer',
                'tipo_empleado' => 'interno'
            ],
            [
                'numero_empleado' => 1003,
                'nombres' => 'Pedro',
                'apellido_paterno' => 'Hernández',
                'apellido_materno' => 'Sánchez',
                'rol' => 'auxiliar',
                'tipo_empleado' => 'subcontratado'
            ]
        ];

        foreach ($empleados as $empleado) {
            Empleados::create($empleado);
        }
    }
}
