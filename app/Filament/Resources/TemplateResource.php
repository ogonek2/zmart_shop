<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateResource\Pages;
use App\Filament\Resources\TemplateResource\RelationManagers;
use App\Models\Template;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static ?string $navigationIcon = 'heroicon-o-template';
    
    protected static ?string $navigationGroup = 'Система';
    
    protected static ?string $navigationLabel = 'Шаблоны';
    
    protected static ?string $pluralLabel = 'Шаблоны';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Название шаблона')
                                    ->required()
                                    ->maxLength(255)
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Template::generateSlug($state))),
                                
                                Forms\Components\TextInput::make('slug')
                                    ->label('Slug (URL)')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                            ]),
                        
                        Forms\Components\RichEditor::make('description')
                            ->label('Описание шаблона')
                            ->columnSpan('full'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Активен')
                            ->default(true),
                    ]),
                
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('Характеристики')
                            ->label('Характеристики товара')
                            ->helperText('Укажите ключи характеристик, которые будут использоваться для товаров с этим шаблоном'),
                        
                        Forms\Components\KeyValue::make('characteristics')
                            ->label('')
                            ->keyLabel('Ключ характеристики')
                            ->valueLabel('Название (для отображения)')
                            ->reorderable()
                            ->addButtonLabel('Добавить характеристику')
                            ->helperText('Например: color => Цвет, size => Размер, weight => Вес'),
                    ]),
                
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('Модернизации')
                            ->label('Модернизации и дополнительные опции')
                            ->helperText('Дополнительные модификации или опции для товаров'),
                        
                        Forms\Components\Repeater::make('modifications')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Название модернизации')
                                    ->required(),
                                
                                Forms\Components\TextInput::make('price')
                                    ->label('Цена')
                                    ->numeric()
                                    ->prefix('₴'),
                                
                                Forms\Components\Textarea::make('description')
                                    ->label('Описание')
                                    ->rows(2),
                            ])
                            ->columns(3)
                            ->createItemButtonLabel('Добавить модернизацию'),
                    ]),
                
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('Дополнительные поля')
                            ->label('Дополнительные настраиваемые поля')
                            ->helperText('Любые дополнительные данные в формате JSON'),
                        
                        Forms\Components\KeyValue::make('additional_fields')
                            ->label('')
                            ->keyLabel('Ключ')
                            ->valueLabel('Значение')
                            ->reorderable()
                            ->addButtonLabel('Добавить поле'),
                    ]),
                
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('SEO по умолчанию')
                            ->label('SEO настройки по умолчанию')
                            ->helperText('Эти настройки будут использоваться как шаблон для категорий и каталогов'),
                        
                        Forms\Components\TextInput::make('seo_title')
                            ->label('SEO Заголовок')
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('seo_description')
                            ->label('SEO Описание')
                            ->rows(3)
                            ->maxLength(500),
                        
                        Forms\Components\Textarea::make('seo_keywords')
                            ->label('SEO Ключевые слова')
                            ->rows(2)
                            ->helperText('Через запятую'),
                        
                        Forms\Components\FileUpload::make('meta_image')
                            ->label('Meta изображение по умолчанию')
                            ->image()
                            ->directory('seo/templates'),
                    ])
                    

            ]);
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
                
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('categories_count')
                    ->label('Категорий')
                    ->getStateUsing(function ($record) {
                        return $record->categories()->count();
                    })
                    ->sortable(query: function ($query, $direction) {
                        return $query->withCount('categories')->orderBy('categories_count', $direction);
                    }),
                
                Tables\Columns\TextColumn::make('catalogs_count')
                    ->label('Каталогов')
                    ->getStateUsing(function ($record) {
                        return $record->catalogs()->count();
                    })
                    ->sortable(query: function ($query, $direction) {
                        return $query->withCount('catalogs')->orderBy('catalogs_count', $direction);
                    }),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Активен')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
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
            ->defaultSort('name', 'asc');
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
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }    
}
