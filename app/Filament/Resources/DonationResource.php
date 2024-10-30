<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?string $navigationGroup = 'Support Management';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('evac_center_id')
                    ->relationship('evacCenter', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('donation_type')
                    ->required()
                    ->options([
                        'Food' => 'Food',
                        'Water' => 'Water',
                        'Clothing' => 'Clothing',
                        'Medicine' => 'Medicine',
                        'Hygiene Kits' => 'Hygiene Kits',
                        'Others' => 'Others',
                    ]),
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->placeholder('Enter quantity'),
                TextInput::make('donator')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter donator name'),
                TextInput::make('beneficiary')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter beneficiary name'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('evacCenter.name')
                    ->sortable()
                    ->searchable()
                    ->label('Evacuation Center'),
                TextColumn::make('donation_type')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('donator')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('beneficiary')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('evac_center')
                    ->relationship('evacCenter', 'name'),
                Tables\Filters\SelectFilter::make('donation_type')
                    ->options([
                        'Food' => 'Food',
                        'Water' => 'Water',
                        'Clothing' => 'Clothing',
                        'Medicine' => 'Medicine',
                        'Hygiene Kits' => 'Hygiene Kits',
                        'Others' => 'Others',
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
            'index' => Pages\ListDonations::route('/'),
            'create' => Pages\CreateDonation::route('/create'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
