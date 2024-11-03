<?php

//creado con comando: sail php artisan make:filament-widget LatestOrders --table
// opcicon  admin panel
namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 2; //se puso para que el windegt de estados de orden aparesca de  primero antess que la tabla 
    public function table(Table $table): Table
    {
        return $table
            ->query( OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID de la Orden'),
                Tables\Columns\TextColumn::make('user.name')->label('Cliente')->sortable(),
                Tables\Columns\TextColumn::make('grand_total')->label('Gran Total')->sortable()->money('COL'),
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

                    Tables\Columns\TextColumn::make('payment_method')->label('Forma de Pago')->sortable()->searchable(),
                    Tables\Columns\TextColumn::make('payment_status')->label('Estado del Pago')->sortable()->searchable()->badge(),
                    Tables\Columns\TextColumn::make('created_at')->label('Fecha de la Orden')->sortable()->searchable()
                        ->dateTime(),
            ])
            ->actions([
                Tables\Actions\Action::make('Ver orden')
                    ->url(fn (Order $record):string => OrderResource::getUrl('view',['record' => $record]))
                    ->color('info')
                    ->icon('heroicon-o-eye'),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
