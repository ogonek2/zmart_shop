<?php

namespace App\Filament\Resources\RelationsManagerResource\Pages;

use App\Filament\Resources\RelationsManagerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRelationsManager extends EditRecord
{
    protected static string $resource = RelationsManagerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}