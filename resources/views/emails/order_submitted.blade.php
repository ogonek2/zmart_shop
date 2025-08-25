<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Новий замовлення</title>
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
    <h2>🛒 Нове замовлення з сайту</h2>

    <p><strong>Ім’я:</strong> {{ $order['lastname'] }} {{ $order['name'] }} {{ $order['fathername'] ?? '' }}</p>
    <p><strong>Телефон:</strong> {{ $order['phone'] }}</p>
    <p><strong>Служба доставки:</strong> {{ $order['delivery_service'] }}</p>
    <p><strong>Місто:</strong> {{ $order['city'] ?? "Не указано" }}</p>
    <p><strong>Склад / Адреса:</strong>
        {{ $order['warehouse'] ?: $order['manual_address'] }}
    </p>
    <p><strong>Коментар:</strong> {{ $order['comment'] ?? '—' }}</p>

    <h3>🧾 Список товарів</h3>

    @if (!empty($order['cart']) && is_array($order['cart']))
        <table>
            <thead>
                <tr>
                    <th>Артикуль</th>
                    <th>Назва товару</th>
                    <th>Кількість</th>
                    <th>Ціна за одиницю</th>
                    <th>Сума</th>
                </tr>
            </thead>
            <tbody>
                @php $total = 0; @endphp
                @foreach ($order['cart'] as $item)
                    @php
                        $sum = $item['quantity'] * $item['price'];
                        $total += $sum;
                    @endphp
                    <tr>
                        <td>{{ $item['articule'] }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['price'], 2, ',', ' ') }} грн</td>
                        <td>{{ number_format($sum, 2, ',', ' ') }} грн</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="total">Всього до оплати: <strong>{{ number_format($total, 2, ',', ' ') }} грн</strong></p>
    @else
        <p><em>Корзина пуста.</em></p>
    @endif
</body>
</html>
