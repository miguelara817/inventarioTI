<?php

namespace Database\Seeders;

use App\Models\Unidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnidadesSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'DIRECCION GENERAL EJECUTIVA',
            'AUDITORIA INTERNA',
            'GERENCIA ADMINISTRATIVA FINANCIERA',
            'GERENCIA DE PRODUCCION Y GESTION DE CALIDAD',
            'GERENCIA DE COMERCIO EXTERIOR Y COMERCIALIZACION',
            'JEFATURA DE SISTEMAS Y TECNOLOGIAS DE LA INFORMACION',
            'JEFATURA DE ADMINISTRACION Y RECURSOS HUMANOS',
            'JEFATURA DE FINANZAS',
            'JEFATURA DE UNIDAD JURIDICA',
            'JEFATURA DE COMERCIO EXTERIOR',
            'JEFATURA DE COMERCIALIZACION',
            'PLANTA PROCESADORA IVIRGARZAMA',
            'PLANTA PROCESADORA SHINAHOTA',
        ] as $nombre) {
            Unidad::create([
                'nombre' => $nombre,
            ]);
        }
    }
}
