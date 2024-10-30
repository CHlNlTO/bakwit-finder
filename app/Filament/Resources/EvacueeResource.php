<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvacueeResource\Pages;
use App\Models\Barangay;
use App\Models\Evacuee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;

class EvacueeResource extends Resource
{
    protected static ?string $model = Evacuee::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Evacuee Management';
    protected static ?int $navigationSort = 1;

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
                Select::make('evac_center_id')
                    ->relationship('evacCenter', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('last_name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter last name'),
                TextInput::make('first_name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter first name'),
                TextInput::make('middle_name')
                    ->maxLength(255)
                    ->placeholder('Enter middle name'),
                Select::make('gender')
                    ->required()
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                        'Other' => 'Other',
                    ]),
                DatePicker::make('birth_date')
                    ->required()
                    ->maxDate(now()),
                TextInput::make('age')
                    ->required()
                    ->numeric()
                    ->placeholder('Enter age'),
                TextInput::make('religion')
                    ->maxLength(255)
                    ->placeholder('Enter religion'),
                TextInput::make('nationality')
                    ->maxLength(255)
                    ->placeholder('Enter nationality'),
                TextInput::make('address')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter address'),
                TextInput::make('contact_number')
                    ->tel()
                    ->maxLength(255)
                    ->placeholder('Enter contact number'),
                TextInput::make('email')
                    ->email()
                    ->maxLength(255)
                    ->placeholder('Enter email address'),
                TextInput::make('sector')
                    ->maxLength(255)
                    ->placeholder('Enter sector'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->sortable()
                    ->formatStateUsing(fn($record) =>
                    "{$record->last_name}, {$record->first_name} {$record->middle_name}"),
                TextColumn::make('barangay.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('evacCenter.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('gender')
                    ->sortable(),
                TextColumn::make('age')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('contact_number')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('barangay')
                    ->relationship('barangay', 'name'),
                Tables\Filters\SelectFilter::make('evac_center')
                    ->relationship('evacCenter', 'name'),
                Tables\Filters\SelectFilter::make('gender')
                    ->options([
                        'Male' => 'Male',
                        'Female' => 'Female',
                        'Other' => 'Other',
                    ]),
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
            'index' => Pages\ListEvacuees::route('/'),
            'create' => Pages\CreateEvacuee::route('/create'),
            'edit' => Pages\EditEvacuee::route('/{record}/edit'),
        ];
    }
}
