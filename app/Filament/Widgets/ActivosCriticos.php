<?php

namespace App\Filament\Widgets;

use App\Models\Activo;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ActivosCriticos extends TableWidget
{
    protected static ?int $sort = 8;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Activos que requieren atención')
            ->description('Activos con fallas o fuera de servicio.')
            ->query(fn (): Builder => Activo::query()
                ->with(['tipo', 'ubicacion', 'estadoOperativo'])
                ->whereHas('estadoOperativo', fn ($query) => $query->whereIn('nombre', ['Con fallas', 'Fuera de servicio']))
                ->orderBy('codigo_activo'))
            ->defaultPaginationPageOption(5)
            ->paginated([5, 10, 25])
            ->columns([
                TextColumn::make('codigo_activo')
                    ->label('Código')
                    ->searchable(),
                TextColumn::make('tipo.nombre')
                    ->label('Tipo'),
                TextColumn::make('ubicacion.nombre')
                    ->label('Ubicación'),
                TextColumn::make('estadoOperativo.nombre')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Operativo' => 'success',
                        'Operativo con observaciones' => 'info',
                        'Con fallas' => 'warning',
                        'Fuera de servicio' => 'danger',
                        default => 'gray',
                    }),
            ]);
    }
}
