<?php

namespace App\Filament\Widgets;

use App\Models\Activo;
use App\Models\Persona;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ActivosStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Resumen del inventario';

    protected ?string $pollingInterval = null;

    protected int|array|null $columns = ['@xl' => 4, '!@lg' => 2];

    /**
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        $total = Activo::query()->count();
        $disponibles = $this->activosConEstadoAsignado('Disponible');
        $asignados = $this->activosConEstadoAsignado('Asignado');
        $enMantenimiento = $this->activosConEstadoAsignado('En mantenimiento');
        $dadosDeBaja = $this->activosConEstadoAsignado('Dado de baja');

        $enFallas = Activo::query()
            ->whereHas('estadoOperativo', fn ($query) => $query->whereIn('nombre', ['Con fallas', 'Fuera de servicio']))
            ->count();

        $personasActivas = Persona::query()->where('is_active', true)->count();
        $personasConActivos = Persona::query()->where('is_active', true)->has('activos')->count();

        $datosIncompletos = Activo::query()
            ->where(fn ($query) => $query
                ->whereNull('activo_tipo_id')
                ->orWhereNull('ubicacion_id'))
            ->count();

        return [
            Stat::make('Total de activos', $total)
                ->description('Registrados en el inventario')
                ->descriptionIcon(Heroicon::OutlinedCube)
                ->color('primary'),
            Stat::make('Disponibles', $disponibles)
                ->description($this->porcentaje($disponibles, $total).' del total')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->color('success'),
            Stat::make('Asignados', $asignados)
                ->description($this->porcentaje($asignados, $total).' del total')
                ->descriptionIcon(Heroicon::OutlinedUser)
                ->color('info'),
            Stat::make('En mantenimiento', $enMantenimiento)
                ->description('Requieren seguimiento')
                ->descriptionIcon(Heroicon::OutlinedWrenchScrewdriver)
                ->color('warning'),
            Stat::make('Con fallas o fuera de servicio', $enFallas)
                ->description('Atención prioritaria')
                ->descriptionIcon(Heroicon::OutlinedExclamationTriangle)
                ->color('danger'),
            Stat::make('Dados de baja', $dadosDeBaja)
                ->description('Fuera del inventario operativo')
                ->descriptionIcon(Heroicon::OutlinedArchiveBox)
                ->color('gray'),
            Stat::make('Personas activas', $personasActivas)
                ->description($personasConActivos.' con activos asignados')
                ->descriptionIcon(Heroicon::OutlinedUsers)
                ->color('primary'),
            Stat::make('Datos incompletos', $datosIncompletos)
                ->description('Sin tipo o ubicación')
                ->descriptionIcon(Heroicon::OutlinedClipboardDocumentCheck)
                ->color('warning'),
        ];
    }

    private function activosConEstadoAsignado(string $valor): int
    {
        return Activo::query()
            ->whereHas('estadoAsignado', fn ($query) => $query->where('valor', $valor))
            ->count();
    }

    private function porcentaje(int $parte, int $total): string
    {
        if ($total === 0) {
            return '0 %';
        }

        return round(($parte / $total) * 100, 1).' %';
    }
}
