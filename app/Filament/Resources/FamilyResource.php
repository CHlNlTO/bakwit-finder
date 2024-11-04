<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FamilyResource\Pages;
use App\Models\Barangay;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FamilyResource extends Resource
{
    protected static ?string $model = Family::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Evacuee Management';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Family';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Select::make('barangay_id')
                //     ->relationship('barangay', 'name')
                //     ->required()
                //     ->searchable()
                //     ->preload()
                //     ->getOptionLabelFromRecordUsing(fn($record) => "{$record->name} ({$record->city->name})")
                //     ->options(
                //         Barangay::query()
                //             ->with('city')
                //             ->get()
                //             ->groupBy('city.name')
                //             ->mapWithKeys(function ($barangays, $cityName) {
                //                 return $barangays->mapWithKeys(function ($barangay) use ($cityName) {
                //                     return [$barangay->id => "{$barangay->name}, {$cityName}"];
                //                 });
                //             })
                //             ->toArray()
                //     )
                //     ->optionsLimit(100),
                Select::make('evac_center_id')
                    ->relationship('evacCenter', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Family ID')
                    ->sortable(),
                TextColumn::make('members')
                    ->label('Family Head')
                    ->formatStateUsing(function (Model $record) {
                        $head = $record->members()->first();
                        if (!$head) return 'N/A';
                        return "{$head->last_name}, {$head->first_name} {$head->middle_name}";
                    })
                    ->placeholder('N/A'),
                TextColumn::make('members_count')
                    ->counts('members')
                    ->label('Family Members')
                    ->sortable(),
                TextColumn::make('evacCenter.name')
                    ->label('Evacuation Center')
                    ->placeholder('N/A')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('barangay')
                //     ->relationship('barangay', 'name'),
                Tables\Filters\SelectFilter::make('evac_center')
                    ->relationship('evacCenter', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFamilies::route('/'),
            'create' => Pages\CreateFamily::route('/create'),
            'edit' => Pages\EditFamily::route('/{record}/edit'),
        ];
    }
}
