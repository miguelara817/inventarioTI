<?php

namespace App\Filament\Resources\Activos\Pages;

use App\Filament\Resources\Activos\ActivoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListActivos extends ListRecords
{
    protected static string $resource = ActivoResource::class;

    // Opciones disponibles: ExtraLarge, TwoExtraLarge, SixExtraLarge, Full, etc.
    // protected ?MaxWidth $maxContentWidth = MaxWidth::Full; 

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
