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
        ]);
    
        $cart = json_decode($request->cart, true) ?? [];
    
        $orderData = [
            'delivery_service' => $request->delivery_service,
            'city' => $request->city,
            'warehouse' => $request->warehouse,
            'manual_address' => $request->manual_address,
            'name' => $request->name,
            'lastname' => $request->lastname,
            'fathername' => $request->fathername,
            'phone' => $request->phone,
            'comment' => $request->comment,
            'cart' => $cart,
            'total_price' => $request->total_price,
        ];
    
        $order = Orders::create(array_merge($orderData, [
            'cart' => $request->cart // сохраняем оригинальный JSON
        ]));
    
        // Отправка письма
        Mail::to('zmartcomua@gmail.com')->send(new OrderSubmitted($orderData));
    
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
                'item_id'     => $product->articule,
                'item_brand'  => $product->brand ?? 'Без бренду',
                'item_category' => $product->categories()->first()->name ?? 'Без категорії',
                'price'       => $product->price,
                'discount'    => $product->discount, // можно добавить, если есть
                'quantity'    => $item['quantity'] ?? 1
            ];
    
            $simpleItems[] = [
                'id' => $product->articule,
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

        // Перенаправляем пользователя на страницу "Спасибо"
        return view('thanks_you_page');
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
