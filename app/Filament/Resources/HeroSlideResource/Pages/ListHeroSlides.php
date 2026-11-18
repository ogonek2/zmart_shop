<?php

namespace App\Filament\Resources\HeroSlideResource\Pages;

use App\Filament\Resources\HeroSlideResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHeroSlides extends ListRecords
{
    protected static string $resource = HeroSlideResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Добавить слайд'),
        ];
    }

    protected function getTableEmptyStateHeading(): ?string
    {
        return 'Слайдов пока нет';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Создайте первый баннер для хедера главной страницы.';
    }
}
