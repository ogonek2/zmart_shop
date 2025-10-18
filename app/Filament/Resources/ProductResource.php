<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Catalog;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Illuminate\Support\Facades\Route;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    
    protected static ?string $navigationGroup = 'Управление товарами';
    
    protected static ?string $navigationLabel = 'Товары';
    
    protected static ?string $pluralLabel = 'Товары';
    

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
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('url', Product::generateHref($state))),
                                
                                Forms\Components\TextInput::make('articule')
                                    ->label('Артикул')
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('price')
                                    ->label('Цена')
                                    ->numeric()
                                    ->required()
                                    ->prefix('₴'),
                                
                                Forms\Components\Toggle::make('is_wholesale')
                                    ->label('Оптовая продажа')
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if (!$state) {
                                            $set('wholesale_price', null);
                                            $set('wholesale_min_quantity', null);
                                        }
                                    }),
                                
                                Forms\Components\TextInput::make('wholesale_price')
                                    ->label('Оптовая цена')
                                    ->numeric()
                                    ->prefix('₴')
                                    ->visible(fn (callable $get) => $get('is_wholesale'))
                                    ->required(fn (callable $get) => $get('is_wholesale')),
                                
                                Forms\Components\TextInput::make('wholesale_min_quantity')
                                    ->label('Минимальное количество для опта')
                                    ->numeric()
                                    ->minValue(1)
                                    ->suffix('шт.')
                                    ->visible(fn (callable $get) => $get('is_wholesale'))
                                    ->required(fn (callable $get) => $get('is_wholesale'))
                                    ->helperText('Минимальное количество товара для покупки по оптовой цене'),
                                
                                Forms\Components\TextInput::make('discount')
                                    ->label('Скидка')
                                    ->numeric()
                                    ->suffix('%'),
                                
                                Forms\Components\TextInput::make('url')
                                    ->label('URL')
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                
                                Forms\Components\TextInput::make('brand')
                                    ->label('Бренд')
                                    ->maxLength(255),
                            ]),
                        
                        Forms\Components\RichEditor::make('description')
                            ->label('Описание')
                            ->columnSpan('full'),
                        
                        Forms\Components\TextInput::make('complectation')
                            ->label('Комплектация')
                            ->maxLength(255),
                        
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('condition_item')
                                    ->label('Состояние')
                                    ->options([
                                        'new' => 'Новый',
                                        'used' => 'Б/У',
                                        'refurbished' => 'Восстановленный',
                                    ]),
                                
                                Forms\Components\Select::make('availability')
                                    ->label('Наличие')
                                    ->options([
                                        'in_stock' => 'В наличии',
                                        'out_of_stock' => 'Нет в наличии',
                                        'on_order' => 'Под заказ',
                                    ])
                                    ->default('in_stock'),
                            ]),
                        
                        // Управление главным изображением перенесено в отдельную страницу галереи
                        
                        
                        // Управление галереей перенесено в отдельную страницу
                        
                    ])
                    ->columnSpan(['lg' => 2]),
                
                Forms\Components\Card::make()
                    ->schema([
                        // При создании - показываем поля загрузки
                        // При редактировании - показываем кнопку для перехода на страницу галереи
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Главное изображение')
                            ->image()
                            ->directory('products')
                            ->disk('public')
                            ->imagePreviewHeight('200')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')
                            ->helperText('Загрузите главное изображение товара')
                            ->hiddenOn('edit')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120), // 5MB
                        
                        Forms\Components\Repeater::make('gallery_images')
                            ->label('Галерея изображений')
                            ->schema([
                                Forms\Components\FileUpload::make('src')
                                    ->label('Изображение')
                                    ->image()
                                    ->directory('products/gallery')
                                    ->disk('public')
                                    ->imagePreviewHeight('150')
                                    ->panelAspectRatio('1:1')
                                    ->panelLayout('integrated')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->maxSize(5120) // 5MB
                                    ->required(),
                            ])
                            ->collapsible()
                            ->collapsed(false)
                            ->helperText('Добавьте дополнительные изображения товара')
                            ->hiddenOn('edit'),
                        
                        // При редактировании показываем кнопку перехода на страницу галереи
                        Forms\Components\Placeholder::make('Управление изображениями')
                            ->content(function ($record) {
                                $editUrl = route('filament.resources.products.manage-gallery', $record->id);
                                
                                return new \Illuminate\Support\HtmlString(
                                    '<div style="padding: 20px; text-align: center;">
                                        <div style="margin-bottom: 15px;">
                                            <i class="fas fa-images" style="font-size: 48px; color: #3b82f6; margin-bottom: 10px;"></i>
                                        </div>
                                        <h3 style="margin: 0 0 10px 0; color: #374151; font-size: 18px;">Управление изображениями</h3>
                                        <p style="margin: 0 0 20px 0; color: #6b7280; font-size: 14px;">Загружайте, редактируйте и управляйте изображениями товара</p>
                                        <a href="' . $editUrl . '" target="_blank" 
                                           style="display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-size: 16px; font-weight: 500; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); transition: all 0.2s;">
                                            <i class="fas fa-images" style="margin-right: 8px;"></i>
                                            Открыть галерею
                                        </a>
                                    </div>'
                                );
                            })
                            ->visibleOn('edit'),
                        
                        Forms\Components\Placeholder::make('Связи'),
                        
                        Forms\Components\Select::make('categories')
                            ->label('Категории')
                            ->multiple()
                            ->relationship('categories', 'name')
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Автоматически загружаем данные из шаблона при изменении категории
                                if (!empty($state)) {
                                    $category = \App\Models\Category::with('template')->find($state[0]);
                                    
                                    if ($category && $category->template) {
                                        // Загружаем данные только если поля пустые
                                        if (empty($get('characteristics'))) {
                                            $set('characteristics', $category->template->characteristics ?? []);
                                        }
                                        if (empty($get('modifications'))) {
                                            $set('modifications', $category->template->modifications ?? []);
                                        }
                                        if (empty($get('additional_fields'))) {
                                            $set('additional_fields', $category->template->additional_fields ?? []);
                                        }
                                    }
                                }
                            }),
                        
                        Forms\Components\Select::make('catalogs')
                            ->label('Каталоги (Группы)')
                            ->multiple()
                            ->relationship('catalogs', 'name')
                            ->searchable(),
                    ])
                    ->columnSpan(['lg' => 1]),
                
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('SEO'),
                        
                        Forms\Components\TextInput::make('seo_title')
                            ->label('SEO Заголовок')
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('seo_description')
                            ->label('SEO Описание')
                            ->rows(3),
                        
                        Forms\Components\Textarea::make('seo_keywords')
                            ->label('SEO Ключевые слова')
                            ->rows(2),
                    ])
                    ->columnSpan(['lg' => 2]),
                
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('Данные из шаблона')
                            ->content('Поля ниже автоматически заполняются из шаблона категории'),
                        
                        Forms\Components\KeyValue::make('characteristics')
                            ->label('Характеристики')
                            ->keyLabel('Название')
                            ->valueLabel('Значение')
                            ->addButtonLabel('Добавить характеристику'),
                        
                        Forms\Components\Repeater::make('modifications')
                            ->label('Модификации')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Название модификации')
                                    ->required(),
                                Forms\Components\TextInput::make('price')
                                    ->label('Цена')
                                    ->numeric(),
                                Forms\Components\TextInput::make('sku')
                                    ->label('Артикул'),
                            ])
                            ->createItemButtonLabel('Добавить модификацию')
                            ->collapsible(),
                        
                        Forms\Components\KeyValue::make('additional_fields')
                            ->label('Дополнительные поля')
                            ->keyLabel('Название')
                            ->valueLabel('Значение')
                            ->addButtonLabel('Добавить поле'),
                    ])
                    ->columnSpan(['lg' => 2]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Фото')
                    ->size(60),
                
                Tables\Columns\TextColumn::make('images_count')
                    ->label('Галерея')
                    ->formatStateUsing(function ($record) {
                        $count = $record ? $record->images()->count() : 0;
                        return $count > 0 ? $count . ' фото' : '—';
                    })
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                Tables\Columns\TextColumn::make('articule')
                    ->label('Артикул')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('price')
                    ->label('Цена')
                    ->money('uah')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_wholesale')
                    ->label('Опт')
                    ->boolean()
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record ? $record->is_wholesale : false),
                
                Tables\Columns\TextColumn::make('wholesale_price')
                    ->label('Оптовая цена')
                    ->money('uah')
                    ->sortable()
                    ->visible(fn ($record) => $record && $record->is_wholesale),
                
                Tables\Columns\TextColumn::make('wholesale_min_quantity')
                    ->label('Мин. кол-во опт')
                    ->sortable()
                    ->suffix(' шт.')
                    ->visible(fn ($record) => $record && $record->is_wholesale),
                
                Tables\Columns\TextColumn::make('discount')
                    ->label('Скидка %')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('availability')
                    ->label('Наличие')
                    ->colors([
                        'success' => 'in_stock',
                        'danger' => 'out_of_stock',
                        'warning' => 'on_order',
                    ])
                    ->enum([
                        'in_stock' => 'В наличии',
                        'out_of_stock' => 'Нет в наличии',
                        'on_order' => 'Под заказ',
                    ]),
                
                Tables\Columns\TextColumn::make('categories.name')
                    ->label('Категории')
                    ->limit(30)
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('catalogs.name')
                    ->label('Каталоги')
                    ->limit(30)
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('characteristics')
                    ->label('Характеристики')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) return '—';
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                return count($decoded) . ' характеристик';
                            }
                            return '—';
                        }
                        if (is_array($state)) {
                            return count($state) . ' характеристик';
                        }
                        return '—';
                    })
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('modifications')
                    ->label('Модификации')
                    ->formatStateUsing(function ($state) {
                        if (empty($state)) return '—';
                        if (is_string($state)) {
                            $decoded = json_decode($state, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                return count($decoded) . ' модификаций';
                            }
                            return '—';
                        }
                        if (is_array($state)) {
                            return count($state) . ' модификаций';
                        }
                        return '—';
                    })
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('availability')
                    ->label('Наличие')
                    ->options([
                        'in_stock' => 'В наличии',
                        'out_of_stock' => 'Нет в наличии',
                        'on_order' => 'Под заказ',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_wholesale')
                    ->label('Оптовая продажа')
                    ->placeholder('Все товары')
                    ->trueLabel('Только оптовые')
                    ->falseLabel('Только розничные'),
                
                Tables\Filters\SelectFilter::make('categories')
                    ->label('Категория')
                    ->options(function () {
                        return \App\Models\Category::pluck('name', 'id')->toArray();
                    })
                    ->multiple()
                    ->query(function ($query, $data) {
                        if (filled($data['values'])) {
                            return $query->whereHas('categories', function ($q) use ($data) {
                                $q->whereIn('categories.id', $data['values']);
                            });
                        }
                    }),
                
                Tables\Filters\SelectFilter::make('catalogs')
                    ->label('Каталог')
                    ->options(function () {
                        return \App\Models\Catalog::pluck('name', 'id')->toArray();
                    })
                    ->multiple()
                    ->query(function ($query, $data) {
                        if (filled($data['values'])) {
                            return $query->whereHas('catalogs', function ($q) use ($data) {
                                $q->whereIn('catalogs.id', $data['values']);
                            });
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('manage_relations')
                    ->label('Связи')
                    ->icon('heroicon-o-link')
                    ->url(fn ($record) => route('filament.resources.products.manage-relations', $record))
                    ->color('info'),
                Tables\Actions\Action::make('manage_gallery')
                    ->label('Галерея')
                    ->icon('heroicon-o-photograph')
                    ->url(fn ($record) => route('filament.resources.products.manage-gallery', $record))
                    ->color('success'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'manage-relations' => Pages\ManageRelations::route('/{record}/manage-relations'),
            'manage-gallery' => Pages\ManageGallery::route('/{record}/manage-gallery'),
            'hierarchy' => Pages\HierarchyManager::route('/hierarchy'),
            'tree' => Pages\TreeManager::route('/tree'),
            'vue-tree' => Pages\VueTreeManager::route('/vue-tree'),
        ];
    }
    
    
    public static function getGlobalSearchResultTitle($record): string
    {
        return $record->name;
    }
    
    public static function getGlobalSearchResultDetails($record): array
    {
        return [
            'Артикул' => $record->articule,
            'Цена' => $record->price . ' ₴',
        ];
    }
}