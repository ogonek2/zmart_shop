<x-filament::page>
    <div class="flex h-screen bg-gray-50">
        <!-- Левая панель - Древовидная структура -->
        <div class="w-1/4 bg-white border-r border-gray-200 overflow-y-auto">
            <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Категории</h2>
                    <div class="flex space-x-2">
                        <button 
                            wire:click="fixCategorySorting"
                            class="px-2 py-1 text-xs bg-blue text-white rounded hover:bg-yellow-700"
                            title="Исправить сортировку всех категорий">
                            🔧
                        </button>
                        <button 
                            wire:click="createCategory('Новая категория')"
                            class="px-3 py-1 text-sm bg-blue-600 text-black rounded hover:bg-blue-700">
                            + Добавить
                        </button>
                    </div>
                </div>
                
                <!-- Древовидная структура категорий -->
                <div id="categories-tree" class="space-y-1">
                    @foreach($treeData['categories'] as $category)
                        @include('filament.resources.product-resource.pages.category-tree-node', ['category' => $category, 'level' => 0])
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Правая панель - Детальный просмотр -->
        <div class="flex-1 bg-white overflow-y-auto">
            @if($selectedItem)
                <div class="p-6">
                    <!-- Заголовок выбранного элемента -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $selectedItem->name }}</h1>
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                    Категория
                                </span>
                            </div>
                            <div class="flex space-x-2">
                                <button 
                                    wire:click="createCategory('Новая подкатегория', {{ $selectedItem->id }})"
                                    class="px-3 py-1 text-sm bg-green-600 text-black rounded hover:bg-green-700">
                                    + Подкатегория
                                </button>
                                <button 
                                    type="button"
                                    wire:click="openEditModal({{ $selectedItem->id }})"
                                    class="px-3 py-1 text-sm bg-yellow-600 text-black rounded hover:bg-yellow-700">
                                    ✏️ Редактировать
                                </button>
                                <button 
                                    type="button"
                                    onclick="
                                        if (confirm('Вы уверены, что хотите удалить категорию \'{{ addslashes($selectedItem->name) }}\'? Все подкатегории будут перемещены на уровень выше.')) {
                                            window.livewire.find('{{ $_instance->id }}').deleteCategory({{ $selectedItem->id }});
                                        }
                                    "
                                    class="px-3 py-1 text-sm bg-red-600 text-black rounded hover:bg-red-700">
                                    🗑️ Удалить
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500">
                            {{ count($detailData['products']) }} товаров • 
                            {{ count($detailData['subcategories']) }} подкатегорий
                        </p>
                    </div>

                    <!-- Подкатегории -->
                    @if(count($detailData['subcategories']) > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Подкатегории</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($detailData['subcategories'] as $subcategory)
                                    @php
                                        $subcat = is_array($subcategory) ? (object)$subcategory : $subcategory;
                                    @endphp
                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer"
                                         wire:click="selectItem('category_{{ $subcat->id }}', 'category')">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                            </svg>
                                            <span class="font-medium text-gray-900">{{ $subcat->name ?? 'Без названия' }}</span>
                                        </div>
                                        <p class="text-sm text-gray-500">{{ $subcat->products_count ?? 0 }} товаров</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Товары - Таблица -->
                    <div class="product-drop-zone" 
                         data-category-id="{{ $selectedItem->id ?? 0 }}"
                         data-category-name="{{ $selectedItem->name ?? 'Текущая категория' }}">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Товары</h3>
                            <span class="text-sm text-gray-500">{{ count($detailData['products']) }} товаров</span>
                        </div>
                        
                        @if(count($detailData['products']) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Фото</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Артикул</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Цена</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Наличие</th>
                                            <th class="px-3 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($detailData['products'] as $product)
                                            @php
                                                $prod = is_array($product) ? (object)$product : $product;
                                            @endphp
                                            <tr class="hover:bg-gray-50 product-row" 
                                                data-product-id="{{ $prod->id ?? 0 }}"
                                                draggable="true">
                                                <td class="px-3 py-4 whitespace-nowrap">
                                                    <div class="flex items-center space-x-2">
                                                        <!-- Drag Handle для товара -->
                                                        <div class="product-drag-handle p-1 cursor-grab" title="Перетащить товар в категорию">
                                                            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                                            </svg>
                                                        </div>
                                                        
                                                        @if(isset($prod->image_path) && $prod->image_path)
                                                            <img 
                                                                data-src="{{ $prod->image_path }}" 
                                                                alt="{{ $prod->name ?? '' }}" 
                                                                class="lazy-image w-12 h-12 object-cover rounded"
                                                                style="background: #f3f4f6;">
                                                        @else
                                                            <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-3 py-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $prod->name ?? 'Без названия' }}</div>
                                                    @if(isset($prod->categories) && is_object($prod->categories) && $prod->categories->count() > 0)
                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                            @foreach($prod->categories->take(2) as $category)
                                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                    {{ is_object($category) ? $category->name : $category['name'] ?? 'N/A' }}
                                                                </span>
                                                            @endforeach
                                                            @if($prod->categories->count() > 2)
                                                                <span class="text-xs text-gray-500">+{{ $prod->categories->count() - 2 }}</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $prod->articule ?? 'N/A' }}
                                                </td>
                                                <td class="px-3 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                    {{ number_format($prod->price ?? 0, 0) }} ₴
                                                </td>
                                                <td class="px-3 py-4 whitespace-nowrap">
                                                    @if(isset($prod->availability) && $prod->availability)
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            В наличии
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            Нет
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('filament.resources.products.edit', $prod->id ?? 0) }}" 
                                                       class="text-blue-600 hover:text-blue-900 mr-3"
                                                       title="Редактировать">
                                                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                        </svg>
                                                    </a>
                                                    <button 
                                                        wire:click="removeProductFromItem({{ $prod->id ?? 0 }})"
                                                        class="text-red-600 hover:text-red-900"
                                                        title="Отвязать">
                                                        <svg class="w-5 h-5 inline" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                        </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">Нет товаров</h3>
                                <p class="mt-1 text-sm text-gray-500">В этой категории пока нет товаров.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Выберите элемент</h3>
                        <p class="mt-1 text-sm text-gray-500">Кликните на категорию или каталог для просмотра содержимого.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        function initSortable() {
            console.log('Инициализация SortableJS...');
            
            // Находим все контейнеры категорий
            const containers = document.querySelectorAll('#categories-tree, .category-children');
            console.log('Найдено контейнеров:', containers.length);
            
            containers.forEach((container, index) => {
                console.log(`Обработка контейнера ${index}:`, container);
                
                if (container._sortable) {
                    console.log('Уничтожаем существующий Sortable');
                    container._sortable.destroy();
                }
                
                // Проверяем, что в контейнере есть элементы для сортировки
                const sortableItems = container.querySelectorAll('.category-tree-node');
                if (sortableItems.length === 0) {
                    console.log('Пропускаем пустой контейнер');
                    return;
                }
                
                console.log(`Создаем Sortable для контейнера с ${sortableItems.length} элементами`);
                
                const sortable = new Sortable(container, {
                    group: {
                        name: 'categories',
                        pull: true,
                        put: true
                    },
                    animation: 200,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    handle: '.drag-handle',
                    forceFallback: false,
                    removeCloneOnHide: true,
                    
                    onStart: function(evt) {
                        console.log('Начало перетаскивания:', evt.item.dataset.categoryId);
                        // Сохраняем исходную позицию
                        evt.item.dataset.originalIndex = evt.oldIndex;
                    },
                    
                    onMove: function(evt) {
                        // Разрешаем перемещение
                        return true;
                    },
                    
                    onEnd: function(evt) {
                        console.log('Конец перетаскивания');
                        console.log('evt.item:', evt.item);
                        console.log('evt.to:', evt.to);
                        console.log('evt.newIndex:', evt.newIndex);
                        console.log('evt.oldIndex:', evt.oldIndex);
                        
                        // Проверяем, что элемент действительно переместился
                        if (evt.oldIndex === evt.newIndex) {
                            console.log('Позиция не изменилась, пропускаем');
                            return;
                        }
                        
                        const draggedElement = evt.item;
                        const draggedId = draggedElement.dataset.categoryId;
                        
                        if (!draggedId) {
                            console.error('Не найден ID категории');
                            return;
                        }
                        
                        // Определяем новую родительскую категорию
                        const newParentContainer = evt.to;
                        let newParentId = null;
                        
                        if (newParentContainer.id !== 'categories-tree') {
                            const parentNode = newParentContainer.closest('[data-category-id]');
                            if (parentNode) {
                                newParentId = parentNode.dataset.categoryId;
                            }
                        }
                        
                        // Проверяем на циклические ссылки
                        if (draggedId === newParentId) {
                            console.warn('Попытка переместить категорию в саму себя');
                            evt.item.parentNode.insertBefore(evt.item, evt.item.parentNode.children[evt.oldIndex]);
                            return;
                        }
                        
                        // Получаем новую позицию (индекс среди соседей)
                        const newIndex = evt.newIndex;
                        
                        console.log('Перемещение:', {
                            draggedId: draggedId,
                            newParentId: newParentId,
                            newIndex: newIndex,
                            oldIndex: evt.oldIndex
                        });
                        
                        // Вызываем метод Livewire
                        if (window.Livewire) {
                            try {
                                Livewire.find('{{ $_instance->id }}').call('updateCategoryOrder', draggedId, newParentId, newIndex);
                            } catch (error) {
                                console.error('Ошибка при вызове Livewire:', error);
                                // Возвращаем элемент на место при ошибке
                                evt.item.parentNode.insertBefore(evt.item, evt.item.parentNode.children[evt.oldIndex]);
                            }
                        } else {
                            console.error('Livewire не найден');
                            // Возвращаем элемент на место
                            evt.item.parentNode.insertBefore(evt.item, evt.item.parentNode.children[evt.oldIndex]);
                        }
                    }
                });
                
                container._sortable = sortable;
            });
        }
        
        document.addEventListener('DOMContentLoaded', initSortable);
        
        // Переинициализация после обновления Livewire
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', (message, component) => {
                setTimeout(initSortable, 100);
            });
        });
    </script>
    
    <style>
        .sortable-ghost {
            opacity: 0.4;
            background: #e3f2fd;
        }
        
        .sortable-chosen {
            background: #bbdefb;
        }
        
        .sortable-drag {
            opacity: 1;
        }
        
        .drag-handle {
            cursor: grab;
        }
        
        .drag-handle:active {
            cursor: grabbing;
        }
        
        /* Стили для drag-and-drop товаров */
        .product-row {
            transition: opacity 0.2s ease;
        }
        
        .product-row.dragging {
            opacity: 0.5;
        }
        
        .category-drop-zone, .product-drop-zone {
            transition: all 0.2s ease;
        }
        
        .category-drop-zone.drag-over, .product-drop-zone.drag-over {
            background-color: #dcfce7 !important;
            border: 2px solid #22c55e !important;
            transform: scale(1.02);
        }
        
        .product-drop-zone {
            min-height: 200px;
            border: 2px dashed transparent;
            border-radius: 8px;
            padding: 16px;
        }
        
        .product-drop-zone.drag-over {
            border-color: #22c55e !important;
            background-color: #f0fdf4 !important;
        }
        
        .product-drag-handle {
            cursor: grab;
            opacity: 0.6;
            transition: opacity 0.2s ease;
        }
        
        .product-drag-handle:hover {
            opacity: 1;
        }
        
        .product-drag-handle:active {
            cursor: grabbing;
        }
        
        /* Lazy loading placeholder */
        .lazy-image:not([src]) {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
    
    <script>
        // Lazy loading для изображений
        function initLazyLoading() {
            const lazyImages = document.querySelectorAll('.lazy-image');
            
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const img = entry.target;
                            if (img.dataset.src && !img.src) {
                                img.src = img.dataset.src;
                                img.classList.add('loaded');
                                observer.unobserve(img);
                            }
                        }
                    });
                }, {
                    rootMargin: '50px' // Загружаем за 50px до появления в viewport
                });
                
                lazyImages.forEach(img => imageObserver.observe(img));
            } else {
                // Fallback для старых браузеров
                lazyImages.forEach(img => {
                    if (img.dataset.src && !img.src) {
                        img.src = img.dataset.src;
                    }
                });
            }
        }
        
        // Инициализация при загрузке и после обновления Livewire
        document.addEventListener('DOMContentLoaded', function() {
            initLazyLoading();
            initProductDragDrop();
        });
        
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('message.processed', (message, component) => {
                setTimeout(function() {
                    initLazyLoading();
                    initProductDragDrop();
                }, 100);
            });
        }
        
        // Инициализация drag-and-drop для товаров
        function initProductDragDrop() {
            console.log('Инициализация drag-and-drop для товаров...');
            
            // Находим все товары
            const productRows = document.querySelectorAll('.product-row');
            console.log('Найдено товаров:', productRows.length);
            
            productRows.forEach(row => {
                // Убираем старые обработчики
                row.removeEventListener('dragstart', handleProductDragStart);
                row.removeEventListener('dragend', handleProductDragEnd);
                
                // Добавляем новые обработчики
                row.addEventListener('dragstart', handleProductDragStart);
                row.addEventListener('dragend', handleProductDragEnd);
            });
            
            // Находим все зоны для сброса (категории + зона товаров)
            const dropZones = document.querySelectorAll('.category-drop-zone, .product-drop-zone');
            console.log('Найдено зон для сброса:', dropZones.length);
            
            dropZones.forEach(zone => {
                // Убираем старые обработчики
                zone.removeEventListener('dragover', handleCategoryDragOver);
                zone.removeEventListener('dragenter', handleCategoryDragEnter);
                zone.removeEventListener('dragleave', handleCategoryDragLeave);
                zone.removeEventListener('drop', handleCategoryDrop);
                
                // Добавляем новые обработчики
                zone.addEventListener('dragover', handleCategoryDragOver);
                zone.addEventListener('dragenter', handleCategoryDragEnter);
                zone.addEventListener('dragleave', handleCategoryDragLeave);
                zone.addEventListener('drop', handleCategoryDrop);
            });
        }
        
        let draggedProduct = null;
        
        function handleProductDragStart(e) {
            draggedProduct = e.target.closest('.product-row');
            const productId = draggedProduct.dataset.productId;
            const productName = draggedProduct.querySelector('.text-sm.font-medium').textContent;
            
            console.log('Начало перетаскивания товара:', productId, productName);
            
            e.dataTransfer.setData('text/plain', productId);
            e.dataTransfer.effectAllowed = 'move';
            
            // Добавляем класс для визуального эффекта
            draggedProduct.classList.add('opacity-50');
        }
        
        function handleProductDragEnd(e) {
            console.log('Конец перетаскивания товара');
            
            // Убираем класс
            if (draggedProduct) {
                draggedProduct.classList.remove('opacity-50');
            }
            
            // Убираем подсветку со всех зон
            document.querySelectorAll('.category-drop-zone, .product-drop-zone').forEach(zone => {
                zone.classList.remove('drag-over');
            });
            
            draggedProduct = null;
        }
        
        function handleCategoryDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'move';
        }
        
        function handleCategoryDragEnter(e) {
            e.preventDefault();
            const zone = e.target.closest('.category-drop-zone, .product-drop-zone');
            if (zone) {
                zone.classList.add('drag-over');
            }
        }
        
        function handleCategoryDragLeave(e) {
            const zone = e.target.closest('.category-drop-zone, .product-drop-zone');
            if (zone && !zone.contains(e.relatedTarget)) {
                zone.classList.remove('drag-over');
            }
        }
        
        function handleCategoryDrop(e) {
            e.preventDefault();
            const zone = e.target.closest('.category-drop-zone, .product-drop-zone');
            if (!zone) return;
            
            const productId = e.dataTransfer.getData('text/plain');
            const categoryId = zone.dataset.categoryId;
            const categoryName = zone.dataset.categoryName;
            
            console.log('Сброс товара:', {
                productId: productId,
                categoryId: categoryId,
                categoryName: categoryName
            });
            
            // Убираем подсветку
            zone.classList.remove('drag-over');
            
            // Вызываем метод Livewire для перемещения товара в категорию
            if (window.Livewire) {
                try {
                    Livewire.find('{{ $_instance->id }}').call('moveProductToCategory', productId, categoryId);
                } catch (error) {
                    console.error('Ошибка при вызове Livewire:', error);
                }
            } else {
                console.error('Livewire не найден');
            }
        }
    </script>
    @endpush

    <!-- Модальное окно редактирования -->
    @if($showEditModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" style="display: flex; align-items: center; justify-content: center;">
        <div class="fixed inset-0 bg-black opacity-50" wire:click="closeEditModal"></div>
        
        <div class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full mx-4 z-10" style="max-height: 90vh; overflow-y: auto;">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Редактирование категории</h3>
                    <button wire:click="closeEditModal" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="saveCategory">
                    <div class="space-y-4">
                        <!-- Название -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Название <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                wire:model="editForm.name"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                        </div>

                        <!-- Родительская категория -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Родительская категория</label>
                            <select 
                                wire:model="editForm.parent_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Без родителя (корневая)</option>
                                @foreach(\App\Models\Category::where('id', '!=', $editingCategoryId)->get() as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Шаблон -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Шаблон</label>
                            <select 
                                wire:model="editForm.template_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Без шаблона</option>
                                @foreach(\App\Models\Template::all() as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Описание -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Описание</label>
                            <textarea 
                                wire:model="editForm.description"
                                rows="3"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>

                        <!-- SEO поля -->
                        <div class="border-t pt-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">SEO информация</h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SEO заголовок</label>
                                    <input 
                                        type="text" 
                                        wire:model="editForm.seo_title"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SEO описание</label>
                                    <textarea 
                                        wire:model="editForm.seo_description"
                                        rows="2"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SEO ключевые слова</label>
                                    <input 
                                        type="text" 
                                        wire:model="editForm.seo_keywords"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="keyword1, keyword2, keyword3">
                                </div>
                            </div>
                        </div>

                        <!-- Дополнительные настройки -->
                        <div class="border-t pt-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Дополнительные настройки</h4>
                            
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        wire:model="editForm.is_active"
                                        id="is_active"
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <label for="is_active" class="ml-2 text-sm text-gray-700">Активна</label>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Порядок сортировки</label>
                                    <input 
                                        type="number" 
                                        wire:model="editForm.sort_order"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end space-x-3">
                        <button 
                            type="button"
                            wire:click="closeEditModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Отмена
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 text-sm font-medium text-black bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</x-filament::page>
