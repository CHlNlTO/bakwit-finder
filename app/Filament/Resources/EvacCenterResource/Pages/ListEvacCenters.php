<?php

namespace App\Filament\Resources\EvacCenterResource\Pages;

use App\Filament\Resources\EvacCenterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvacCenters extends ListRecords
{
    protected static string $resource = EvacCenterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
