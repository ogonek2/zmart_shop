<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\Template;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationGroup = 'Управление категориями';
    
    protected static ?string $navigationLabel = 'Категории';
    
    protected static ?string $pluralLabel = 'Категории';


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
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('url', Category::generateHref($state))),
                                
                                Forms\Components\TextInput::make('url')
                                    ->label('URL')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),
                        
                        Forms\Components\RichEditor::make('description')
                            ->label('Описание')
                            ->columnSpan('full'),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('template_id')
                                    ->label('Шаблон')
                                    ->options(function () {
                                        return \App\Models\Template::pluck('name', 'id')->toArray();
                                    })
                                    ->searchable()
                                    ->nullable()
                                    ->helperText('Выберите шаблон для применения характеристик и SEO настроек'),
                                
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Порядок сортировки')
                                    ->numeric()
                                    ->default(0),
                            ]),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Активна')
                            ->default(true),
                    ])
                    ->columnSpan(['lg' => 2]),
                
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('SEO Настройки')
                            ->label('SEO'),
                        
                        Forms\Components\TextInput::make('seo_title')
                            ->label('SEO Заголовок')
                            ->maxLength(255)
                            ->helperText('Оставьте пустым для использования названия категории'),
                        
                        Forms\Components\Textarea::make('seo_description')
                            ->label('SEO Описание')
                            ->rows(3)
                            ->maxLength(500),
                        
                        Forms\Components\Textarea::make('seo_keywords')
                            ->label('SEO Ключевые слова')
                            ->rows(2)
                            ->helperText('Через запятую'),
                        
                        Forms\Components\FileUpload::make('meta_image')
                            ->label('Meta изображение (OG:Image)')
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
                
                Tables\Columns\TextColumn::make('url')
                    ->label('URL')
                    ->searchable()
                    ->limit(40)
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('template.name')
                    ->label('Шаблон')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Товаров')
                    ->getStateUsing(function ($record) {
                        return $record->products()->count();
                    })
                    ->sortable(query: function ($query, $direction) {
                        return $query->withCount('products')->orderBy('products_count', $direction);
                    }),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Порядок')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активна')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создана')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('template_id')
                    ->label('Шаблон')
                    ->options(function () {
                        return \App\Models\Template::pluck('name', 'id')->toArray();
                    })
                    ->multiple(),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Активна')
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
            // RelationManagers\ProductsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
