<?php

namespace App\Filament\Resources\Activos\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ActivosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('N.º')
                    ->rowIndex(),
                TextColumn::make('codigo_activo')
                    ->label('Código')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tipo.nombre')
                    ->label('Tipo')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('marca')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('modelo')
                    ->searchable()
                    ->copyable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('numero_serie')
                    ->label('Nº Serie')
                    ->copyable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('ubicacion.nombre')
                    ->label('Ubicación')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('personaAsignada.nombre')
                    ->label('Asignado a')
                    ->getStateUsing(fn ($record) => $record->personaAsignada ? $record->personaAsignada->nombre.' '.$record->personaAsignada->apellido : null)
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('estadoOperativo.nombre')
                    ->label('Estado Op.')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Operativo' => 'success',
                        'Operativo con observaciones' => 'info',
                        'Con fallas' => 'warning',
                        'Fuera de servicio' => 'danger',
                        'No verificado' => 'gray'
                    })
                    ->toggleable(),
                TextColumn::make('estadoAsignado.valor')
                    ->label('Estado Asignado.')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Asignado' => 'info',
                        'Disponible' => 'success',
                        'En mantenimiento' => 'warning',
                        'Dado de baja' => 'danger',
                    })
                    ->toggleable(),
                TextColumn::make('estadoFisico.valor')
                    ->label('Estado Asignado.')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Bueno' => 'success',
                        'Regular' => 'warning',
                        'Malo' => 'danger',
                        'No verificado' => 'gray',
                    })
                    ->toggleable(),
                TextColumn::make('fecha_registro')
                    ->label('Fecha de Registro')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // TrashedFilter::make(),
                SelectFilter::make('activo_tipo_id')
                    ->label('Tipo')
                    ->relationship('tipo', 'nombre'),
                SelectFilter::make('ubicacion_id')
                    ->label('Ubicación')
                    ->relationship('ubicacion', 'nombre'),
                SelectFilter::make('estado_operativo')
                    ->label('Estado Operativo')
                    ->relationship('estadoOperativo', 'nombre'),
            ])
            ->recordActions([
                // ActionGroup::make([
                //     ViewAction::make(),
                //     EditAction::make(),
                // ]),
                ViewAction::make()
                    ->iconButton(),
                EditAction::make()
                    ->slideOver()
                    ->iconButton(),
                DeleteAction::make()
                    ->iconButton()
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
