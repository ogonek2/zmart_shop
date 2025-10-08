<div class="catalog-item" data-id="{{ $catalog['id'] }}" data-type="catalog">
    <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg cursor-move hover:bg-green-100" style="margin-left: {{ $level * 20 }}px;">
        <div class="flex items-center space-x-3">
            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
            </svg>
            <div>
                <div class="font-medium text-gray-900">{{ $catalog['name'] }}</div>
                <div class="text-sm text-gray-500">
                    {{ $catalog['template'] }} • {{ $catalog['type_label'] }} • {{ $catalog['products_count'] }} товаров
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                {{ $catalog['type_label'] }}
            </span>
        </div>
    </div>
    
    <!-- Вложенные элементы -->
    @if(!empty($catalog['children']))
        <div class="catalog-children mt-2 space-y-1" data-id="{{ $catalog['id'] }}">
            @foreach($catalog['children'] as $child)
                @if($child['type'] === 'product')
                    <div class="product-item" data-id="{{ $child['id'] }}" data-type="product">
                        <div class="flex items-center justify-between p-2 bg-gray-50 border border-gray-200 rounded cursor-move hover:bg-gray-100" style="margin-left: {{ ($level + 1) * 20 }}px;">
                            <div class="flex items-center space-x-3">
                                @if($child['image'])
                                    <img src="{{ $child['image'] }}" alt="" class="w-8 h-8 object-cover rounded">
                                @else
                                    <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center">
                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium text-sm text-gray-900">{{ $child['name'] }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $child['sku'] }} • {{ number_format($child['price'], 0) }} ₴
                                    </div>
                                </div>
                            </div>
                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                Товар
                            </span>
                        </div>
                    </div>
                @elseif($child['type'] === 'catalog')
                    @include('filament.resources.product-resource.pages.catalog-tree-item', ['catalog' => $child, 'level' => $level + 1])
                @endif
            @endforeach
        </div>
    @endif
</div>
