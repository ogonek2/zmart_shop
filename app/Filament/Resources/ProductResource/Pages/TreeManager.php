<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\Catalog;
use App\Models\Template;
use App\Models\ProductCategoryCatalogRelation;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class TreeManager extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.resources.product-resource.pages.tree-manager';

    protected static ?string $navigationLabel = 'Древовидное управление';

    protected static ?string $title = 'Управление иерархией';

    public $selectedItem = null;
    public $selectedType = null;
    public $selectedId = null;
    public $treeData = [];
    public $detailData = [];
    
    // Для модального окна редактирования
    public $showEditModal = false;
    public $editingCategoryId = null;
    public $editForm = [
        'name' => '',
        'parent_id' => null,
        'template_id' => null,
        'seo_title' => '',
        'seo_description' => '',
        'seo_keywords' => '',
        'description' => '',
        'is_active' => true,
        'sort_order' => 0,
    ];

    public function mount()
    {
        $this->detailData = [
            'type' => 'category',
            'item' => null,
            'products' => [],
            'subcategories' => []
        ];
        $this->loadTreeData();
    }

    public function loadTreeData()
    {
        $this->treeData = [
            'categories' => $this->buildCategoryTree()
        ];
    }

    private function buildCategoryTree()
    {
        return Category::with(['products', 'childCategories'])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($category) {
                return $this->buildCategoryNode($category);
            })->toArray();
    }

    private function buildCategoryNode($category)
    {
        $children = Category::where('parent_id', $category->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function ($child) {
                return $this->buildCategoryNode($child);
            })->toArray();

        return [
            'id' => 'category_' . $category->id,
            'type' => 'category',
            'name' => $category->name,
            'template' => 'Без шаблона',
            'products_count' => $category->products()->count(),
            'children' => $children
        ];
    }


    public function selectItem($itemId, $itemType)
    {
        $this->selectedId = $itemId;
        $this->selectedType = $itemType;
        $this->selectedItem = $this->findItemInTree($itemId, $itemType);
        $this->loadDetailData();
    }

    private function findItemInTree($itemId, $itemType)
    {
        if ($itemType === 'category') {
            $categoryId = str_replace('category_', '', $itemId);
            return Category::with(['products', 'childCategories'])->find($categoryId);
        }
        return null;
    }

    public function loadDetailData()
    {
        if (!$this->selectedItem) {
            $this->detailData = [
                'type' => 'category',
                'item' => null,
                'products' => [],
                'subcategories' => []
            ];
            return;
        }

        $this->detailData = [
            'type' => 'category',
            'item' => $this->selectedItem,
            'products' => $this->selectedItem->products()->with(['categories', 'catalogs'])->get()->toArray(),
            'subcategories' => $this->selectedItem->childCategories()->with(['products'])->orderBy('sort_order')->get()->toArray()
        ];
    }

    public function moveCategory($categoryId, $targetCategoryId, $position = 'inside')
    {
        try {
            $category = Category::find($categoryId);
            if (!$category) return;

            if ($position === 'inside') {
                // Перемещаем категорию внутрь другой категории
                $category->update(['parent_id' => $targetCategoryId]);
            } elseif ($position === 'after') {
                // Перемещаем категорию после другой категории
                $targetCategory = Category::find($targetCategoryId);
                if ($targetCategory) {
                    $category->update(['parent_id' => $targetCategory->parent_id]);
                }
            }

            $this->loadTreeData();
            
            Notification::make()
                ->success()
                ->title('Категория перемещена')
                ->body('Иерархия успешно обновлена')
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Не удалось переместить категорию: ' . $e->getMessage())
                ->send();
        }
    }

    public function updateCategoryOrder($categoryId, $newParentId, $newIndex)
    {
        try {
            \Log::info('updateCategoryOrder called', [
                'categoryId' => $categoryId,
                'newParentId' => $newParentId,
                'newIndex' => $newIndex
            ]);
            
            $category = Category::find($categoryId);
            if (!$category) {
                Notification::make()
                    ->danger()
                    ->title('Ошибка')
                    ->body('Категория не найдена')
                    ->send();
                return;
            }

            // Проверяем, что не перемещаем категорию в саму себя или в дочернюю
            if ($this->wouldCreateCircularReference($categoryId, $newParentId)) {
                Notification::make()
                    ->warning()
                    ->title('Невозможно переместить')
                    ->body('Нельзя переместить категорию в саму себя или в дочернюю категорию')
                    ->send();
                return;
            }

            // Обновляем родителя
            $oldParentId = $category->parent_id;
            $category->parent_id = $newParentId;
            
            // Получаем все категории с новым родителем (исключая перемещаемую)
            $siblings = Category::where('parent_id', $newParentId)
                ->where('id', '!=', $categoryId)
                ->orderBy('sort_order')
                ->get();
                
            \Log::info('Siblings found', [
                'parent_id' => $newParentId,
                'count' => $siblings->count(),
                'siblings' => $siblings->pluck('id')->toArray()
            ]);
            
            // Пересчитываем sort_order для всех siblings
            $sortOrder = 10;
            $inserted = false;
            
            foreach ($siblings as $index => $sibling) {
                if ($index == $newIndex && !$inserted) {
                    // Вставляем перемещаемую категорию на нужную позицию
                    $category->sort_order = $sortOrder;
                    $sortOrder += 10;
                    $inserted = true;
                }
                $sibling->sort_order = $sortOrder;
                $sibling->save();
                $sortOrder += 10;
            }
            
            // Если новый индекс больше количества siblings, добавляем в конец
            if (!$inserted) {
                $category->sort_order = $sortOrder;
            }
            
            $category->save();
            
            // Исправляем сортировку в старой родительской категории
            if ($oldParentId !== $newParentId) {
                $this->fixSortingForParent($oldParentId);
            }
            
            \Log::info('Категория обновлена', [
                'id' => $category->id,
                'parent_id' => $category->parent_id,
                'sort_order' => $category->sort_order
            ]);
            
            $this->loadTreeData();
            
            Notification::make()
                ->success()
                ->title('Порядок обновлен')
                ->body('Категория успешно перемещена')
                ->send();
                
        } catch (\Exception $e) {
            \Log::error('Ошибка при обновлении порядка', [
                'categoryId' => $categoryId,
                'newParentId' => $newParentId,
                'newIndex' => $newIndex,
                'error' => $e->getMessage()
            ]);
            
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Не удалось обновить порядок: ' . $e->getMessage())
                ->send();
        }
    }
    
    private function wouldCreateCircularReference($categoryId, $newParentId)
    {
        if ($categoryId == $newParentId) {
            return true;
        }
        
        if (!$newParentId) {
            return false;
        }
        
        // Проверяем, не является ли newParentId дочерней категорией categoryId
        $children = Category::where('parent_id', $categoryId)->pluck('id')->toArray();
        
        while (!empty($children)) {
            if (in_array($newParentId, $children)) {
                return true;
            }
            
            $children = Category::whereIn('parent_id', $children)->pluck('id')->toArray();
        }
        
        return false;
    }
    
    private function fixSortingForParent($parentId)
    {
        $children = Category::where('parent_id', $parentId)
            ->orderBy('sort_order')
            ->get();
            
        $sortOrder = 10;
        foreach ($children as $child) {
            $child->sort_order = $sortOrder;
            $child->save();
            $sortOrder += 10;
        }
    }

    public function fixCategorySorting()
    {
        try {
            \Log::info('Исправление сортировки категорий...');
            
            // Получаем все категории, сгруппированные по parent_id
            $categoriesByParent = Category::orderBy('parent_id')
                ->orderBy('sort_order')
                ->get()
                ->groupBy('parent_id');
            
            foreach ($categoriesByParent as $parentId => $categories) {
                $sortOrder = 10;
                foreach ($categories as $category) {
                    $category->sort_order = $sortOrder;
                    $category->save();
                    $sortOrder += 10;
                }
                
                \Log::info("Исправлена сортировка для parent_id: {$parentId}", [
                    'count' => $categories->count(),
                    'sort_orders' => $categories->pluck('sort_order')->toArray()
                ]);
            }
            
            $this->loadTreeData();
            
            Notification::make()
                ->success()
                ->title('Сортировка исправлена')
                ->body('Все категории получили корректные позиции сортировки')
                ->send();
                
        } catch (\Exception $e) {
            \Log::error('Ошибка при исправлении сортировки', [
                'error' => $e->getMessage()
            ]);
            
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Не удалось исправить сортировку: ' . $e->getMessage())
                ->send();
        }
    }

    public function moveProductToCategory($productId, $categoryId)
    {
        try {
            \Log::info('moveProductToCategory called', [
                'productId' => $productId,
                'categoryId' => $categoryId
            ]);

            $product = \App\Models\Product::find($productId);
            $category = \App\Models\Category::find($categoryId);

            if (!$product) {
                Notification::make()
                    ->danger()
                    ->title('Ошибка')
                    ->body('Товар не найден')
                    ->send();
                return;
            }

            if (!$category) {
                Notification::make()
                    ->danger()
                    ->title('Ошибка')
                    ->body('Категория не найдена')
                    ->send();
                return;
            }

            // Получаем текущую категорию товара (если есть)
            $currentCategory = $this->selectedItem;
            $currentCategoryName = $currentCategory ? $currentCategory->name : 'неизвестная категория';

            // Перемещаем товар (удаляем из всех категорий и добавляем к новой)
            $product->categories()->sync([$categoryId]);

            // Обновляем данные
            $this->loadTreeData();
            if ($this->selectedItem) {
                $this->loadDetailData();
            }

            Notification::make()
                ->success()
                ->title('Товар перемещен')
                ->body('Товар "' . $product->name . '" перемещен из "' . $currentCategoryName . '" в "' . $category->name . '"')
                ->send();

        } catch (\Exception $e) {
            \Log::error('Ошибка при перемещении товара в категорию', [
                'productId' => $productId,
                'categoryId' => $categoryId,
                'error' => $e->getMessage()
            ]);

            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Не удалось переместить товар в категорию: ' . $e->getMessage())
                ->send();
        }
    }

    public function createCategory($name, $parentId = null)
    {
        try {
            // Проверяем, что имя не пустое
            if (empty($name) || trim($name) === '') {
                Notification::make()
                    ->warning()
                    ->title('Ошибка')
                    ->body('Название категории не может быть пустым')
                    ->send();
                return;
            }

            // Определяем правильный sort_order для новой категории
            $maxSortOrder = Category::where('parent_id', $parentId)->max('sort_order') ?? 0;
            $newSortOrder = $maxSortOrder + 10;
            
            // Если это первая категория в группе, начинаем с 10
            if ($maxSortOrder == 0) {
                $newSortOrder = 10;
            }

            $category = Category::create([
                'name' => trim($name),
                'parent_id' => $parentId,
                'is_active' => true,
                'sort_order' => $newSortOrder,
            ]);

            $this->loadTreeData();
            if ($this->selectedItem && $this->selectedItem->id == $parentId) {
                $this->loadDetailData();
            }
            
            Notification::make()
                ->success()
                ->title('Категория создана')
                ->body('Категория "' . $name . '" успешно создана')
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Не удалось создать категорию: ' . $e->getMessage())
                ->send();
        }
    }

    public function openEditModal($categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Категория не найдена')
                ->send();
            return;
        }

        $this->editingCategoryId = $categoryId;
        $this->editForm = [
            'name' => $category->name,
            'parent_id' => $category->parent_id,
            'template_id' => $category->template_id,
            'seo_title' => $category->seo_title ?? '',
            'seo_description' => $category->seo_description ?? '',
            'seo_keywords' => $category->seo_keywords ?? '',
            'description' => $category->description ?? '',
            'is_active' => $category->is_active ?? true,
            'sort_order' => $category->sort_order ?? 0,
        ];
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editingCategoryId = null;
        $this->editForm = [
            'name' => '',
            'parent_id' => null,
            'template_id' => null,
            'seo_title' => '',
            'seo_description' => '',
            'seo_keywords' => '',
            'description' => '',
            'is_active' => true,
            'sort_order' => 0,
        ];
    }

    public function saveCategory()
    {
        try {
            // Валидация
            if (empty($this->editForm['name']) || trim($this->editForm['name']) === '') {
                Notification::make()
                    ->warning()
                    ->title('Ошибка')
                    ->body('Название категории не может быть пустым')
                    ->send();
                return;
            }

            $category = Category::find($this->editingCategoryId);
            if (!$category) {
                Notification::make()
                    ->danger()
                    ->title('Ошибка')
                    ->body('Категория не найдена')
                    ->send();
                return;
            }

            $category->update([
                'name' => trim($this->editForm['name']),
                'parent_id' => $this->editForm['parent_id'],
                'template_id' => $this->editForm['template_id'],
                'seo_title' => $this->editForm['seo_title'],
                'seo_description' => $this->editForm['seo_description'],
                'seo_keywords' => $this->editForm['seo_keywords'],
                'description' => $this->editForm['description'],
                'is_active' => $this->editForm['is_active'],
                'sort_order' => $this->editForm['sort_order'],
            ]);

            $this->loadTreeData();
            $this->loadDetailData();
            $this->closeEditModal();
            
            Notification::make()
                ->success()
                ->title('Категория обновлена')
                ->body('Категория успешно обновлена')
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Не удалось обновить категорию: ' . $e->getMessage())
                ->send();
        }
    }
    
    public function updateCategory($categoryId, $name)
    {
        // Быстрое обновление только названия (для старых кнопок)
        try {
            if (empty($name) || trim($name) === '') {
                Notification::make()
                    ->warning()
                    ->title('Ошибка')
                    ->body('Название категории не может быть пустым')
                    ->send();
                return;
            }

            $category = Category::find($categoryId);
            if (!$category) {
                Notification::make()
                    ->danger()
                    ->title('Ошибка')
                    ->body('Категория не найдена')
                    ->send();
                return;
            }

            $category->update(['name' => trim($name)]);
            $this->loadTreeData();
            $this->loadDetailData();
            
            Notification::make()
                ->success()
                ->title('Категория обновлена')
                ->body('Категория успешно обновлена')
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Не удалось обновить категорию: ' . $e->getMessage())
                ->send();
        }
    }

    public function deleteCategory($categoryId)
    {
        try {
            $category = Category::find($categoryId);
            if (!$category) return;

            // Перемещаем дочерние категории на уровень выше
            $category->childCategories()->update(['parent_id' => $category->parent_id]);
            
            // Удаляем категорию
            $category->delete();
            
            $this->loadTreeData();
            
            Notification::make()
                ->success()
                ->title('Категория удалена')
                ->body('Категория и все её дочерние элементы успешно удалены')
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Не удалось удалить категорию: ' . $e->getMessage())
                ->send();
        }
    }

    public function removeProductFromItem($productId)
    {
        try {
            $product = Product::find($productId);
            if (!$product) return;

            if ($this->selectedType === 'category') {
                $product->categories()->detach($this->selectedItem->id);
            } elseif ($this->selectedType === 'catalog') {
                $product->catalogs()->detach($this->selectedItem->id);
            }

            ProductCategoryCatalogRelation::where('product_id', $productId)
                ->where($this->selectedType . '_id', $this->selectedItem->id)
                ->delete();

            $this->loadDetailData();
            
            Notification::make()
                ->success()
                ->title('Товар отвязан')
                ->body('Связь успешно удалена')
                ->send();
                
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title('Ошибка')
                ->body('Не удалось отвязать товар: ' . $e->getMessage())
                ->send();
        }
    }

    protected function getActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Обновить')
                ->icon('heroicon-o-refresh')
                ->action('loadTreeData'),
        ];
    }
}
