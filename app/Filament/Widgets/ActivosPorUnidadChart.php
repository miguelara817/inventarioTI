<?php

namespace App\Filament\Widgets;

use App\Models\Activo;
use App\Models\Unidad;
use Filament\Widgets\ChartWidget;

class ActivosPorUnidadChart extends ChartWidget
{
    protected static ?int $sort = 5;

    protected ?string $heading = 'Activos por unidad organizativa';

    protected ?string $description = 'Activos agrupados por la unidad de la persona responsable.';

    protected ?string $pollingInterval = null;

    /**
     * Paleta usada para pintar cada barra del gráfico.
     *
     * @var array<int, string>
     */
    private const COLORES = [
        '#243652',
        '#0ea5e9',
        '#10b981',
        '#f59e0b',
        '#ef4444',
        '#8b5cf6',
        '#14b8a6',
        '#64748b',
    ];

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        $unidades = Unidad::query()->orderBy('nombre')->pluck('nombre', 'unidad_id');

        $conteos = Activo::query()
            ->join('personas', 'personas.persona_id', '=', 'activos.persona_asignada')
            ->join('unidades', 'unidades.unidad_id', '=', 'personas.unidad_id')
            ->whereNull('personas.deleted_at')
            ->whereNull('unidades.deleted_at')
            ->selectRaw('unidades.unidad_id as unidad_id, count(*) as total')
            ->groupBy('unidades.unidad_id')
            ->pluck('total', 'unidad_id');

        $labels = [];
        $data = [];

        foreach ($unidades as $id => $nombre) {
            $total = (int) ($conteos[$id] ?? 0);

            if ($total === 0) {
                continue;
            }

            $labels[] = $nombre;
            $data[] = $total;
        }

        $sinUnidad = Activo::query()->count() - array_sum($data);

        if ($sinUnidad > 0) {
            $labels[] = 'Sin unidad';
            $data[] = $sinUnidad;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Activos',
                    'data' => $data,
                    'backgroundColor' => $this->colores(count($labels)),
                    'borderRadius' => 4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'x' => [
                    'beginAtZero' => true,
                    'ticks' => ['precision' => 0],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    /**
     * @return array<int, string>
     */
    private function colores(int $cantidad): array
    {
        $colores = [];
        $paleta = count(self::COLORES);

        for ($i = 0; $i < $cantidad; $i++) {
            $colores[] = self::COLORES[$i % $paleta];
        }

        return $colores;
    }
}
