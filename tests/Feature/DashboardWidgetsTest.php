<?php

use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\ActivosCriticos;
use App\Filament\Widgets\ActivosPorEstadoAsignadoChart;
use App\Filament\Widgets\ActivosPorEstadoOperativoChart;
use App\Filament\Widgets\ActivosPorTipoChart;
use App\Filament\Widgets\ActivosStatsOverview;
use App\Filament\Widgets\AltasPorMesChart;
use App\Filament\Widgets\UltimosActivosRegistrados;
use App\Models\Activo;
use App\Models\ActivoEstadoAsignado;
use App\Models\ActivoEstadoOperativo;
use App\Models\ActivoTipo;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Unidad;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel('admin');

    $this->actingAs(User::factory()->create());

    $this->operativo = ActivoEstadoOperativo::query()->create(['nombre' => 'Operativo']);
    $this->conFallas = ActivoEstadoOperativo::query()->create(['nombre' => 'Con fallas']);
    $this->noVerificado = ActivoEstadoOperativo::query()->create(['nombre' => 'No verificado']);

    $this->asignado = ActivoEstadoAsignado::query()->create(['valor' => 'Asignado']);
    $this->disponible = ActivoEstadoAsignado::query()->create(['valor' => 'Disponible']);
    $this->enMantenimiento = ActivoEstadoAsignado::query()->create(['valor' => 'En mantenimiento']);
    $this->dadoDeBaja = ActivoEstadoAsignado::query()->create(['valor' => 'Dado de baja']);

    $this->laptop = ActivoTipo::query()->create(['nombre' => 'Laptop']);
    $this->pc = ActivoTipo::query()->create(['nombre' => 'PC']);

    $this->ubicacion = Ubicacion::query()->create(['nombre' => 'Oficina principal']);
    $this->unidad = Unidad::query()->create(['nombre' => 'Jefatura de Sistemas']);

    $this->persona = Persona::query()->create([
        'nombre' => 'Ana',
        'apellido' => 'Perez',
        'email' => 'ana@example.com',
        'unidad_id' => $this->unidad->unidad_id,
    ]);
});

/**
 * Crea el conjunto de activos usado por los widgets del dashboard.
 *
 * @return array{0: Activo, 1: Activo, 2: Activo, 3: Activo}
 */
function crearActivosDeDashboard(object $test): array
{
    $disponible = Activo::query()->create([
        'codigo_activo' => 'ACT-001',
        'activo_tipo_id' => $test->laptop->activo_tipos_id,
        'ubicacion_id' => $test->ubicacion->ubicacion_id,
        'estado_asignado' => $test->disponible->activo_estado_asignado_id,
        'estado_operativo' => $test->operativo->activo_estado_operativo_id,
        'fecha_registro' => now(),
    ]);

    $asignado = Activo::query()->create([
        'codigo_activo' => 'ACT-002',
        'activo_tipo_id' => $test->pc->activo_tipos_id,
        'ubicacion_id' => $test->ubicacion->ubicacion_id,
        'estado_asignado' => $test->asignado->activo_estado_asignado_id,
        'estado_operativo' => $test->conFallas->activo_estado_operativo_id,
        'persona_asignada' => $test->persona->persona_id,
        'fecha_registro' => now()->subMonth(),
    ]);

    $enMantenimiento = Activo::query()->create([
        'codigo_activo' => 'ACT-003',
        'activo_tipo_id' => $test->pc->activo_tipos_id,
        'ubicacion_id' => $test->ubicacion->ubicacion_id,
        'estado_asignado' => $test->enMantenimiento->activo_estado_asignado_id,
        'estado_operativo' => $test->conFallas->activo_estado_operativo_id,
        'fecha_registro' => now()->subMonths(2),
    ]);

    $incompleto = Activo::query()->create([
        'codigo_activo' => 'ACT-004',
        'fecha_registro' => now()->subMonths(3),
    ]);

    return [$disponible, $asignado, $enMantenimiento, $incompleto];
}

it('calcula los indicadores del resumen de inventario', function () {
    crearActivosDeDashboard($this);

    $stats = invokeProtected(new ActivosStatsOverview, 'getStats');

    $valores = collect($stats)->mapWithKeys(
        fn ($stat): array => [(string) $stat->getLabel() => $stat->getValue()],
    );

    expect($valores['Total de activos'])->toBe(4)
        ->and($valores['Disponibles'])->toBe(1)
        ->and($valores['Asignados'])->toBe(1)
        ->and($valores['En mantenimiento'])->toBe(1)
        ->and($valores['Dados de baja'])->toBe(0)
        ->and($valores['Con fallas o fuera de servicio'])->toBe(2)
        ->and($valores['Personas activas'])->toBe(1)
        ->and($valores['Datos incompletos'])->toBe(1);
});

it('agrupa los activos por tipo en el gráfico', function () {
    crearActivosDeDashboard($this);

    $data = invokeProtected(new ActivosPorTipoChart, 'getData');

    expect($data['labels'])->toBe(['Laptop', 'PC', 'Sin tipo'])
        ->and($data['datasets'][0]['data'])->toBe([1, 2, 1]);
});

it('agrupa los activos por estado operativo en el gráfico', function () {
    crearActivosDeDashboard($this);

    $data = invokeProtected(new ActivosPorEstadoOperativoChart, 'getData');

    expect($data['labels'])->toBe(['Operativo', 'Con fallas', 'No verificado', 'Sin estado'])
        ->and($data['datasets'][0]['data'])->toBe([1, 2, 0, 1]);
});

it('agrupa los activos por estado de asignación en el gráfico', function () {
    crearActivosDeDashboard($this);

    $data = invokeProtected(new ActivosPorEstadoAsignadoChart, 'getData');

    expect($data['labels'])->toBe(['Asignado', 'Disponible', 'En mantenimiento', 'Dado de baja', 'Sin estado'])
        ->and($data['datasets'][0]['data'])->toBe([1, 1, 1, 0, 1]);
});

it('cuenta las altas de los últimos doce meses', function () {
    crearActivosDeDashboard($this);

    $data = invokeProtected(new AltasPorMesChart, 'getData');

    expect($data['labels'])->toHaveCount(12)
        ->and($data['datasets'][0]['data'])->toBe([0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1]);
});

it('lista los últimos activos registrados', function () {
    $activos = crearActivosDeDashboard($this);

    Livewire::test(UltimosActivosRegistrados::class)
        ->assertCanSeeTableRecords($activos)
        ->assertCountTableRecords(4);
});

it('lista solo los activos con fallas o fuera de servicio', function () {
    [$disponible, $asignado, $enMantenimiento, $incompleto] = crearActivosDeDashboard($this);

    Livewire::test(ActivosCriticos::class)
        ->assertCanSeeTableRecords([$asignado, $enMantenimiento])
        ->assertCanNotSeeTableRecords([$disponible, $incompleto]);
});

it('renderiza cada widget del dashboard sin errores', function () {
    crearActivosDeDashboard($this);

    Livewire::test(ActivosStatsOverview::class)->assertOk();
    Livewire::test(ActivosPorTipoChart::class)->assertOk();
    Livewire::test(ActivosPorEstadoOperativoChart::class)->assertOk();
    Livewire::test(ActivosPorEstadoAsignadoChart::class)->assertOk();
    Livewire::test(AltasPorMesChart::class)->assertOk();
});

it('renderiza el dashboard del sistema', function () {
    crearActivosDeDashboard($this);

    Livewire::test(Dashboard::class)->assertOk();
});
