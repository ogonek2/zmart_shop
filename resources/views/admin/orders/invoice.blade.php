<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Заказ #{{ $order->id }}</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .company-info {
            margin-bottom: 30px;
        }
        .order-info {
            margin-bottom: 30px;
        }
        .customer-info {
            margin-bottom: 30px;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .products-table th,
        .products-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .products-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .totals {
            text-align: right;
            margin-top: 20px;
        }
        .total-row {
            font-weight: bold;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>ZMART - Интернет-магазин бытовой техники</h1>
        <p>Счет-фактура</p>
    </div>

    <div class="company-info">
        <h3>Информация о компании</h3>
        <p><strong>Название:</strong> ZMART</p>
        <p><strong>Email:</strong> zmartcomua@gmail.com</p>
        <p><strong>Дата:</strong> {{ $order->formatted_created_at }}</p>
    </div>

    <div class="order-info">
        <h3>Информация о заказе</h3>
        <p><strong>Номер заказа:</strong> #{{ $order->id }}</p>
        <p><strong>Дата заказа:</strong> {{ $order->formatted_created_at }}</p>
        <p><strong>Способ доставки:</strong> {{ $order->delivery_service }}</p>
        <p><strong>Способ оплаты:</strong> 
            @switch($order->payment)
                @case('cash')
                    Накладений платіж
                    @break
                @case('bank_transfer')
                    Банківський переказ
                    @break
                @case('card_payment')
                    Картою при отриманні
                    @break
                @case('pickup_payment')
                    При самовивозі
                    @break
                @default
                    {{ $order->payment }}
            @endswitch
        </p>
        <p><strong>Город:</strong> {{ $order->city }}</p>
        <p><strong>Отделение:</strong> {{ $order->warehouse }}</p>
    </div>

    <div class="customer-info">
        <h3>Информация о клиенте</h3>
        <p><strong>Имя:</strong> {{ $order->name }} {{ $order->lastname }}</p>
        <p><strong>Отчество:</strong> {{ $order->fathername ?? 'Не указано' }}</p>
        <p><strong>Телефон:</strong> {{ $order->phone }}</p>
        @if($order->manual_address)
        <p><strong>Адрес:</strong> {{ $order->manual_address }}</p>
        @endif
        @if($order->comment)
        <p><strong>Комментарий:</strong> {{ $order->comment }}</p>
        @endif
    </div>

    <div class="products">
        <h3>Товары в заказе</h3>
        <table class="products-table">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Артикул</th>
                    <th>Цена</th>
                    <th>Количество</th>
                    <th>Сумма</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->cart_items as $item)
                <tr>
                    <td>{{ $item['name'] ?? 'Название не указано' }}</td>
                    <td>{{ $item['sku'] ?? 'Не указан' }}</td>
                    <td>{{ number_format($item['price'] ?? 0, 2, ',', ' ') }} ₴</td>
                    <td>{{ $item['quantity'] ?? 1 }}</td>
                    <td>{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2, ',', ' ') }} ₴</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Товары не найдены</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="totals">
        <p><strong>Подытог:</strong> {{ $order->formatted_total_price }}</p>
        <p><strong>Доставка:</strong> 0,00 ₴</p>
        <p class="total-row"><strong>Итого:</strong> {{ $order->formatted_total_price }}</p>
    </div>

    <div style="margin-top: 50px; text-align: center; font-size: 10px; color: #666;">
        <p>Спасибо за ваш заказ!</p>
        <p>ZMART - качественная бытовая техника для вашего дома</p>
    </div>
</body>
</html>
