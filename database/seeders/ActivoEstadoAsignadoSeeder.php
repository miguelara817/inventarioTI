<?php

namespace Database\Seeders;

use App\Models\ActivoEstadoAsignado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivoEstadoAsignadoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivoEstadoAsignado::create([
            'valor' => 'Asignado',
        ]);
        ActivoEstadoAsignado::create([
            'valor' => 'Disponible',
        ]);
        ActivoEstadoAsignado::create([
            'valor' => 'En mantenimiento',
        ]);
        ActivoEstadoAsignado::create([
            'valor' => 'Dado de baja',
        ]);
    }
}
