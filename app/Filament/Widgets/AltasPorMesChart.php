<?php

namespace App\Filament\Widgets;

use App\Models\Activo;
use Filament\Widgets\ChartWidget;

class AltasPorMesChart extends ChartWidget
{
    protected static ?int $sort = 6;

    protected ?string $heading = 'Altas por mes';

    protected ?string $description = 'Activos registrados en los últimos 12 meses.';

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $inicio = now()->startOfMonth()->subMonths(11);

        $labels = [];
        $data = [];

        for ($i = 0; $i < 12; $i++) {
            $mes = $inicio->copy()->addMonths($i);

            $labels[] = $mes->locale('es')->translatedFormat('M Y');
            $data[] = Activo::query()
                ->whereYear('fecha_registro', $mes->year)
                ->whereMonth('fecha_registro', $mes->month)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Activos registrados',
                    'data' => $data,
                    'borderColor' => '#243652',
                    'backgroundColor' => 'rgba(36, 54, 82, 0.15)',
                    'pointBackgroundColor' => '#243652',
                    'fill' => true,
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
