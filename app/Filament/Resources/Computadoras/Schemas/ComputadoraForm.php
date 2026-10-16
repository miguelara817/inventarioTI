<?php

namespace App\Filament\Resources\Computadoras\Schemas;

use App\Models\Activo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ComputadoraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('activo id')
                    ->label('Activo')
                    ->relationship('activo', 'codigo_activo')
                    ->options(Activo::all()->pluck('codigo_activo', 'activo_id'))
                    ->searchable()
                    ->preload(),
                TextInput::make('hostname')
                    ->label('Hostname')
                    ->maxLength(150),
                TextInput::make('procesador')
                    ->label('Procesador')
                    ->maxLength(50),
                TextInput::make('memoria_ram')
                    ->label('Memoria RAM')
                    ->maxLength(50),
                TextInput::make('almacenamiento')
                    ->label('Almacenamiento')
                    ->maxLength(50),
                TextInput::make('video')
                    ->label('Tarjeta de Video')
                    ->maxLength(50),
                TextInput::make('direccion_ip')
                    ->label('Dirección IP')
                    ->maxLength(100),
                TextInput::make('direccion_mac')
                    ->label('Dirección MAC')
                    ->maxLength(100),
                TextInput::make('sistema_operativo')
                    ->label('Sistema Operativo')
                    ->maxLength(100),
                TextInput::make('versiopn_so')
                    ->label('Versión de sistema operativo')
                    ->maxLength(100)
            ]);
    }
}
