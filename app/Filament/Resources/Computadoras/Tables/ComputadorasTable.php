<?php

namespace App\Filament\Resources\Computadoras\Tables;

use App\Filament\Exports\ComputadoraExporter;
use App\Filament\Reports\ReportActions;
use App\Models\Computadora;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ComputadorasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('N.º')
                    ->rowIndex(),
                TextColumn::make('activo.codigo_activo')
                    ->label('Código de activo')
                    // ->copyable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('hostname')
                    ->label('Hostname')
                    // ->copyable()
                    ->toggleable(),
                TextColumn::make('procesador')
                    ->label('Procesador')
                    // ->copyable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('memoria_ram')
                    ->label('Memoria RAM')
                    // ->copyable()
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('almacenamiento')
                    ->label('Almacenamiento')
                    // ->copyable()
                    ->toggleable(),
                TextColumn::make('video')
                    ->label('Tarjeta de video')
                    // ->copyable()
                    ->toggleable(),
                TextColumn::make('sistema_operativo')
                    // ->copyable()
                    ->label('SO')
                    ->toggleable(),
                TextColumn::make('version_so')
                    ->label('Versio SO')
                    // ->copyable()
                    ->toggleable(),
            ])
            ->filters([
                // TrashedFilter::make(),
            ])
            ->headerActions([
                ReportActions::make(
                    exporter: ComputadoraExporter::class,
                    title: 'Computadoras',
                    columns: [
                        'Código de activo' => fn (Computadora $computadora): ?string => $computadora->activo?->codigo_activo,
                        'Hostname' => fn (Computadora $computadora): ?string => $computadora->hostname,
                        'Procesador' => fn (Computadora $computadora): ?string => $computadora->procesador,
                        'Memoria RAM' => fn (Computadora $computadora): ?string => $computadora->memoria_ram,
                        'Almacenamiento' => fn (Computadora $computadora): ?string => $computadora->almacenamiento,
                        'Tarjeta de video' => fn (Computadora $computadora): ?string => $computadora->video,
                        'Dirección IP' => fn (Computadora $computadora): ?string => $computadora->direccion_ip,
                        'Dirección MAC' => fn (Computadora $computadora): ?string => $computadora->direccion_mac,
                        'Sistema operativo' => fn (Computadora $computadora): ?string => $computadora->sistema_operativo,
                        'Versión del SO' => fn (Computadora $computadora): ?string => $computadora->version_so,
                    ],
                    with: ['activo'],
                ),
            ])
            ->recordActions([
                // ActionGroup::make([
                //     ViewAction::make(),
                //     EditAction::make(),
                // ]),
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
