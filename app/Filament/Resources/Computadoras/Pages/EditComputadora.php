<?php

namespace App\Filament\Resources\Computadoras\Pages;

use App\Filament\Resources\Computadoras\ComputadoraResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditComputadora extends EditRecord
{
    protected static string $resource = ComputadoraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
