<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Новий заказ з сайту</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        h2 { color: #1C214B; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f8f8f8;
        }
        .total {
            font-weight: bold;
            text-align: right;
        }
    </style>
</head>
<body>
    <h2>🎉 Новий заказ з сайту!</h2>

    <h3>👤 Інформація про клієнта</h3>
    <p><strong>Ім'я:</strong> {{ $order['name'] }}</p>
    <p><strong>Прізвище:</strong> {{ $order['lastname'] }}</p>
    @if(!empty($order['fathername']))
        <p><strong>По батькові:</strong> {{ $order['fathername'] }}</p>
    @endif
    <p><strong>Телефон:</strong> {{ $order['phone'] }}</p>
    @if(!empty($order['comment']))
        <p><strong>Коментар:</strong> {{ $order['comment'] }}</p>
    @endif

    <h3>🚚 Спосіб доставки</h3>
    <p><strong>Служба доставки:</strong> {{ $order['delivery_service'] }}</p>
    
    @if(!empty($order['city']))
        <p><strong>Місто:</strong> {{ $order['city'] }}</p>
    @endif
    
    @if(!empty($order['warehouse']))
        <p><strong>Відділення:</strong> {{ $order['warehouse'] }}</p>
    @endif
    
    @if(!empty($order['manual_address']))
        <p><strong>Адреса доставки:</strong> {{ $order['manual_address'] }}</p>
    @endif

    <h3>💳 Спосіб оплати</h3>
    <p>{{ $order['payment'] }}</p>

    <h3>🧾 Список товарів</h3>

    @php
        $cart = $order['cart'];
        
        // Если корзина - строка, пробуем декодировать JSON
        if (is_string($cart)) {
            $cart = json_decode($cart, true);
        }
        
        // Проверяем, что корзина - массив
        $cart = is_array($cart) ? $cart : [];
        
        // Логируем для отладки
        \Log::info('Email cart processing:', [
            'original_cart' => $order['cart'],
            'decoded_cart' => $cart,
            'cart_type' => gettype($cart),
            'cart_count' => is_array($cart) ? count($cart) : 'not array'
        ]);
    @endphp

    @if(!empty($cart))
        <p><strong>Кількість товарів:</strong> {{ count($cart) }}</p>
        <table>
            <thead>
                <tr>
                    <th>Назва товару</th>
                    <th>Артикул</th>
                    <th>Кількість</th>
                    <th>Ціна за одиницю</th>
                    <th>Сума</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($cart as $item)
                    @php
                        // Проверяем, что у товара есть все необходимые поля
                        $itemName = $item['name'] ?? 'Без названия';
                        $itemQuantity = $item['quantity'] ?? 1;
                        $itemPrice = $item['price'] ?? 0;
                        
                        // Убеждаемся, что значения - числа
                        $itemQuantity = is_numeric($itemQuantity) ? (int)$itemQuantity : 1;
                        $itemPrice = is_numeric($itemPrice) ? (float)$itemPrice : 0;
                        
                        $sum = $itemQuantity * $itemPrice;
                        $total += $sum;
                    @endphp
                    <tr>
                        <td>{{ $itemName }}</td>
                        <td>{{ $item['articule'] ?? 'Не указан' }}</td>
                        <td>{{ $itemQuantity }}</td>
                        <td>{{ number_format($itemPrice, 2, ',', ' ') }} грн</td>
                        <td>{{ number_format($sum, 2, ',', ' ') }} грн</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="total">
            <h4>Загальна сума: {{ number_format($total, 2, ',', ' ') }} грн</h4>
            @if(!empty($order['total_price']))
                <p><strong>Сума з форми:</strong> {{ number_format($order['total_price'], 2, ',', ' ') }} грн</p>
            @endif
            
            @if($total != $order['total_price'])
                <p style="color: #ff6b6b;"><strong>⚠️ Увага:</strong> Сума з корзини ({{ number_format($total, 2, ',', ' ') }} грн) відрізняється від суми з форми ({{ number_format($order['total_price'], 2, ',', ' ') }} грн)</p>
            @endif
        </div>
    @else
        <p><strong>Помилка:</strong> Корзина пуста або не може бути розкодована</p>
        <p><strong>Дані корзини:</strong> {{ $order['cart'] }}</p>
        <p><strong>Тип корзини:</strong> {{ gettype($order['cart']) }}</p>
        @if(is_string($order['cart']))
            <p><strong>Довжина корзини:</strong> {{ strlen($order['cart']) }}</p>
            <p><strong>JSON помилка:</strong> {{ json_last_error_msg() }}</p>
        @endif
    @endif

    <hr>
    <p><small>Це автоматичне повідомлення з сайту</small></p>
</body>
</html>
