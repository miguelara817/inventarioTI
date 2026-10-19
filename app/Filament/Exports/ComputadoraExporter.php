<?php

namespace App\Filament\Exports;

use App\Models\Computadora;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ComputadoraExporter extends Exporter
{
    protected static ?string $model = Computadora::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('activo.codigo_activo')
                ->label('Código de activo'),
            ExportColumn::make('hostname')
                ->label('Hostname'),
            ExportColumn::make('procesador')
                ->label('Procesador'),
            ExportColumn::make('memoria_ram')
                ->label('Memoria RAM'),
            ExportColumn::make('almacenamiento')
                ->label('Almacenamiento'),
            ExportColumn::make('video')
                ->label('Tarjeta de video'),
            ExportColumn::make('direccion_ip')
                ->label('Dirección IP'),
            ExportColumn::make('direccion_mac')
                ->label('Dirección MAC'),
            ExportColumn::make('sistema_operativo')
                ->label('Sistema operativo'),
            ExportColumn::make('version_so')
                ->label('Versión del SO'),
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
