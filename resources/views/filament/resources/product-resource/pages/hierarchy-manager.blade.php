<x-filament::page>
    <div class="space-y-6">
        <!-- Поиск -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <input 
                        type="text" 
                        wire:model.debounce.300ms="searchQuery"
                        wire:keyup="search"
                        placeholder="Поиск по названию..."
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>
                <button 
                    wire:click="search"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                >
                    Найти
                </button>
            </div>
        </div>

        <!-- Основной контент -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Категории -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Категории
                    </h3>
                </div>
                <div class="p-4">
                    <div id="categories-tree" class="space-y-2">
                        @foreach($hierarchyData['categories'] as $category)
                            <div class="category-item" data-id="{{ $category['id'] }}" data-type="category">
                                <div class="flex items-center justify-between p-3 bg-blue-50 border border-blue-200 rounded-lg cursor-move hover:bg-blue-100">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                        </svg>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $category['name'] }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $category['template'] }} • {{ $category['products_count'] }} товаров
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                                            Категория
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Товары в категории -->
                                @if(!empty($category['children']))
                                    <div class="ml-6 mt-2 space-y-1">
                                        @foreach($category['children'] as $product)
                                            <div class="product-item" data-id="{{ $product['id'] }}" data-type="product">
                                                <div class="flex items-center justify-between p-2 bg-gray-50 border border-gray-200 rounded cursor-move hover:bg-gray-100">
                                                    <div class="flex items-center space-x-3">
                                                        @if($product['image'])
                                                            <img src="{{ $product['image'] }}" alt="" class="w-8 h-8 object-cover rounded">
                                                        @else
                                                            <div class="w-8 h-8 bg-gray-200 rounded flex items-center justify-center">
                                                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="font-medium text-sm text-gray-900">{{ $product['name'] }}</div>
                                                            <div class="text-xs text-gray-500">
                                                                {{ $product['sku'] }} • {{ number_format($product['price'], 0) }} ₴
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
                                                        Товар
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Каталоги -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        Каталоги и Группы
                    </h3>
                </div>
                <div class="p-4">
                    <div id="catalogs-tree" class="space-y-2">
                        @foreach($hierarchyData['catalogs'] as $catalog)
                            @include('filament.resources.product-resource.pages.catalog-tree-item', ['catalog' => $catalog, 'level' => 0])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Инициализация Sortable для категорий
            const categoriesContainer = document.getElementById('categories-tree');
            if (categoriesContainer) {
                new Sortable(categoriesContainer, {
                    group: 'hierarchy',
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function(evt) {
                        const draggedId = evt.item.dataset.id;
                        const targetId = evt.to.dataset.id || evt.to.closest('[data-id]')?.dataset.id;
                        
                        if (targetId && draggedId !== targetId) {
                            const position = evt.newIndex === 0 ? 'inside' : 'after';
                            @this.call('moveItem', draggedId, targetId, position);
                        }
                    }
                });
            }

            // Инициализация Sortable для каталогов
            const catalogsContainer = document.getElementById('catalogs-tree');
            if (catalogsContainer) {
                new Sortable(catalogsContainer, {
                    group: 'hierarchy',
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function(evt) {
                        const draggedId = evt.item.dataset.id;
                        const targetId = evt.to.dataset.id || evt.to.closest('[data-id]')?.dataset.id;
                        
                        if (targetId && draggedId !== targetId) {
                            const position = evt.newIndex === 0 ? 'inside' : 'after';
                            @this.call('moveItem', draggedId, targetId, position);
                        }
                    }
                });
            }

            // Добавляем обработчики для вложенных элементов
            document.querySelectorAll('.catalog-children').forEach(container => {
                new Sortable(container, {
                    group: 'hierarchy',
                    animation: 150,
                    ghostClass: 'opacity-50',
                    onEnd: function(evt) {
                        const draggedId = evt.item.dataset.id;
                        const targetId = evt.to.dataset.id || evt.to.closest('[data-id]')?.dataset.id;
                        
                        if (targetId && draggedId !== targetId) {
                            const position = evt.newIndex === 0 ? 'inside' : 'after';
                            @this.call('moveItem', draggedId, targetId, position);
                        }
                    }
                });
            });
        });
    </script>
    @endpush
</x-filament::page>