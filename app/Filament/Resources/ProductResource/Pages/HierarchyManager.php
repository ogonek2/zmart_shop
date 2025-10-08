<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\Catalog;
use App\Models\ProductCategoryCatalogRelation;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Http\Request;

class HierarchyManager extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.resources.product-resource.pages.hierarchy-manager';

    protected static ?string $navigationLabel = 'Иерархия связей';

    protected static ?string $title = 'Управление иерархией связей';

    public $selectedProduct = null;
    public $hierarchyData = [];
    public $searchQuery = '';

    public function mount()
    {
        $this->loadHierarchyData();
    }

    public function loadHierarchyData()
    {
        $this->hierarchyData = [
            'categories' => Category::with(['template', 'products'])->get()->map(function ($category) {
                return [
                    'id' => 'category_' . $category->id,
                    'type' => 'category',
                    'name' => $category->name,
                    'template' => $category->template?->name ?? 'Без шаблона',
                    'products_count' => $category->products()->count(),
                    'children' => $category->products()->get()->map(function ($product) {
                        return [
                            'id' => 'product_' . $product->id,
                            'type' => 'product',
                            'name' => $product->name,
                            'sku' => $product->articule,
                            'price' => $product->price,
                            'image' => $product->image_path,
                        ];
                    })->toArray()
                ];
            })->toArray(),
            'catalogs' => Catalog::with(['products'])->whereNull('parent_id')->get()->map(function ($catalog) {
                return $this->buildCatalogTree($catalog);
            })->toArray()
        ];
    }

    private function buildCatalogTree($catalog)
    {
        $children = [];
        
        // Добавляем товары каталога
        foreach ($catalog->products as $product) {
            $children[] = [
                'id' => 'product_' . $product->id,
                'type' => 'product',
                'name' => $product->name,
                'sku' => $product->articule,
                'price' => $product->price,
                'image' => $product->image_path,
            ];
        }
        
        // Добавляем подкаталоги
        $childCatalogs = Catalog::where('parent_id', $catalog->id)->with(['products'])->get();
        foreach ($childCatalogs as $childCatalog) {
            $children[] = $this->buildCatalogTree($childCatalog);
        }
        
        return [
            'id' => 'catalog_' . $catalog->id,
            'type' => 'catalog',
            'name' => $catalog->name,
            'template' => 'Без шаблона', // Временно убираем загрузку шаблона
            'type_label' => $catalog->type ?? 'Группа',
            'products_count' => $catalog->products()->count(),
            'children' => $children
        ];
    }

    public function moveItem($draggedId, $targetId, $position = 'inside')
    {
        try {
            $draggedType = explode('_', $draggedId)[0];
            $draggedItemId = explode('_', $draggedId)[1];
            $targetType = explode('_', $targetId)[0];
            $targetItemId = explode('_', $targetId)[1];

            if ($draggedType === 'product') {
                $this->moveProduct($draggedItemId, $targetType, $targetItemId, $position);
            } elseif ($draggedType === 'catalog') {
                $this->moveCatalog($draggedItemId, $targetType, $targetItemId, $position);
            }

            $this->loadHierarchyData();
            
            Notification::make()
                ->success()
                ->title('Элемент перемещен')
                ->body('Иерархия успешно обновлена')
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Не удалось переместить элемент: ' . $e->getMessage())
                ->send();
        }
    }

    private function moveProduct($productId, $targetType, $targetId, $position)
    {
        $product = Product::find($productId);
        if (!$product) return;

        // Удаляем старые связи
        ProductCategoryCatalogRelation::where('product_id', $productId)->delete();

        if ($targetType === 'category') {
            // Привязываем к категории
            $product->categories()->sync([$targetId]);
            ProductCategoryCatalogRelation::create([
                'product_id' => $productId,
                'category_id' => $targetId,
                'catalog_id' => null,
                'is_primary' => true,
            ]);
        } elseif ($targetType === 'catalog') {
            // Привязываем к каталогу
            $product->catalogs()->sync([$targetId]);
            ProductCategoryCatalogRelation::create([
                'product_id' => $productId,
                'category_id' => null,
                'catalog_id' => $targetId,
                'is_primary' => true,
            ]);
        }
    }

    private function moveCatalog($catalogId, $targetType, $targetId, $position)
    {
        $catalog = Catalog::find($catalogId);
        if (!$catalog) return;

        if ($targetType === 'catalog' && $position === 'inside') {
            // Перемещаем каталог внутрь другого каталога
            $catalog->update(['parent_id' => $targetId]);
        } elseif ($targetType === 'catalog' && $position === 'after') {
            // Перемещаем каталог после другого каталога
            $targetCatalog = Catalog::find($targetId);
            if ($targetCatalog) {
                $catalog->update(['parent_id' => $targetCatalog->parent_id]);
            }
        }
    }

    public function search()
    {
        if (empty($this->searchQuery)) {
            $this->loadHierarchyData();
            return;
        }

        $query = $this->searchQuery;
        
        $this->hierarchyData = [
            'categories' => Category::where('name', 'like', "%{$query}%")
                ->with(['template', 'products'])
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => 'category_' . $category->id,
                        'type' => 'category',
                        'name' => $category->name,
                        'template' => $category->template?->name ?? 'Без шаблона',
                        'products_count' => $category->products()->count(),
                        'children' => $category->products()->get()->map(function ($product) {
                            return [
                                'id' => 'product_' . $product->id,
                                'type' => 'product',
                                'name' => $product->name,
                                'sku' => $product->articule,
                                'price' => $product->price,
                                'image' => $product->image_path,
                            ];
                        })->toArray()
                    ];
                })->toArray(),
            'catalogs' => Catalog::where('name', 'like', "%{$query}%")
                ->with(['products'])
                ->get()
                ->map(function ($catalog) {
                    return $this->buildCatalogTree($catalog);
                })->toArray()
        ];
    }

    protected function getActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Обновить')
                ->icon('heroicon-o-refresh')
                ->action('loadHierarchyData'),
        ];
    }
}