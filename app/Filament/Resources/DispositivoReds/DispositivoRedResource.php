<?php

namespace App\Filament\Resources\DispositivoReds;

use App\Filament\Resources\DispositivoReds\Pages\CreateDispositivoRed;
use App\Filament\Resources\DispositivoReds\Pages\EditDispositivoRed;
use App\Filament\Resources\DispositivoReds\Pages\ListDispositivoReds;
use App\Filament\Resources\DispositivoReds\Schemas\DispositivoRedForm;
use App\Filament\Resources\DispositivoReds\Tables\DispositivoRedsTable;
use App\Models\DispositivoRed;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class DispositivoRedResource extends Resource
{
    protected static ?string $navigationLabel = 'Dispositivos de Red';

    protected static ?string $label = 'Dispositivo de Red';

    protected static ?string $pluralLabel = 'Dispositivos de Red';

    protected static ?string $slug = 'dispositivos-red';

    protected static string|UnitEnum|null $navigationGroup = 'Activos';
    protected static ?string $model = DispositivoRed::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Printer;

    protected static ?string $recordTitleAttribute = 'DispositivoRed';

    public static function form(Schema $schema): Schema
    {
        return DispositivoRedForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DispositivoRedsTable::configure($table);
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
            'index' => ListDispositivoReds::route('/'),
            // 'create' => CreateDispositivoRed::route('/create'),
            // 'edit' => EditDispositivoRed::route('/{record}/edit'),
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
