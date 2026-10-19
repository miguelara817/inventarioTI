<?php

namespace App\Filament\Exports;

use App\Models\Activo;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Carbon;

class ActivoExporter extends Exporter
{
    protected static ?string $model = Activo::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('codigo_activo')
                ->label('Código'),
            ExportColumn::make('tipo.nombre')
                ->label('Tipo'),
            ExportColumn::make('marca')
                ->label('Marca'),
            ExportColumn::make('modelo')
                ->label('Modelo'),
            ExportColumn::make('numero_serie')
                ->label('N.º de serie'),
            ExportColumn::make('ubicacion.nombre')
                ->label('Ubicación'),
            ExportColumn::make('persona_asignada')
                ->label('Asignado a')
                ->formatStateUsing(fn ($state, Activo $record): ?string => $record->personaAsignada
                    ? trim("{$record->personaAsignada->nombre} {$record->personaAsignada->apellido}")
                    : null),
            ExportColumn::make('estadoOperativo.nombre')
                ->label('Estado operativo'),
            ExportColumn::make('estadoAsignado.valor')
                ->label('Estado asignado'),
            ExportColumn::make('estadoFisico.valor')
                ->label('Estado físico'),
            ExportColumn::make('fecha_registro')
                ->label('Fecha de registro')
                ->formatStateUsing(fn ($state): ?string => filled($state)
                    ? Carbon::parse($state)->format('d/m/Y H:i')
                    : null),
            ExportColumn::make('observaciones')
                ->label('Observaciones'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = "El reporte se generó correctamente con {$export->successful_rows} registros.";

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= " {$failedRowsCount} registros no se pudieron exportar.";
        }

        return $body;
    }
}
