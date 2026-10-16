<?php

namespace App\Filament\Resources\Computadoras\Pages;

use App\Filament\Resources\Computadoras\ComputadoraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComputadoras extends ListRecords
{
    protected static string $resource = ComputadoraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
