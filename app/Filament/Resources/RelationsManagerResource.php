<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RelationsManagerResource\Pages;
use App\Models\ProductCategoryCatalogRelation;
use App\Models\Product;
use App\Models\Category;
use App\Models\Catalog;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class RelationsManagerResource extends Resource
{
    protected static ?string $model = ProductCategoryCatalogRelation::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationLabel = 'Управление связями';

    protected static ?string $modelLabel = 'Связь';

    protected static ?string $pluralModelLabel = 'Связи товаров';

    protected static ?string $navigationGroup = 'Каталог';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Select::make('product_id')
                            ->label('Товар')
                            ->options(Product::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $product = Product::find($state);
                                    if ($product) {
                                        $set('product_name', $product->name);
                                        $set('product_sku', $product->sku);
                                    }
                                }
                            }),

                        TextInput::make('product_name')
                            ->label('Название товара')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('product_sku')
                            ->label('Артикул')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(3),

                Forms\Components\Card::make()
                    ->schema([
                        Select::make('category_id')
                            ->label('Категория')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $category = Category::find($state);
                                    if ($category) {
                                        $set('category_name', $category->name);
                                        $set('category_template', $category->template?->name ?? 'Без шаблона');
                                    }
                                }
                            }),

                        TextInput::make('category_name')
                            ->label('Название категории')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('category_template')
                            ->label('Шаблон категории')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(3),

                Forms\Components\Card::make()
                    ->schema([
                        Select::make('catalog_id')
                            ->label('Каталог/Группа')
                            ->options(Catalog::all()->pluck('name', 'id'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $catalog = Catalog::find($state);
                                    if ($catalog) {
                                        $set('catalog_name', $catalog->name);
                                        $set('catalog_template', $catalog->template?->name ?? 'Без шаблона');
                                        $set('catalog_type', $catalog->type ?? 'Группа');
                                    }
                                }
                            }),

                        TextInput::make('catalog_name')
                            ->label('Название каталога')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('catalog_template')
                            ->label('Шаблон каталога')
                            ->disabled()
                            ->dehydrated(false),

                        TextInput::make('catalog_type')
                            ->label('Тип каталога')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(4),

                Forms\Components\Card::make()
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Порядок сортировки')
                            ->numeric()
                            ->default(0),

                        Toggle::make('is_primary')
                            ->label('Основная связь')
                            ->helperText('Отметьте, если это основная связь для товара'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Товар')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                Tables\Columns\TextColumn::make('product.sku')
                    ->label('Артикул')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Категория')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Не указана'),

                Tables\Columns\TextColumn::make('catalog.name')
                    ->label('Каталог/Группа')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Не указан'),

                Tables\Columns\TextColumn::make('catalog.type')
                    ->label('Тип каталога')
                    ->placeholder('—'),

                Tables\Columns\IconColumn::make('is_primary')
                    ->label('Основная')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создано')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('product_id')
                    ->label('Товар')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->searchable(),

                SelectFilter::make('category_id')
                    ->label('Категория')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->searchable(),

                SelectFilter::make('catalog_id')
                    ->label('Каталог')
                    ->options(Catalog::all()->pluck('name', 'id'))
                    ->searchable(),

                Tables\Filters\TernaryFilter::make('is_primary')
                    ->label('Основная связь')
                    ->placeholder('Все связи')
                    ->trueLabel('Только основные')
                    ->falseLabel('Только дополнительные'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRelationsManagers::route('/'),
            'create' => Pages\CreateRelationsManager::route('/create'),
            'edit' => Pages\EditRelationsManager::route('/{record}/edit'),
        ];
    }
}