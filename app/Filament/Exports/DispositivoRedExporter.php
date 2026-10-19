<?php

namespace App\Filament\Exports;

use App\Models\DispositivoRed;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class DispositivoRedExporter extends Exporter
{
    protected static ?string $model = DispositivoRed::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('activo.codigo_activo')
                ->label('Código de activo'),
            ExportColumn::make('hostname')
                ->label('Hostname'),
            ExportColumn::make('direccion_ip')
                ->label('Dirección IP'),
            ExportColumn::make('direccion_mac')
                ->label('Dirección MAC'),
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
