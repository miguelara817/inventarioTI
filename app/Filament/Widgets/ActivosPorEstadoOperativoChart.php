<?php

namespace App\Filament\Widgets;

use App\Models\Activo;
use App\Models\ActivoEstadoOperativo;
use Filament\Widgets\ChartWidget;

class ActivosPorEstadoOperativoChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Estado operativo';

    protected ?string $description = 'Salud del parque de activos según su estado operativo.';

    protected ?string $pollingInterval = null;

    /**
     * @var array<string, string>
     */
    private const COLORES = [
        'Operativo' => '#10b981',
        'Operativo con observaciones' => '#0ea5e9',
        'Con fallas' => '#f59e0b',
        'Fuera de servicio' => '#ef4444',
        'No verificado' => '#94a3b8',
        'Sin estado' => '#64748b',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $estados = ActivoEstadoOperativo::query()->orderBy('activo_estado_operativo_id')->pluck('nombre', 'activo_estado_operativo_id');

        $conteos = Activo::query()
            ->selectRaw('estado_operativo, count(*) as total')
            ->groupBy('estado_operativo')
            ->pluck('total', 'estado_operativo');

        $labels = [];
        $data = [];

        foreach ($estados as $id => $nombre) {
            $labels[] = $nombre;
            $data[] = (int) ($conteos[$id] ?? 0);
        }

        $sinEstado = Activo::query()->whereNull('estado_operativo')->count();

        if ($sinEstado > 0) {
            $labels[] = 'Sin estado';
            $data[] = $sinEstado;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Activos',
                    'data' => $data,
                    'backgroundColor' => array_map(
                        fn (string $label): string => self::COLORES[$label] ?? '#64748b',
                        $labels,
                    ),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
