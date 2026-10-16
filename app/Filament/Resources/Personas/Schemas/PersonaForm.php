<?php

namespace App\Filament\Resources\Personas\Schemas;

use App\Models\Unidad;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PersonaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(50),
                TextInput::make('apellido')
                    ->label('Apellido')
                    ->required()
                    ->maxLength(50),
                Select::make('unidad_id')
                    ->label('Unidad')
                    ->relationship('unidad', 'nombre')
                    ->options(Unidad::all()->pluck('nombre', 'unidad_id'))
                    ->searchable()
                    ->preload(),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true)
            ]);
    }
}
