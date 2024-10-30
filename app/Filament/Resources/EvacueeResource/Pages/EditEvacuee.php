<?php

namespace App\Filament\Resources\EvacueeResource\Pages;

use App\Filament\Resources\EvacueeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvacuee extends EditRecord
{
    protected static string $resource = EvacueeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
