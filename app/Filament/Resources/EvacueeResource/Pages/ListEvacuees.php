<?php

namespace App\Filament\Resources\EvacueeResource\Pages;

use App\Filament\Resources\EvacueeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvacuees extends ListRecords
{
    protected static string $resource = EvacueeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
