<?php

namespace App\Filament\Resources\EvacueeFamilyMemberResource\Pages;

use App\Filament\Resources\EvacueeFamilyMemberResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEvacueeFamilyMember extends EditRecord
{
    protected static string $resource = EvacueeFamilyMemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
