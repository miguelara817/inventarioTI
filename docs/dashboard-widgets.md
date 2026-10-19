# Guía: widgets y gráficos del dashboard

Esta guía explica cómo crear y mantener los widgets del dashboard de Filament 5 en este proyecto
(stats, gráficos y tablas), siguiendo los patrones ya usados en `app/Filament/Widgets`.

## 1. Los tres tipos de widget

| Necesitas | Clase base | Comando |
| --- | --- | --- |
| Números / tarjetas | `StatsOverviewWidget` | `php artisan make:filament-widget Nombre --stats-overview` |
| Gráficos | `ChartWidget` | `php artisan make:filament-widget Nombre --chart` |
| Tablas | `TableWidget` | `php artisan make:filament-widget Nombre --table` |

Todo lo que pongas en `app/Filament/Widgets` se registra automáticamente (ya existe
`discoverWidgets` en `app/Providers/Filament/AdminPanelProvider.php`). No hace falta registrarlo a mano.

## 2. Un gráfico mínimo (dona)

```php
<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class MiGrafico extends ChartWidget
{
    protected static ?int $sort = 2;              // orden en el dashboard
    protected ?string $heading = 'Mi título';
    protected ?string $description = 'Resumen visible y accesible.';
    protected ?string $pollingInterval = null;    // sin refresco automático

    /**
     * @return array<string, mixed>
     */
    protected function getData(): array
    {
        return [
            'datasets' => [[
                'label' => 'Activos',
                'data' => [3, 5, 2],
                'backgroundColor' => ['#243652', '#0ea5e9', '#10b981'],
            ]],
            'labels' => ['Laptop', 'PC', 'Monitor'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut'; // bar | line | pie | polarArea | radar | scatter | bubble
    }
}
```

`getData()` sigue el formato de **Chart.js**, así que cualquier opción de Chart.js es válida aquí
(`tension`, `fill`, `borderRadius`, `borderColor`, etc.).

## 3. Agrupar datos en la BD (sin traer todo a PHP)

El patrón que usa el proyecto evita `N+1` y no depende de joins frágiles: se agrupa por la **llave
foránea** y se resuelven las etiquetas contra el catálogo.

```php
$catalogo = ActivoTipo::query()->orderBy('nombre')->pluck('nombre', 'activo_tipos_id');

$conteos = Activo::query()
    ->selectRaw('activo_tipo_id, count(*) as total')
    ->groupBy('activo_tipo_id')
    ->pluck('total', 'activo_tipo_id');

$labels = [];
$data = [];
foreach ($catalogo as $id => $nombre) {
    $labels[] = $nombre;
    $data[] = (int) ($conteos[$id] ?? 0);   // incluye ceros
}

// lo que no cae en ningún catálogo (nulos o registros borrados)
$sinClasificar = Activo::query()->count() - array_sum($data);
if ($sinClasificar > 0) {
    $labels[] = 'Sin tipo';
    $data[] = $sinClasificar;
}
```

Convenciones:

- `(int)` al valor: Postgres devuelve `count(*)` como string.
- El modelo `Activo` aplica soft deletes, así que `Activo::query()` no cuenta eliminados.
- Para agrupar por estados, agrupa por la FK (`estado_operativo`) y no por el nombre.

## 4. Agrupar por una relación de dos saltos

Cuando el dato a agrupar está a dos relaciones de distancia (por ejemplo
`Activo → persona_asignada → unidad`), usa `join` y agrupa por la llave del catálogo. Filtra los
soft deletes de las tablas unidas y calcula lo no clasificado con el total menos la suma.

```php
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
        continue; // en este gráfico se omiten las unidades sin activos
    }

    $labels[] = $nombre;
    $data[] = $total;
}

$sinUnidad = Activo::query()->count() - array_sum($data);
if ($sinUnidad > 0) {
    $labels[] = 'Sin unidad';
    $data[] = $sinUnidad;
}
```

Para nombres largos (unidades, ubicaciones) conviene una barra **horizontal** con
`getOptions()`:

```php
protected function getOptions(): array
{
    return [
        'indexAxis' => 'y',
        'plugins' => ['legend' => ['display' => false]],
        'scales' => ['x' => ['beginAtZero' => true, 'ticks' => ['precision' => 0]]],
    ];
}
```

## 5. Serie por mes (portable Postgres / SQLite)

`date_trunc` / `strftime` / `EXTRACT` no son portables y los tests corren en SQLite. Lo portable es
`whereYear` / `whereMonth`:

```php
$inicio = now()->startOfMonth()->subMonths(11);

for ($i = 0; $i < 12; $i++) {
    $mes = $inicio->copy()->addMonths($i);
    $labels[] = $mes->locale('es')->translatedFormat('M Y');
    $data[] = Activo::query()
        ->whereYear('fecha_registro', $mes->year)
        ->whereMonth('fecha_registro', $mes->month)
        ->count();
}
```

## 6. Resumen de indicadores (StatsOverviewWidget)

```php
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MiResumen extends StatsOverviewWidget
{
    protected ?string $pollingInterval = null;
    protected int|array|null $columns = ['@xl' => 4, '!@lg' => 2];

    protected function getStats(): array
    {
        return [
            Stat::make('Total de activos', Activo::query()->count())
                ->description('Registrados en el inventario')
                ->descriptionIcon('heroicon-o-cube')
                ->color('primary'),
        ];
    }
}
```

`color()` acepta `primary`, `success`, `info`, `warning`, `danger`, `gray`. Cada `Stat` también
acepta `->chart([...])` para una minigráfica de tendencia.

## 7. Tablas (TableWidget)

```php
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class MiTabla extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->heading('Mi listado')
            ->query(fn () => Activo::query()->with(['tipo'])->latest('fecha_registro')->limit(5))
            ->paginated(false)
            ->columns([
                TextColumn::make('codigo_activo')->label('Código')->searchable(),
                TextColumn::make('tipo.nombre')->label('Tipo'),
            ]);
    }
}
```

Recuerda precargar las relaciones con `->with(...)` para evitar `N+1` al mostrar columnas de
relación.

## 8. Control de diseño desde el dashboard

En `app/Filament/Pages/Dashboard.php`:

```php
public function getColumns(): int | array
{
    return ['md' => 2, 'xl' => 3]; // 1 columna en móvil, 2 en md, 3 en xl
}
```

En cada widget:

- `protected static ?int $sort = N;` → orden.
- `protected int|string|array $columnSpan = 1;` → cuántas columnas ocupa (`1`, `2`, `'full'` o
  `['md' => 2]`).

Los widgets son **lazy** por defecto (solo cargan al verse). Para desactivarlo:
`protected static bool $isLazy = false;`. Para refresco automático:
`protected ?string $pollingInterval = '30s';`.

## 9. Cómo probarlo

`getData()` / `getStats()` son `protected`. Con el helper `invokeProtected()` de `tests/Pest.php`
se pueden inspeccionar directamente:

```php
it('agrupa por tipo', function () {
    // ...crear datos
    $data = invokeProtected(new ActivosPorTipoChart, 'getData');

    expect($data['labels'])->toBe(['Laptop', 'PC'])
        ->and($data['datasets'][0]['data'])->toBe([1, 2]);
});
```

Para tablas, y para verificar que el widget renderiza sin errores:

```php
Livewire::test(UltimosActivosRegistrados::class)
    ->assertCanSeeTableRecords($activos)
    ->assertCountTableRecords(4);

Livewire::test(ActivosPorTipoChart::class)->assertOk();
```

Recuerda siempre `Filament::setCurrentPanel('admin')` y
`$this->actingAs(User::factory()->create())` en `beforeEach`.

## 10. Errores comunes

- **No uses `date_trunc`, `strftime` ni `EXTRACT`** en crudo: rompe los tests en SQLite. Usa
  `whereYear` / `whereMonth`.
- **No cargues la colección completa** para contar en PHP: usa `groupBy` + `selectRaw`.
- **No agrupes por el nombre del catálogo con `leftJoin` sin filtrar soft deletes**: mejor agrupa
  por la FK y resuelve el nombre en PHP.
- **Nunca uses `whereHas` dentro de un `selectRaw`/agregado**; para contar por estado usa
  `whereHas(...)->count()`.
- Si un gráfico se ve cortado, ajusta `$columnSpan` o `getColumns()`.
- En producción, si agregas widgets y no aparecen, limpia la caché de componentes:
  `php artisan optimize:clear` (y opcionalmente `php artisan filament:cache-components`).

## 11. Ideas para extender el dashboard

- Computadoras por **sistema operativo** o **memoria RAM**.
- Activos por **ubicación / edificio**.
- Integridad de datos: IP/MAC duplicadas, activos sin `numero_serie`.

## Widgets actuales

| Orden | Archivo | Tipo |
| --- | --- | --- |
| 1 | `ActivosStatsOverview.php` | Stats overview (KPIs) |
| 2 | `ActivosPorTipoChart.php` | Dona |
| 3 | `ActivosPorEstadoOperativoChart.php` | Dona |
| 4 | `ActivosPorEstadoAsignadoChart.php` | Barras |
| 5 | `ActivosPorUnidadChart.php` | Barras horizontales |
| 6 | `AltasPorMesChart.php` | Línea (12 meses, ancho completo) |
| 7 | `UltimosActivosRegistrados.php` | Tabla |
| 8 | `ActivosCriticos.php` | Tabla |
