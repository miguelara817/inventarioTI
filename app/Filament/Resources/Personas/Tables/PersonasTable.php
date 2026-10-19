<?php

namespace App\Filament\Resources\Personas\Tables;

use App\Filament\Exports\PersonaExporter;
use App\Filament\Reports\ReportActions;
use App\Models\Persona;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class PersonasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('N.º')
                    ->rowIndex(),
                TextColumn::make('nombre')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('apellido')
                    ->label('Apellido')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('Correo Institucional')
                    ->searchable(),
                TextColumn::make('unidad.nombre')
                    ->label('Unidad')
                    // ->searchable()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    // ->searchable()
                    ->sortable(),
            ])
            ->filters([
                // TrashedFilter::make(),
                SelectFilter::make('unidad.nombre')
                    ->label('Unidad')
                    ->relationship('unidad', 'nombre'),
                TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos'),

            ])
            ->headerActions([
                ReportActions::make(
                    exporter: PersonaExporter::class,
                    title: 'Personas',
                    columns: [
                        'Nombre' => fn (Persona $persona): ?string => $persona->nombre,
                        'Apellido' => fn (Persona $persona): ?string => $persona->apellido,
                        'Correo institucional' => fn (Persona $persona): ?string => $persona->email,
                        'Unidad' => fn (Persona $persona): ?string => $persona->unidad?->nombre,
                        'Activo' => fn (Persona $persona): string => $persona->is_active ? 'Sí' : 'No',
                    ],
                    with: ['unidad'],
                ),
            ])
            ->recordActions([
                // ActionGroup::make([
                //     ViewAction::make(),
                //     EditAction::make(),
                //     DeleteAction::make()
                // ])
                ViewAction::make()
                    ->iconButton(),
                EditAction::make()
                    ->iconButton(),
                DeleteAction::make()
                    ->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->recordUrl(null)
            ->recordAction(null);
    }
}
