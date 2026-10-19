<?php

namespace App\Filament\Widgets;

use App\Models\Activo;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UltimosActivosRegistrados extends TableWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 1;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Últimos activos registrados')
            ->query(fn (): Builder => Activo::query()
                ->with(['tipo', 'estadoOperativo'])
                ->latest('fecha_registro')
                ->limit(5))
            ->paginated(false)
            ->columns([
                TextColumn::make('codigo_activo')
                    ->label('Código')
                    ->searchable(),
                TextColumn::make('tipo.nombre')
                    ->label('Tipo'),
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
                TextColumn::make('fecha_registro')
                    ->label('Registrado')
                    ->dateTime('d/m/Y'),
            ]);
    }
}
