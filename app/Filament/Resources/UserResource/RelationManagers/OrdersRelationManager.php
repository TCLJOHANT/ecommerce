<?php
//credo con comando: sail php artisan make:filament-relation-manager UserResource orders id
namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Faker\Provider\ar_EG\Text;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID de la Orden'),
                TextColumn::make('grand_total')->label('Gran Total')->sortable()->money('COL'),
                Tables\Columns\TextColumn::make('status')->label('Estado de la Orden')
                    ->badge()
                    ->color(
                        fn(string $state):string => match($state){
                        'new'=>'info',
                        'processing'=>'warning',
                        'shipped'=>'success',
                        'delivered'=>'success',
                        'canceled'=>'danger',
                        //default=>'danger',
                        }
                    )->icon(
                        fn(string $state):string => match($state){
                        'new'=>'heroicon-m-sparkles',
                        'processing'=>'heroicon-m-arrow-path',
                        'shipped'=>'heroicon-m-truck',
                        'delivered'=>'heroicon-m-check-badge',
                        'canceled'=>'heroicon-m-x-circle',
                        //default=>'heroicon-m-x-circle',
                        }
                    )->sortable(),

                    TextColumn::make('payment_method')->label('Forma de Pago')->sortable()->searchable(),
                    TextColumn::make('payment_status')->label('Estado del Pago')->sortable()->searchable()->badge(),
                    TextColumn::make('created_at')->label('Fecha de la Orden')->sortable()->searchable()
                        ->dateTime(),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                //Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('Ver orden')
                    ->url(fn (Order $record):string => OrderResource::getUrl('view',['record' => $record]))
                    ->color('info')
                    ->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
