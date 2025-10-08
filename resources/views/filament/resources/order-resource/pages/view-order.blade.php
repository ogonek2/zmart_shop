<x-filament::page>
    <div class="space-y-6">
        <!-- Информация о клиенте -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Информация о клиенте</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Имя</label>
                    <p class="text-sm text-gray-900">{{ $record->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Фамилия</label>
                    <p class="text-sm text-gray-900">{{ $record->lastname }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Отчество</label>
                    <p class="text-sm text-gray-900">{{ $record->fathername ?? 'Не указано' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Телефон</label>
                    <p class="text-sm text-gray-900">{{ $record->phone }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Сумма заказа</label>
                    <p class="text-lg font-semibold text-green-600">{{ $record->formatted_total_price }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Дата заказа</label>
                    <p class="text-sm text-gray-900">{{ $record->formatted_created_at }}</p>
                </div>
            </div>
        </div>

        <!-- Информация о доставке -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Информация о доставке</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Способ доставки</label>
                    <p class="text-sm text-gray-900">
                        @switch($record->delivery_service)
                            @case('novaposhta')
                                Новая Почта
                                @break
                            @case('ukrposhta')
                                Укрпошта
                                @break
                            @case('meest')
                                Meest Express
                                @break
                            @case('pickup')
                                Самовывоз
                                @break
                            @default
                                {{ $record->delivery_service }}
                        @endswitch
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Город</label>
                    <p class="text-sm text-gray-900">{{ $record->city ?? 'Не указано' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Отделение</label>
                    <p class="text-sm text-gray-900">{{ $record->warehouse ?? 'Не указано' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Адрес доставки</label>
                    <p class="text-sm text-gray-900">{{ $record->manual_address ?? 'Не указано' }}</p>
                </div>
            </div>
        </div>

        <!-- Информация об оплате -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Информация об оплате</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-500">Способ оплаты</label>
                    <p class="text-sm text-gray-900">
                        @switch($record->payment)
                            @case('card')
                                Банковская карта
                                @break
                            @case('cash')
                                Наличные
                                @break
                            @case('nalogenniy')
                                Наложенный платеж
                                @break
                            @case('bank_transfer')
                                Банковский перевод
                                @break
                            @default
                                {{ $record->payment }}
                        @endswitch
                    </p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Покупка отслежена</label>
                    <p class="text-sm text-gray-900">
                        @if($record->purchase_tracked)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Да
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Нет
                            </span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Товары в заказе -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Товары в заказе</h3>
            @php
                $cartItems = $record->cart_items;
            @endphp
            
            @if(count($cartItems) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Товар</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Количество</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цена</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Сумма</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cartItems as $item)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $item['name'] ?? 'Неизвестный товар' }}
                                        </div>
                                        @if(isset($item['articule']))
                                            <div class="text-sm text-gray-500">Артикул: {{ $item['articule'] }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item['quantity'] ?? 1 }} шт.
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($item['price'] ?? 0, 2) }} ₴
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }} ₴
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                    Итого:
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                    {{ $record->formatted_total_price }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Товары не найдены</p>
            @endif
        </div>

        <!-- Комментарий -->
        @if($record->comment)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Комментарий</h3>
                <p class="text-sm text-gray-900">{{ $record->comment }}</p>
            </div>
        @endif
    </div>
</x-filament::page>
