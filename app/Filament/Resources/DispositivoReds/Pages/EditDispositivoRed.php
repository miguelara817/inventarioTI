<?php

namespace App\Filament\Resources\DispositivoReds\Pages;

use App\Filament\Resources\DispositivoReds\DispositivoRedResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditDispositivoRed extends EditRecord
{
    protected static string $resource = DispositivoRedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
