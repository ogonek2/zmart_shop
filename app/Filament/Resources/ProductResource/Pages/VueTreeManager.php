<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\Page;

class VueTreeManager extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.resources.product-resource.pages.vue-tree-manager';

    protected static ?string $title = 'Управление категориями (Vue)';

    protected static ?string $navigationLabel = 'Древо категорий (Vue)';

    protected function getActions(): array
    {
        return [];
    }
}
