<?php

namespace App\Filament\Resources\DispositivoReds\Pages;

use App\Filament\Resources\DispositivoReds\DispositivoRedResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDispositivoReds extends ListRecords
{
    protected static string $resource = DispositivoRedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
