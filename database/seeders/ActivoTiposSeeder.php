<?php

namespace Database\Seeders;

use App\Models\ActivoTipo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivoTiposSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivoTipo::create([
            'nombre' => 'PC',
        ]);
        ActivoTipo::create([
            'nombre' => 'Laptop',
        ]);
        ActivoTipo::create([
            'nombre' => 'Impresora',
        ]);
        ActivoTipo::create([
            'nombre' => 'Teclado',
        ]);
        ActivoTipo::create([
            'nombre' => 'Mouse',
        ]);
        ActivoTipo::create([
            'nombre' => 'Monitor',
        ]);
    }
}
