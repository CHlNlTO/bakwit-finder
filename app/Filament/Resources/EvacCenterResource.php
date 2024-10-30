<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvacCenterResource\Pages;
use App\Models\Barangay;
use App\Models\EvacCenter;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;

class EvacCenterResource extends Resource
{
    protected static ?string $model = EvacCenter::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Facility Management';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Evacuation Center';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('barangay_id')
                    ->relationship('barangay', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->name} ({$record->city->name})")
                    ->options(
                        Barangay::query()
                            ->with('city')
                            ->get()
                            ->groupBy('city.name')
                            ->mapWithKeys(function ($barangays, $cityName) {
                                return $barangays->mapWithKeys(function ($barangay) use ($cityName) {
                                    return [$barangay->id => "{$barangay->name}, {$cityName}"];
                                });
                            })
                            ->toArray()
                    )
                    ->optionsLimit(100),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter evacuation center name'),
                TextInput::make('address')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter address'),
                TextInput::make('longitude')
                    ->required()
                    ->numeric()
                    ->placeholder('Enter longitude'),
                TextInput::make('latitude')
                    ->required()
                    ->numeric()
                    ->placeholder('Enter latitude'),
                TextInput::make('capacity')
                    ->required()
                    ->numeric()
                    ->placeholder('Enter capacity'),
                TextInput::make('contact_person')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter contact person name'),
                TextInput::make('contact_number')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter contact number'),
                Textarea::make('description')
                    ->maxLength(65535)
                    ->placeholder('Enter description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('barangay.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('contact_person')
                    ->searchable(),
                TextColumn::make('contact_number')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('barangay')
                    ->relationship('barangay', 'name')
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
            'index' => Pages\ListEvacCenters::route('/'),
            'create' => Pages\CreateEvacCenter::route('/create'),
            'edit' => Pages\EditEvacCenter::route('/{record}/edit'),
        ];
    }
}
