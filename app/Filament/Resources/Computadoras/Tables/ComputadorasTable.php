<?php

namespace App\Filament\Resources\Computadoras\Tables;

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
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('hostname')
                    ->label('Hostname')
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('procesador')
                    ->label('Procesador')
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('memoria_ram')
                    ->label('Memoria RAM')
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('almacenamiento')
                    ->label('Almacenamiento')
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('video')
                    ->label('Tarjeta de video')
                    ->copyable()
                    ->toggleable(),
                TextColumn::make('sistema_operativo')
                    ->copyable()
                    ->label('SO')
                    ->toggleable(),
                TextColumn::make('version_so')
                    ->label('Versio SO')
                    ->copyable()
                    ->toggleable(),
            ])
            ->filters([
                // TrashedFilter::make(),
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
                    ->iconButton()
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
