<?php

namespace App\Filament\Widgets;

use App\Models\Activo;
use App\Models\ActivoTipo;
use Filament\Widgets\ChartWidget;

class ActivosPorTipoChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Activos por tipo';

    protected ?string $description = 'Distribución del inventario según el tipo de activo.';

    protected ?string $pollingInterval = null;

    /**
     * Paleta usada para pintar cada rebanada del gráfico.
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
        $tipos = ActivoTipo::query()->orderBy('nombre')->pluck('nombre', 'activo_tipos_id');

        $conteos = Activo::query()
            ->selectRaw('activo_tipo_id, count(*) as total')
            ->groupBy('activo_tipo_id')
            ->pluck('total', 'activo_tipo_id');

        $labels = [];
        $data = [];

        foreach ($tipos as $id => $nombre) {
            $labels[] = $nombre;
            $data[] = (int) ($conteos[$id] ?? 0);
        }

        $sinClasificar = Activo::query()->count() - array_sum($data);

        if ($sinClasificar > 0) {
            $labels[] = 'Sin tipo';
            $data[] = $sinClasificar;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Activos',
                    'data' => $data,
                    'backgroundColor' => $this->colores(count($labels)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
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
