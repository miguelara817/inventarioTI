<?php

namespace App\Filament\Resources\Activos\Pages;

use App\Filament\Resources\Activos\ActivoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditActivo extends EditRecord
{
    protected static string $resource = ActivoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
