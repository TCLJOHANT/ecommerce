<?php
//credo con comando:  sail php artisan make:filament-relation-manager OrderResource address street_address
namespace App\Filament\Resources\OrderResource\RelationManagers;

use Faker\Provider\ar_EG\Text;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                TextInput::make('last_name')
                    ->required()->label('Apellido')
                    ->maxLength(255),
                TextInput::make('phone')
                    ->required()->label('Teléfono')
                    ->maxLength(255),
                TextInput::make('city')
                    ->required()->label('Ciudad')
                    ->maxLength(255),
                TextInput::make('state')
                    ->required()->label('Estado')
                    ->maxLength(255),
                TextInput::make('zip_code')
                    ->required()->label('Código Postal')
                    ->maxLength(15),
                Forms\Components\Textarea::make('street_address')
                    ->required()->label('Dirección')->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                TextColumn::make('fullname')->label('Nombre'),
                TextColumn::make('phone')->label('Teléfono'),
                TextColumn::make('city')->label('Ciudad'),
                TextColumn::make('state')->label('Estado'),
                TextColumn::make('zip_code')->label('Código Postal'),
                TextColumn::make('street_address')->label('Dirección'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
