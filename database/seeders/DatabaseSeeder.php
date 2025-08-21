<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Aquí puedes agregar tus seeders
        $this->call([
            seed_empleados::class,
            seed_entregas::class,
            seed_asistencias::class
        ]);
    }
}
