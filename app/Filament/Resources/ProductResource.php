<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $label = 'Productos';

    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Información Del Producto')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label('Nombre')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                                ->maxLength(255),
                            Forms\Components\TextInput::make('slug')
                                ->required()->label('Slug')
                                ->disabled()
                                ->maxLength(255)
                                ->dehydrated()
                                ->unique(Product::class, 'slug', ignoreRecord: true),
                            Forms\Components\MarkdownEditor::make('description')
                                ->columnSpanFull()->label('Descripción')
                                ->fileAttachmentsDirectory('products')
                        ])->columns(2),

                    Section::make('Imagenes')->schema([
                        Forms\Components\FileUpload::make('images')
                            ->multiple()->label('Subir Imagenes')
                            ->directory('products')
                            ->maxFiles(5)->reorderable()->imageEditor(),

                    ]),

                ])->columnSpan(2),
                Group::make()->schema([
                    Section::make('Precio')
                        ->schema([
                            Forms\Components\TextInput::make('price')
                                ->label('Precio')
                                ->required()
                                ->numeric()->prefix('COL'),
                        ]),
                    Section::make('Asociaciones')
                        ->schema([
                            Select::make('category_id')
                                ->label('Categoría')->required()->searchable()->preload()
                                ->relationship('category', 'name'), //la relacion esta en el modelo
                            //->options(Category::query()->pluck('name', 'id')), //otra manera de traer las opciones
                            Select::make('brand_id')
                                ->label('Marca')->required()->searchable()->preload()
                                ->relationship('brand', 'name'), //la relacion esta en el modelo
                            //->options(Brand::query()->pluck('name', 'id')), //otra manera de traer las opciones

                        ]),
                    Section::make('Estados')->schema([
                        Forms\Components\Toggle::make('in_stock')
                            ->required()->default(true)->label('En Stock'),
                        Forms\Components\Toggle::make('is_active')
                            ->required()->default(true)->label('Activo'),
                        Forms\Components\Toggle::make('is_featured')
                            ->required()->label('Destacado'),
                        Forms\Components\Toggle::make('on_sale')
                            ->required()->label('En Venta'),
                    ]),
                ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->label('Nombre'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Categoría')
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Marca')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('COL')->label('Precio')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()->label('Destacado'),
                Tables\Columns\IconColumn::make('on_sale')
                    ->boolean()->label('En Venta'),
                Tables\Columns\IconColumn::make('in_stock')
                    ->boolean()->label('En Stock'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()->label('Activo'),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()->label('Creado el')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()->label('Actualizado el')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')->label('Categoría')
                    ->relationship('category', 'name'),
                SelectFilter::make('brand')->label('Marca')
                    ->relationship('brand', 'name'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                        Tables\Actions\ViewAction::make()->label('Ver'),
                        Tables\Actions\EditAction::make()->label('Editar'),
                        Tables\Actions\DeleteAction::make()->label('Eliminar'),
                    ])
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
