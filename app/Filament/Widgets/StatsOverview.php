<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Orders;
use App\Models\Category;
use App\Models\Catalog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $totalOrders = Orders::count();
        $pendingOrders = 0; // В старой модели нет статусов
        
        // Рассчитываем общую выручку
        $totalRevenue = Orders::all()->sum(function($order) {
            return is_numeric($order->total_price) ? (float)$order->total_price : 0;
        });
        
        $totalProducts = Product::count();
        $activeProducts = Product::where('availability', 'in_stock')->count();
        
        $totalCategories = Category::where('is_active', true)->count();
        $totalCatalogs = Catalog::where('is_active', true)->count();
        
        // Заказы за последние 30 дней
        $ordersLastMonth = Orders::where('created_at', '>=', now()->subDays(30))->count();
        $revenueLastMonth = Orders::where('created_at', '>=', now()->subDays(30))
            ->get()
            ->sum(function($order) {
                return is_numeric($order->total_price) ? (float)$order->total_price : 0;
            });
        
        return [
            Card::make('Всего товаров', $totalProducts)
                ->description($activeProducts . ' в наличии')
                ->descriptionIcon('heroicon-s-check-circle')
                ->color('success')
                ->chart([7, 3, 5, 8, 12, 15, 20])
                ->extraAttributes(['class' => 'cursor-pointer']),
            
            Card::make('Всего заказов', $totalOrders)
                ->description($pendingOrders . ' ожидают обработки')
                ->descriptionIcon('heroicon-s-clock')
                ->color($pendingOrders > 0 ? 'warning' : 'success')
                ->chart([12, 15, 18, 14, 17, 20, 22])
                ->extraAttributes(['class' => 'cursor-pointer']),
            
            Card::make('Общая выручка', number_format($totalRevenue, 2) . ' ₴')
                ->description('За весь период')
                ->descriptionIcon('heroicon-s-currency-dollar')
                ->color('success')
                ->chart([100, 150, 200, 180, 220, 300, 350])
                ->extraAttributes(['class' => 'cursor-pointer']),
            
            Card::make('Заказов за месяц', $ordersLastMonth)
                ->description('Выручка: ' . number_format($revenueLastMonth, 2) . ' ₴')
                ->descriptionIcon('heroicon-s-trending-up')
                ->color('primary')
                ->chart([5, 7, 8, 10, 12, 15, 18])
                ->extraAttributes(['class' => 'cursor-pointer']),
            
            Card::make('Категорий', $totalCategories)
                ->description('Активных категорий')
                ->descriptionIcon('heroicon-s-tag')
                ->color('info')
                ->extraAttributes(['class' => 'cursor-pointer']),
            
            Card::make('Каталогов', $totalCatalogs)
                ->description('Групп и подгрупп')
                ->descriptionIcon('heroicon-s-view-grid')
                ->color('info')
                ->extraAttributes(['class' => 'cursor-pointer']),
        ];
    }
}