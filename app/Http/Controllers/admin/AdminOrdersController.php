<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminOrdersController extends Controller
{
    public function index()
    {
        $orders = Orders::orderBy('created_at', 'desc')
            ->paginate(20);

        // Отладочная информация
        if ($orders->count() > 0) {
            $firstOrder = $orders->first();
            \Log::info('First order data:', [
                'id' => $firstOrder->id,
                'raw_name' => $firstOrder->getRawOriginal('name'),
                'decrypted_name' => $firstOrder->name,
                'raw_lastname' => $firstOrder->getRawOriginal('lastname'),
                'decrypted_lastname' => $firstOrder->lastname,
            ]);
        }

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Orders::findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Orders::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Статус заказа обновлен');
    }

    public function exportPdf($id)
    {
        $order = Orders::findOrFail($id);
        
        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
        
        // Настройки для корректного отображения кириллицы
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'DejaVu Sans'
        ]);
        
        return $pdf->download("order-{$order->id}.pdf");
    }

    public function destroy($id)
    {
        $order = Orders::findOrFail($id);
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Статус заказа обновлен');
    }

    public function statistics()
    {
        // Получаем все заказы для подсчета
        $allOrders = Orders::all();
        
        $stats = [
            'total_orders' => $allOrders->count(),
            'pending_orders' => $allOrders->where('status', 'pending')->count(),
            'confirmed_orders' => $allOrders->where('status', 'confirmed')->count(),
            'processing_orders' => $allOrders->where('status', 'processing')->count(),
            'shipped_orders' => $allOrders->where('status', 'shipped')->count(),
            'delivered_orders' => $allOrders->where('status', 'delivered')->count(),
            'cancelled_orders' => $allOrders->where('status', 'cancelled')->count(),
            'total_revenue' => $allOrders->sum('numeric_total_price'),
            'monthly_revenue' => $allOrders->where('created_at', '>=', now()->startOfMonth())
                ->sum('numeric_total_price'),
            'payment_stats' => [
                'cash' => $allOrders->where('payment', 'cash')->count(),
                'bank_transfer' => $allOrders->where('payment', 'bank_transfer')->count(),
                'card_payment' => $allOrders->where('payment', 'card_payment')->count(),
                'pickup_payment' => $allOrders->where('payment', 'pickup_payment')->count(),
            ],
        ];

        return view('admin.orders.statistics', compact('stats'));
    }

    // Метод для создания тестового заказа (временно, для проверки)
    public function createTestOrder()
    {
        try {
            $testOrder = Orders::create([
                'name' => 'Тест',
                'lastname' => 'Клиент',
                'phone' => '+380991234567',
                'delivery_service' => 'Новая Почта',
                'city' => 'Киев',
                'warehouse' => 'Отделение №1',
                'total_price' => '1500.00',
                'comment' => 'Тестовый заказ'
            ]);

            return redirect()->back()->with('success', 'Тестовый заказ создан: ID ' . $testOrder->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Ошибка создания заказа: ' . $e->getMessage());
        }
    }
}
