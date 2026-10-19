<?php

namespace App\Filament\Exports;

use App\Models\Persona;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PersonaExporter extends Exporter
{
    protected static ?string $model = Persona::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nombre')
                ->label('Nombre'),
            ExportColumn::make('apellido')
                ->label('Apellido'),
            ExportColumn::make('email')
                ->label('Correo institucional'),
            ExportColumn::make('unidad.nombre')
                ->label('Unidad'),
            ExportColumn::make('is_active')
                ->label('Activo')
                ->formatStateUsing(fn ($state): string => $state ? 'Sí' : 'No'),
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
