<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvacueeFamilyMemberResource\Pages;
use App\Models\EvacueeFamilyMember;
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

class EvacueeFamilyMemberResource extends Resource
{
    protected static ?string $model = EvacueeFamilyMember::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Evacuee Management';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Family Member';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('evacuee_id')
                    ->relationship('evacuee', 'last_name')
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('evacuee.last_name')
                    ->sortable()
                    ->searchable()
                    ->label('Family Head'),
                TextColumn::make('full_name')
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->sortable()
                    ->formatStateUsing(fn($record) =>
                    "{$record->last_name}, {$record->first_name} {$record->middle_name}"),
                TextColumn::make('gender')
                    ->sortable(),
                TextColumn::make('age')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('evacuee')
                    ->relationship('evacuee', 'last_name'),
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
            'index' => Pages\ListEvacueeFamilyMembers::route('/'),
            'create' => Pages\CreateEvacueeFamilyMember::route('/create'),
            'edit' => Pages\EditEvacueeFamilyMember::route('/{record}/edit'),
        ];
    }
}
