<?php

namespace App\Filament\Resources\DispositivoReds\Schemas;

use App\Models\Activo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class DispositivoRedForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('activo_id')
                    ->relationship('activo', 'activo_id')
                    ->options(Activo::all()->pluck('codigo_activo', 'activo_id'))
                    ->unique(table: 'computadoras', column:'activo_id')
                    ->validationMessages([
                        'unique' => 'El activo ya esta registrado'
                    ])
                    ->searchable()
                    ->preload(),
                TextInput::make('hostname'),
                TextInput::make('direccion_ip'),
                TextInput::make('direccion_mac'),
            ]);
    }
}
