<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NeedResource\Pages;
use App\Models\Need;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;

class NeedResource extends Resource
{
    protected static ?string $model = Need::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Support Management';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('evac_center_id')
                    ->relationship('evacCenter', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('description')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter need description'),
                Select::make('urgency')
                    ->required()
                    ->options([
                        1 => 'Low',
                        2 => 'Medium',
                        3 => 'High',
                        4 => 'Critical',
                    ])
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('evacCenter.name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('urgency')
                    ->sortable()
                    ->formatStateUsing(fn(int $state) => match ($state) {
                        1 => 'Low',
                        2 => 'Medium',
                        3 => 'High',
                        4 => 'Critical',
                    })
                    ->color(fn(int $state): string => match ($state) {
                        1 => 'gray',
                        2 => 'warning',
                        3 => 'danger',
                        4 => 'danger',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('evac_center')
                    ->relationship('evacCenter', 'name'),
                Tables\Filters\SelectFilter::make('urgency')
                    ->options([
                        1 => 'Low',
                        2 => 'Medium',
                        3 => 'High',
                        4 => 'Critical',
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
            'index' => Pages\ListNeeds::route('/'),
            'create' => Pages\CreateNeed::route('/create'),
            'edit' => Pages\EditNeed::route('/{record}/edit'),
        ];
    }
}
