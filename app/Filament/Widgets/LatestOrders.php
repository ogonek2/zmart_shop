<?php

namespace App\Filament\Widgets;

use App\Models\Orders;
use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return Orders::query()
            ->latest('created_at');
    }
    
    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [5, 10, 25];
    }
    
    protected function getDefaultTableRecordsPerPageSelectOption(): int
    {
        return 10;
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->label('№')
                ->sortable()
                ->weight('bold'),
            
            Tables\Columns\TextColumn::make('full_name')
                ->label('Клиент')
                ->getStateUsing(function ($record) {
                    return trim($record->name . ' ' . $record->lastname);
                })
                ->searchable(['name', 'lastname'])
                ->sortable(),
            
            Tables\Columns\TextColumn::make('phone')
                ->label('Телефон')
                ->searchable(),
            
            Tables\Columns\BadgeColumn::make('delivery_service')
                ->label('Доставка')
                ->colors([
                    'primary' => 'novaposhta',
                    'success' => 'pickup',
                    'secondary' => 'ukrposhta',
                ])
                ->enum([
                    'novaposhta' => 'Новая Почта',
                    'ukrposhta' => 'Укрпошта',
                    'meest' => 'Meest',
                    'pickup' => 'Самовывоз',
                ]),
            
            Tables\Columns\TextColumn::make('total_price')
                ->label('Сумма')
                ->getStateUsing(function ($record) {
                    return is_numeric($record->total_price) ? number_format($record->total_price, 2) . ' ₴' : $record->total_price;
                })
                ->sortable()
                ->weight('bold'),
            
            Tables\Columns\TextColumn::make('created_at')
                ->label('Дата')
                ->dateTime('d.m.Y H:i')
                ->sortable(),
        ];
    }
    
    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }
    
    protected function getTableHeading(): string
    {
        return 'Последние заказы';
    }
}