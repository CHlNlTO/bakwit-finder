<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvacueeResource\Pages;
use App\Models\Barangay;
use App\Models\Evacuee;
use App\Models\Family;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class EvacueeResource extends Resource
{
    protected static ?string $model = Evacuee::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Evacuee Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Evacuation Center Information')
                    ->schema([
                        Select::make('evac_center_id')
                            ->relationship('family.evacCenter', 'name')
                            ->label('Evacuation Center')
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),

                Repeater::make('Personal Information')
                    ->schema([
                        Select::make('family_id')
                            ->label('Family ID')
                            ->options(function () {
                                return Family::query()
                                    ->with('members')
                                    ->get()
                                    ->mapWithKeys(function ($family) {
                                        $familyHead = $family->members->first();
                                        if (!$familyHead) return [];

                                        // Create the member list for dropdown display
                                        $membersList = $family->members
                                            ->map(function ($member) {
                                                return "└─ {$member->last_name}, {$member->first_name} {$member->middle_name}";
                                            })
                                            ->join("\n");

                                        // Full label for dropdown options
                                        $dropdownLabel = "Family #{$family->id} - {$familyHead->last_name} Family\n{$membersList}";

                                        return [$family->id => $dropdownLabel];
                                    });
                            })
                            ->getOptionLabelUsing(
                                fn($value) =>
                                Family::query()
                                    ->with('members')
                                    ->find($value)
                                    ->members
                                    ->first()
                                    ->transform(fn($member) => "Family #{$value} - {$member->last_name} Family")
                            )
                            ->searchable()
                            ->preload()
                            ->placeholder('Select Family ID or leave blank for new family')
                            ->live()
                            ->selectablePlaceholder(false),
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
                            ->options([
                                'Male' => 'Male',
                                'Female' => 'Female',
                                'Other' => 'Other',
                            ])
                            ->placeholder('Select gender'),
                        DatePicker::make('birth_date')
                            ->maxDate(now())
                            ->placeholder('Select birth date'),
                        TextInput::make('age')
                            ->numeric()
                            ->placeholder('Enter age'),
                        TextInput::make('religion')
                            ->maxLength(255)
                            ->placeholder('Enter religion'),
                        TextInput::make('nationality')
                            ->maxLength(255)
                            ->placeholder('Enter nationality'),
                        TextInput::make('address')
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
                    ])
                    ->label('Personal Information')
                    ->columns(2)
                    ->columnSpanFull()
                    ->addActionLabel('Add Family Member'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('family.id')
                    ->label('Family ID')
                    ->formatStateUsing(fn($record) => '#000' . $record->family?->id)
                    ->placeholder('N/A')
                    ->sortable(),
                TextColumn::make('full_name')
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->placeholder('N/A')
                    ->sortable(),
                TextColumn::make('family.evacCenter.name')
                    ->label('Evacuation Center')
                    ->placeholder('N/A')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('gender')
                    ->placeholder('N/A')
                    ->sortable(),
                TextColumn::make('age')
                    ->numeric()
                    ->placeholder('N/A')
                    ->sortable(),
                TextColumn::make('birth_date')
                    ->placeholder('N/A')
                    ->sortable()
                    ->date('Y-m-d'),
                TextColumn::make('contact_number')
                    ->placeholder('N/A')
                    ->searchable(),
                TextColumn::make('email')
                    ->placeholder('N/A')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('barangay')
                //     ->relationship('evacCenter.barangay', 'name'),
                Tables\Filters\SelectFilter::make('evac_center')
                    ->relationship('family.evacCenter', 'name'),
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
