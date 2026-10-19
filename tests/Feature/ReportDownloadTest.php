<?php

use App\Filament\Exports\ActivoExporter;
use App\Filament\Resources\Activos\Pages\ListActivos;
use App\Models\Activo;
use App\Models\User;
use Filament\Actions\ExportAction;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel('admin');

    $this->actingAs(User::factory()->create());
});

it('descarga el reporte de activos en PDF', function () {
    Activo::query()->create(['codigo_activo' => 'ACT-001']);

    Livewire::test(ListActivos::class)
        ->callAction(TestAction::make('pdf')->table())
        ->assertFileDownloaded(
            filename: 'activos-ti-'.now()->format('Y-m-d').'.pdf',
            contentType: 'application/pdf',
        );
});

it('exporta los activos a Excel', function () {
    $exports = ExportAction::fake();

    Livewire::test(ListActivos::class)
        ->callAction(TestAction::make('excel')->table())
        ->assertHasNoFormErrors();

    $exports->assertDispatched(ActivoExporter::class);
});

it('exporta los activos a CSV', function () {
    $exports = ExportAction::fake();

    Livewire::test(ListActivos::class)
        ->callAction(TestAction::make('csv')->table())
        ->assertHasNoFormErrors();

    $exports->assertDispatched(ActivoExporter::class);
});
