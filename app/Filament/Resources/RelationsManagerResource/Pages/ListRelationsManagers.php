<?php

namespace App\Filament\Resources\RelationsManagerResource\Pages;

use App\Filament\Resources\RelationsManagerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Models\Product;
use App\Models\Category;
use App\Models\Catalog;
use App\Models\ProductCategoryCatalogRelation;

class ListRelationsManagers extends ListRecords
{
    protected static string $resource = RelationsManagerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('bulk_assign')
                ->label('Массовое назначение')
                ->icon('heroicon-o-plus')
                ->form([
                    Select::make('product_ids')
                        ->label('Товары')
                        ->multiple()
                        ->options(Product::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),

                    Select::make('category_ids')
                        ->label('Категории')
                        ->multiple()
                        ->options(Category::all()->pluck('name', 'id'))
                        ->searchable(),

                    Select::make('catalog_ids')
                        ->label('Каталоги/Группы')
                        ->multiple()
                        ->options(Catalog::all()->pluck('name', 'id'))
                        ->searchable(),

                    TextInput::make('sort_order')
                        ->label('Порядок сортировки')
                        ->numeric()
                        ->default(0),

                    Toggle::make('is_primary')
                        ->label('Основные связи')
                        ->helperText('Сделать все связи основными'),
                ])
                ->action(function (array $data) {
                    $relations = [];
                    
                    foreach ($data['product_ids'] as $productId) {
                        // Связи с категориями
                        if (!empty($data['category_ids'])) {
                            foreach ($data['category_ids'] as $categoryId) {
                                $relations[] = [
                                    'product_id' => $productId,
                                    'category_id' => $categoryId,
                                    'catalog_id' => null,
                                    'sort_order' => $data['sort_order'],
                                    'is_primary' => $data['is_primary'],
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        }
                        
                        // Связи с каталогами
                        if (!empty($data['catalog_ids'])) {
                            foreach ($data['catalog_ids'] as $catalogId) {
                                $relations[] = [
                                    'product_id' => $productId,
                                    'category_id' => null,
                                    'catalog_id' => $catalogId,
                                    'sort_order' => $data['sort_order'],
                                    'is_primary' => $data['is_primary'],
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        }
                    }
                    
                    if (!empty($relations)) {
                        ProductCategoryCatalogRelation::insert($relations);
                        
                        Notification::make()
                            ->success()
                            ->title('Связи созданы')
                            ->body('Успешно создано ' . count($relations) . ' связей')
                            ->send();
                    }
                }),
        ];
    }
}