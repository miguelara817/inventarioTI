<?php

namespace Database\Seeders;

use App\Models\ActivoEstadoOperativo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivoEstadoOperativoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivoEstadoOperativo::create([
            'nombre' => 'Operativo',
        ]);
        ActivoEstadoOperativo::create([
            'nombre' => 'Operativo con observaciones',
        ]);
        ActivoEstadoOperativo::create([
            'nombre' => 'Con fallas',
        ]);
        ActivoEstadoOperativo::create([
            'nombre' => 'Fuera de servicio',
        ]);
        ActivoEstadoOperativo::create([
            'nombre' => 'No verificado',
        ]);
    }
}
