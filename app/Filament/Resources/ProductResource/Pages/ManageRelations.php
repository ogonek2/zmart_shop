<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Product;
use App\Models\Category;
use App\Models\Catalog;
use App\Models\ProductCategoryCatalogRelation;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class ManageRelations extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.resources.product-resource.pages.manage-relations';

    public Product $record;

    public $selectedCategories = [];
    public $selectedCatalogs = [];
    public $sortOrder = 0;
    public $isPrimary = false;

    public function mount($record)
    {
        $this->record = $record;
        
        // Загружаем текущие связи
        $this->selectedCategories = $this->record->categories()->pluck('categories.id')->toArray();
        $this->selectedCatalogs = $this->record->catalogs()->pluck('catalogs.id')->toArray();
    }

    protected function getActions(): array
    {
        return [
            Action::make('save_relations')
                ->label('Сохранить связи')
                ->action('saveRelations')
                ->color('success'),
        ];
    }

    public function saveRelations()
    {
        // Удаляем старые связи
        ProductCategoryCatalogRelation::where('product_id', $this->record->id)->delete();
        
        $relations = [];
        
        // Создаем связи с категориями
        foreach ($this->selectedCategories as $categoryId) {
            $relations[] = [
                'product_id' => $this->record->id,
                'category_id' => $categoryId,
                'catalog_id' => null,
                'sort_order' => $this->sortOrder,
                'is_primary' => $this->isPrimary,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Создаем связи с каталогами
        foreach ($this->selectedCatalogs as $catalogId) {
            $relations[] = [
                'product_id' => $this->record->id,
                'catalog_id' => $catalogId,
                'category_id' => null,
                'sort_order' => $this->sortOrder,
                'is_primary' => $this->isPrimary,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        if (!empty($relations)) {
            ProductCategoryCatalogRelation::insert($relations);
        }
        
        // Обновляем связи many-to-many для совместимости
        $this->record->categories()->sync($this->selectedCategories);
        $this->record->catalogs()->sync($this->selectedCatalogs);
        
        Notification::make()
            ->success()
            ->title('Связи сохранены')
            ->body('Связи товара успешно обновлены')
            ->send();
    }

    public function getCategories()
    {
        return Category::all()->pluck('name', 'id');
    }

    public function getCatalogs()
    {
        return Catalog::all()->pluck('name', 'id');
    }
}
