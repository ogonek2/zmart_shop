<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatalogResource\Pages;
use App\Filament\Resources\CatalogResource\RelationManagers;
use App\Models\Catalog;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class CatalogResource extends Resource
{
    protected static ?string $model = Catalog::class;

    protected static ?string $navigationIcon = 'heroicon-o-view-grid';
    
    protected static ?string $navigationGroup = 'Управление категориями';
    
    protected static ?string $navigationLabel = 'Каталоги';
    
    protected static ?string $pluralLabel = 'Каталоги';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Название')
                                    ->required()
                                    ->maxLength(255)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('url', Catalog::generateHref($state))),
                                
                                Forms\Components\TextInput::make('url')
                                    ->label('URL')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),
                        
                        Forms\Components\RichEditor::make('description')
                            ->label('Описание')
                            ->columnSpan('full'),
                        
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('parent_id')
                                    ->label('Родительский каталог')
                                    ->options(function () {
                                        return \App\Models\Catalog::where('type', 'group')
                                            ->pluck('name', 'id')
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->nullable()
                                    ->helperText('Оставьте пустым для создания группы верхнего уровня')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $set('type', 'subgroup');
                                        } else {
                                            $set('type', 'group');
                                        }
                                    }),
                                
                                Forms\Components\Select::make('type')
                                    ->label('Тип')
                                    ->options([
                                        'group' => 'Группа',
                                        'subgroup' => 'Подгруппа',
                                    ])
                                    ->default('group')
                                    ->required()
                                    ->disabled(fn (callable $get) => $get('parent_id') !== null),
                                
                                Forms\Components\Select::make('template_id')
                                    ->label('Шаблон')
                                    ->options(function () {
                                        return \App\Models\Template::pluck('name', 'id')->toArray();
                                    })
                                    ->searchable()
                                    ->nullable()
                                    ->helperText('Шаблон для характеристик и SEO'),
                            ]),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Порядок сортировки')
                                    ->numeric()
                                    ->default(0),
                                
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Активен')
                                    ->default(true),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),
                
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('SEO Настройки')
                            ->label('SEO'),
                        
                        Forms\Components\TextInput::make('seo_title')
                            ->label('SEO Заголовок')
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('seo_description')
                            ->label('SEO Описание')
                            ->rows(3)
                            ->maxLength(500),
                        
                        Forms\Components\Textarea::make('seo_keywords')
                            ->label('SEO Ключевые слова')
                            ->rows(2),
                        
                        Forms\Components\FileUpload::make('meta_image')
                            ->label('Meta изображение')
                            ->image()
                            ->directory('seo'),
                    ])
                    ->columnSpan(['lg' => 1])
                    
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('parentCatalog.name')
                    ->label('Родитель')
                    ->searchable()
                    ->sortable()
                    ->default('—'),
                
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Тип')
                    ->colors([
                        'primary' => 'group',
                        'secondary' => 'subgroup',
                    ])
                    ->enum([
                        'group' => 'Группа',
                        'subgroup' => 'Подгруппа',
                    ]),
                
                Tables\Columns\TextColumn::make('template.name')
                    ->label('Шаблон')
                    ->sortable()
                    ->searchable()
                    ->default('—'),
                
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Товаров')
                    ->getStateUsing(function ($record) {
                        return $record->products()->count();
                    })
                    ->sortable(query: function ($query, $direction) {
                        return $query->withCount('products')->orderBy('products_count', $direction);
                    }),
                
                Tables\Columns\TextColumn::make('children_count')
                    ->label('Подгрупп')
                    ->getStateUsing(function ($record) {
                        return \App\Models\Catalog::where('parent_id', $record->id)->count();
                    }),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Тип')
                    ->options([
                        'group' => 'Группа',
                        'subgroup' => 'Подгруппа',
                    ]),
                
                Tables\Filters\SelectFilter::make('parent_id')
                    ->label('Родитель')
                    ->options(function () {
                        return \App\Models\Catalog::where('type', 'group')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->multiple(),
                
                Tables\Filters\SelectFilter::make('template_id')
                    ->label('Шаблон')
                    ->options(function () {
                        return \App\Models\Template::pluck('name', 'id')->toArray();
                    })
                    ->multiple(),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Активен')
                    ->placeholder('Все')
                    ->trueLabel('Только активные')
                    ->falseLabel('Только неактивные'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListCatalogs::route('/'),
            'create' => Pages\CreateCatalog::route('/create'),
            'edit' => Pages\EditCatalog::route('/{record}/edit'),
        ];
    }
}
