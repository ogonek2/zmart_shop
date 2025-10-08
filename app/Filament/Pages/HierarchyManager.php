<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class HierarchyManager extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-view-grid';
    
    protected static ?string $navigationGroup = 'Управление товарами';
    
    protected static ?string $navigationLabel = 'Управление иерархией';
    
    protected static ?string $title = 'Управление иерархией';
    
    protected static ?string $slug = 'hierarchy-manager';
    
    protected static string $view = 'filament.pages.hierarchy-manager';
    
    protected static ?int $navigationSort = 2;
}
