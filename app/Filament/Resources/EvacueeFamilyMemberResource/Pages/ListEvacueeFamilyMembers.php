<?php

namespace App\Filament\Resources\EvacueeFamilyMemberResource\Pages;

use App\Filament\Resources\EvacueeFamilyMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvacueeFamilyMembers extends ListRecords
{
    protected static string $resource = EvacueeFamilyMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
