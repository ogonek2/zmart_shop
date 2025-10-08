<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\Product;

use App\Mail\OrderSubmitted;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|min:2',
            'lastname' => 'required|min:2',
            'phone' => 'required',
            'delivery_service' => 'required',
            'payment' => 'required',
        ]);

        // Получаем корзину из запроса
        $cart = json_decode($request->cart, true) ?? [];
        
        // Логируем входящие данные для отладки
        \Log::info('Order submit request:', [
            'cart_raw' => $request->cart,
            'cart_decoded' => $cart,
            'cart_count' => is_array($cart) ? count($cart) : 'not array',
            'all_data' => $request->all()
        ]);
        
        // Проверяем, что корзина не пустая
        if (empty($cart)) {
            \Log::warning('Empty cart received');
            return response()->json([
                'error' => 'Корзина пуста'
            ], 400);
        }

        // Проверяем структуру корзины
        if (!is_array($cart)) {
            return response()->json([
                'error' => 'Некорректная структура корзины'
            ], 400);
        }

        // Обрабатываем каждый товар в корзине
        foreach ($cart as &$item) {
            // Проверяем обязательные поля товара
            if (!isset($item['name']) || !isset($item['price']) || !isset($item['quantity'])) {
                return response()->json([
                    'error' => 'В корзине есть товары с неполными данными'
                ], 400);
            }
            
            // Если артикул не указан, устанавливаем значение по умолчанию
            if (!isset($item['articule']) || empty($item['articule'])) {
                $item['articule'] = 'Не указан';
            }
            
            // Убеждаемся, что цена и количество - числа
            $item['price'] = is_numeric($item['price']) ? (float)$item['price'] : 0;
            $item['quantity'] = is_numeric($item['quantity']) ? (int)$item['quantity'] : 1;
        }

        $orderData = [
            'delivery_service' => $request->delivery_service,
            'city' => $request->city ?? '',
            'warehouse' => $request->warehouse ?? '',
            'manual_address' => $request->manual_address ?? '',
            'name' => $request->name,
            'lastname' => $request->lastname,
            'fathername' => $request->fathername ?? '',
            'phone' => $request->phone,
            'comment' => $request->comment ?? '',
            'cart' => json_encode($cart),
            'total_price' => is_numeric($request->total_price) ? (float)$request->total_price : 0,
            'payment' => $request->payment,
        ];

        // Проверяем, что все обязательные поля заполнены
        if (empty($orderData['name']) || empty($orderData['lastname']) || empty($orderData['phone'])) {
            return response()->json([
                'error' => 'Не все обязательные поля заполнены'
            ], 400);
        }

        // Проверяем адрес доставки в зависимости от способа доставки
        if ($orderData['delivery_service'] === 'novaposhta') {
            if (empty($orderData['city']) || empty($orderData['warehouse'])) {
                return response()->json([
                    'error' => 'Для доставки Новой Почтой необходимо указать город и отделение'
                ], 400);
            }
        } elseif ($orderData['delivery_service'] !== 'pickup') {
            if (empty($orderData['manual_address'])) {
                return response()->json([
                    'error' => 'Для выбранного способа доставки необходимо указать адрес'
                ], 400);
            }
        }

        // Проверяем, что корзина содержит валидные данные
        if (empty($cart) || !is_array($cart)) {
            return response()->json([
                'error' => 'Корзина содержит некорректные данные'
            ], 400);
        }

        // Проверяем, что в корзине есть товары
        $validItems = array_filter($cart, function($item) {
            return isset($item['name']) && isset($item['price']) && isset($item['quantity']);
        });

        if (empty($validItems)) {
            \Log::warning('No valid items in cart', ['cart' => $cart]);
            return response()->json([
                'error' => 'В корзине нет валидных товаров'
            ], 400);
        }

        // Проверяем, что все товары имеют корректные цены и количество
        foreach ($validItems as $item) {
            if (!is_numeric($item['price']) || $item['price'] < 0) {
                \Log::warning('Invalid price in cart item', ['item' => $item]);
                return response()->json([
                    'error' => 'Товар "' . $item['name'] . '" имеет некорректную цену'
                ], 400);
            }
            
            if (!is_numeric($item['quantity']) || $item['quantity'] <= 0) {
                \Log::warning('Invalid quantity in cart item', ['item' => $item]);
                return response()->json([
                    'error' => 'Товар "' . $item['name'] . '" имеет некорректное количество'
                ], 400);
            }
        }

        // Создаем заказ со СТАРОЙ структурой (Orders модель)
        $order = Orders::create($orderData);
        
        \Log::info('Order created successfully:', [
            'order_id' => $order->id,
            'cart_items_count' => count($cart),
            'total_price' => $orderData['total_price']
        ]);

        // Отправляем письмо с данными заказа
        try {
            Mail::to('zmartcomua@gmail.com')->send(new OrderSubmitted($orderData));
            \Log::info('Order email sent successfully');
        } catch (\Exception $e) {
            \Log::error('Failed to send order email: ' . $e->getMessage());
        }

        // --- Формируем JS для DataLayer ---
        $productIds = collect($cart)->pluck('id')->toArray();
    
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
    
        $items = [];
        $simpleItems = [];
    
        foreach ($cart as $item) {
            $product = $products[$item['id']] ?? null;
            if (!$product) continue;
    
            $items[] = [
                'item_name'   => $product->name,
                'item_id'     => $item['articule'] ?? 'Не указан',
                'item_brand'  => $product->brand ?? 'Без бренду',
                'item_category' => $product->categories()->first()->name ?? 'Без категорії',
                'price'       => $product->price,
                'discount'    => $product->discount, // можно добавить, если есть
                'quantity'    => $item['quantity'] ?? 1,
                'currency' => 'UAH'
            ];
    
            $simpleItems[] = [
                'id' => $item['articule'],
                'google_business_vertical' => 'retail'
            ];
        }
    
        $purchaseEvent = [
            'event' => 'purchase',
            'ecommerce' => [
                'transaction_id' => $order->id,
                'value' => $request->total_price,
                'items' => $items
            ],
            'items' => $simpleItems
        ];
    
        session()->flash('purchase_js', $purchaseEvent);

        return response()->json([
            'success' => true,
            'message' => 'Заказ успешно оформлен',
            'order_id' => $order->id
        ]);
    }
    // CartController.php
    public function saveAbandoned(Request $request)
    {
        $phone = $request->input('phone');
        $cartJson = $request->input('cart');

        // Можете декодировать корзину, если это JSON
        $cart = json_decode($cartJson, true);

        // Сохраняем в базу (можно в таблицу abandoned_carts)
        \DB::table('abandoned_carts')->updateOrInsert(
            ['phone' => $phone],
            [
                'cart_data' => json_encode($cart),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }
}
