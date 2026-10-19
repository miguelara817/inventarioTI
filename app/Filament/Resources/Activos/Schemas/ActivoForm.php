<?php

namespace App\Filament\Resources\Activos\Schemas;

use App\Models\ActivoEstadoAsignado;
use App\Models\ActivoEstadoFisico;
use App\Models\ActivoEstadoOperativo;
use App\Models\ActivoTipo;
use App\Models\Ubicacion;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ActivoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigo_activo')
                    ->label('Código de Activo')
                    ->required()
                    ->maxLength(100)
                    ->alphaDash(),
                Select::make('activo_tipo_id')
                    ->label('Tipo de Activo')
                    ->relationship('tipo', 'nombre')
                    ->options(ActivoTipo::all()->pluck('nombre', 'activo_tipos_id'))
                    ->searchable()
                    ->preload(),
                TextInput::make('marca')
                    ->maxLength(100),
                TextInput::make('modelo')
                    ->maxLength(150),
                TextInput::make('numero_serie')
                    ->label('Número de Serie')
                    ->maxLength(100),
                Select::make('ubicacion_id')
                    ->label('Ubicación')
                    ->relationship('ubicacion', 'nombre')
                    ->options(Ubicacion::all()->pluck('nombre', 'ubicacion_id'))
                    ->searchable()
                    ->preload(),
                Select::make('estado_asignado')
                    ->label('Estado de Asignación')
                    ->relationship('estadoAsignado', 'valor')
                    ->options(ActivoEstadoAsignado::all()->pluck('valor', 'activo_estado_asignado_id'))
                    ->searchable()
                    ->preload(),
                Select::make('estado_operativo')
                    ->label('Estado Operativo')
                    ->relationship('estadoOperativo', 'nombre')
                    ->options(ActivoEstadoOperativo::all()->pluck('nombre', 'activo_estado_operativo_id'))
                    ->searchable()
                    ->preload(),
                Select::make('estado_fisico')
                    ->label('Estado Físico')
                    ->relationship('estadoFisico', 'valor')
                    ->options(ActivoEstadoFisico::all()->pluck('valor', 'activo_estado_fisico_id'))
                    ->searchable()
                    ->preload(),
                Select::make('persona_asignada')
                    ->label('Persona Asignada')
                    ->relationship('personaAsignada', 'nombre')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->nombre.' '.$record->apellido)
                    ->searchable(['nombre', 'apellido'])
                    ->preload(),
                DateTimePicker::make('fecha_registro')
                    ->default(now()),
                // RichEditor::make(),
                Textarea::make('observaciones')
                    ->rows(3)
                    ->columnSpanFull(),
                // FileUpload::make('image')
                //     ->label('Imagen del activo')
                //     ->disk('public')
                //     ->directory('activos')
                //     ->image()
                //     ->acceptedFileTypes(['image/*'])
                //     ->maxSize(2048),
            ]);
    }
}
