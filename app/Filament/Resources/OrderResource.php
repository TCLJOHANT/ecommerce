<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\AddressRelationManager;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;

class OrderResource extends Resource
{
    //protected static ?string $label = 'Ordenes';
    
    protected static ?string $model = Order::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Compras';

    protected static ?string $modelLabel = 'Orden';

    protected static ?string $pluralLabel = 'Ordenes';

    protected static ?int $navigationSort = 5; //pocicion en le menu de la barra de navegacion

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Información del Pedido')
                        ->schema([
                            Select::make('user_id')
                                ->label('Cliente')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->relationship('user', 'name'),
                            Select::make('payment_method')
                                ->label('Forma de Pago')
                                ->required()
                                ->searchable()
                                ->options(['mastercard'=>'Mastercard', 'visa'=>'Visa', 'paypal'=>'Paypal']),
                            Select::make('payment_status')
                                ->label('Estado del Pago')
                                ->required()
                                ->searchable()
                                ->options(['paid'=>'Pagado', 'pending'=>'Pendiente', 'failed'=>'Fallido'])
                                ->default('pending'),
                            ToggleButtons::make('status')->inline()
                                ->label('Estado')
                                ->required()
                                ->options(['new'=>'Nuevo', 'processing'=>'Procesando', 'shipped'=>'Enviado', 'delivered'=>'Entregado', 'canceled'=>'Cancelado'])
                                ->default('new')
                                ->colors(['new'=>'info', 'processing'=>'warning', 'shipped'=>'success', 'delivered'=>'success', 'canceled'=>'danger'])
                                ->icons(['new'=>'heroicon-m-sparkles', 'processing'=>'heroicon-m-arrow-path', 'shipped'=>'heroicon-m-truck', 'delivered'=>'heroicon-m-check-badge', 'canceled'=>'heroicon-m-x-circle']),
                            Select::make('currency')
                                ->label('Divisa')
                                ->options(['col'=>'COL', 'eur'=>'EUR', 'usd'=>'USD' ])
                                ->required()
                                ->default('col'),
                            Select::make('shipping_method')
                                ->label('Metodo de Envío')
                                //->required()
                                ->options(['fedex'=>'Fedex', 'usps'=>'USPS', 'ups'=>'UPS', 'dhl'=>'DHL']),
                                //->default('usps'),
                            Textarea::make('notes')
                                ->columnSpanFull()
                                ->label('Notas'),
                    ])->columns(2),

                    //Forms\Components\TextInput::make('shipping_amount')
                    //   ->numeric(),
    
                    Section::make('Detalles de la Orden')
                        ->schema([
                            Repeater::make('items') //relacion en el modelo
                                ->relationship()
                                ->schema([
                                    Forms\Components\Select::make('product_id')
                                        ->relationship('product', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->required()->distinct()->label('Producto')
                                        ->disableOptionsWhenSelectedInSiblingRepeaterItems() //COMO SE PUEDEN SELECCINAR VAROS ITEMS EN LA ORDEN, A LA SEGUNDA YA NO LO DEJA
                                        ->columnSpan(4)
                                        ->reactive()
                                        ->afterStateUpdated(fn($state,Set $set)  => $set('unit_amount',Product::find($state)?->price ?? 0))
                                        ->afterStateUpdated(fn($state,Set $set) => $set('total_amount',Product::find($state)?->price ?? 0)),
                                    Forms\Components\TextInput::make('quantity')
                                        ->required()->label('Cantidad')
                                        ->numeric()->default(1)->minValue(1)->columnSpan(2)
                                        ->reactive()
                                        ->afterStateUpdated(fn($state,Set $set,Get $get)  => $set('total_amount',$state * $get('unit_amount'))),
                                    Forms\Components\TextInput::make('unit_amount')
                                        ->required()->label('Precio Unitario')->columnSpan(3)
                                        ->numeric()->disabled()->dehydrated(),
                                    Forms\Components\TextInput::make('total_amount')
                                        ->required()->label('Precio Total')
                                        ->numeric()->dehydrated()->columnSpan(3)
                                ])->columns(12),
                                Placeholder::make('grand_total_placeholder')
                                    ->label('Gran Total')
                                    ->content(function (Get $get, Set $set){
                                        $total = 0;
                                        if(!$repeaters = $get('items')){
                                            return $total;
                                        }
                                        foreach ($repeaters as $key => $repeater){
                                            $total += $get("items.{$key}.total_amount");
                                        }
                                        $set('grand_total',$total); //este valor se lo aginana al HIDDEN grand_total
                                        // return Number::currency($total, $get('currency'));
                                        return Number::currency($total, 'COL');
                                    }),
                                Hidden::make('grand_total')->default(0),
                    ])
                ])->columnSpanFull(),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Cliente')
                    ->sortable(),
                Tables\Columns\TextColumn::make('grand_total')
                    ->label('Gran Total')
                    ->numeric()
                    ->sortable()
                    ->money('COL'),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Forma de Pago')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Estado del Pago')
                    ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('currency')
                    ->label('Divisa')->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('shipping_method')
                    ->searchable()->sortable()->label('Metodo de Envío'),
                Tables\Columns\SelectColumn::make('status')
                    ->label('Estado')
                    ->sortable()->searchable()
                    ->options(['new'=>'Nuevo', 'processing'=>'Procesando', 'shipped'=>'Enviado', 'delivered'=>'Entregado', 'canceled'=>'Cancelado']),
                
                //Tables\Columns\TextColumn::make('shipping_amount')
                //    ->numeric()
                //   ->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label('Fecha de Creación')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->label('Fecha de Actualización')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
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
            AddressRelationManager::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'success' : 'danger';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
