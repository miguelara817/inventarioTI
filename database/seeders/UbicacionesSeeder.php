<?php

namespace Database\Seeders;

use App\Models\Ubicacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UbicacionesSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ubicacion::create([
            'nombre' => 'Oficina principal',
            'edificio' => 'HANSA',
            'piso' => '20',
        ]);
    }
}
