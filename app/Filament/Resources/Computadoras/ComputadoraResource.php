<?php

namespace App\Filament\Resources\Computadoras;

use App\Filament\Resources\Computadoras\Pages\CreateComputadora;
use App\Filament\Resources\Computadoras\Pages\EditComputadora;
use App\Filament\Resources\Computadoras\Pages\ListComputadoras;
use App\Filament\Resources\Computadoras\Schemas\ComputadoraForm;
use App\Filament\Resources\Computadoras\Tables\ComputadorasTable;
use App\Models\Computadora;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComputadoraResource extends Resource
{
    protected static ?string $model = Computadora::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ComputerDesktop;

    public static function form(Schema $schema): Schema
    {
        return ComputadoraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ComputadorasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComputadoras::route('/'),
            // 'create' => CreateComputadora::route('/create'),
            // 'edit' => EditComputadora::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
