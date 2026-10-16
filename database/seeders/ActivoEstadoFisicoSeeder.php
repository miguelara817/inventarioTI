<?php

namespace Database\Seeders;

use App\Models\ActivoEstadoFisico;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivoEstadoFisicoSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivoEstadoFisico::create([
            'valor' => 'Bueno',
        ]);
        ActivoEstadoFisico::create([
            'valor' => 'Regular',
        ]);
        ActivoEstadoFisico::create([
            'valor' => 'Malo',
        ]);
        ActivoEstadoFisico::create([
            'valor' => 'No verificado',
        ]);
    }
}
