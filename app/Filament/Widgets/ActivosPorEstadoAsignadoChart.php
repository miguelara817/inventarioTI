<?php

namespace App\Filament\Widgets;

use App\Models\Activo;
use App\Models\ActivoEstadoAsignado;
use Filament\Widgets\ChartWidget;

class ActivosPorEstadoAsignadoChart extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Estado de asignación';

    protected ?string $description = 'Activos asignados, disponibles, en mantenimiento o dados de baja.';

    protected ?string $pollingInterval = null;

    /**
     * @var array<string, string>
     */
    private const COLORES = [
        'Asignado' => '#0ea5e9',
        'Disponible' => '#10b981',
        'En mantenimiento' => '#f59e0b',
        'Dado de baja' => '#ef4444',
        'Sin estado' => '#64748b',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $estados = ActivoEstadoAsignado::query()->orderBy('activo_estado_asignado_id')->pluck('valor', 'activo_estado_asignado_id');

        $conteos = Activo::query()
            ->selectRaw('estado_asignado, count(*) as total')
            ->groupBy('estado_asignado')
            ->pluck('total', 'estado_asignado');

        $labels = [];
        $data = [];

        foreach ($estados as $id => $valor) {
            $labels[] = $valor;
            $data[] = (int) ($conteos[$id] ?? 0);
        }

        $sinEstado = Activo::query()->whereNull('estado_asignado')->count();

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
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
