<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
class CategoryResource extends Resource
{
    protected static ?string $label = 'Categorías';
    
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 3; //pocicion en le menu de la barra de navegacion

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('General')
                ->schema([
                    Grid::make()->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation,$state,Set $set) => $operation === 'create' ? $set('slug',Str::slug($state)) : null)
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->maxLength(255)
                            ->required()->disabled()->dehydrated()
                            ->unique(Category::class,'slug',ignoreRecord:true)
                    ]),
                    FileUpload::make('image')
                        ->label('Imagen')
                        ->imageEditor()
                        ->directory('categories')
                        ->image(),
                    Toggle::make('is_active')
                        ->label('Activo')->default(true)
                        ->required(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->label('Nombre'),
                Tables\Columns\ImageColumn::make('image')->label('Imagen'),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()->label('Activo'),
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
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),  
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
