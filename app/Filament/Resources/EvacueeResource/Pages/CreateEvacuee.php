<?php

namespace App\Filament\Resources\EvacueeResource\Pages;

use App\Filament\Resources\EvacueeResource;
use App\Models\Family;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEvacuee extends CreateRecord
{
    protected static string $resource = EvacueeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Get the repeater data
        $personalInfoArray = $data['Personal Information'] ?? [];
        $evacCenterId = $data['evac_center_id'];

        // Get the first entry to determine if we're using an existing family
        $firstMember = $personalInfoArray[0] ?? null;
        $existingFamilyId = $firstMember['family_id'] ?? null;

        // If no existing family ID, create a new family
        if (!$existingFamilyId) {
            $family = Family::create([
                'evac_center_id' => $evacCenterId
            ]);
            $familyId = $family->id;
        } else {
            $familyId = $existingFamilyId;
        }

        // Create evacuees for each person in the repeater
        $createdEvacuees = collect();
        foreach ($personalInfoArray as $personData) {
            // Remove the family_id from person data as we'll set it separately
            unset($personData['family_id']);

            // Create the evacuee record
            $evacuee = static::getModel()::create([
                ...$personData,
                'family_id' => $familyId,
            ]);

            $createdEvacuees->push($evacuee);
        }

        // Return the first created evacuee for proper redirect
        return $createdEvacuees->first();
    }
}
