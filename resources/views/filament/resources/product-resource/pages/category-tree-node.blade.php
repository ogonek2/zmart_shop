<div class="category-tree-node" 
    data-category-id="{{ str_replace('category_', '', $category['id']) }}"
    data-type="{{ $category['type'] }}"
    style="margin-left: {{ ($level ?? 0) * 20 }}px;">
    
    <div class="flex items-center justify-between p-2 rounded hover:bg-gray-100 {{ $selectedId === $category['id'] ? 'bg-blue-50 border-l-4 border-blue-500' : '' }} category-drop-zone"
         data-category-id="{{ str_replace('category_', '', $category['id']) }}"
         data-category-name="{{ $category['name'] }}">
        <div class="flex items-center space-x-2">
            <!-- Drag Handle -->
            <div class="drag-handle p-1" title="Перетащить">
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                </svg>
            </div>
            
            <!-- Визуальные индикаторы вложенности -->
            @if(($level ?? 0) > 0)
                <div class="flex items-center">
                    @for($i = 0; $i < ($level ?? 0); $i++)
                        <div class="w-4 h-px bg-gray-300 mr-1"></div>
                    @endfor
                    <div class="w-2 h-2 border-l border-b border-gray-300 mr-1"></div>
                </div>
            @endif
            
            @if(!empty($category['children']))
                <svg class="w-4 h-4 text-gray-400 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            @else
                <div class="w-4 h-4"></div>
            @endif
            
            <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
            </svg>
            <span 
                class="text-sm font-medium text-gray-900 cursor-pointer" 
                wire:click="selectItem('{{ $category['id'] }}', '{{ $category['type'] }}')">
                {{ $category['name'] }}
            </span>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-xs text-gray-500">{{ $category['products_count'] }}</span>
            <div class="flex space-x-1">
                <button 
                    wire:click.stop="createCategory('Новая подкатегория', {{ str_replace('category_', '', $category['id']) }})"
                    class="p-1 text-gray-400 hover:text-blue-600"
                    title="Добавить подкатегорию">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                </button>
                <button 
                    type="button"
                    onclick="event.stopPropagation(); (function() {
                        var newName = prompt('Новое название:', '{{ addslashes($category['name']) }}');
                        if (newName !== null && newName.trim() !== '') {
                            @this.updateCategory({{ str_replace('category_', '', $category['id']) }}, newName);
                        }
                    })();"
                    class="p-1 text-gray-400 hover:text-yellow-600"
                    title="Редактировать">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                </button>
                <button 
                    type="button"
                    onclick="event.stopPropagation(); (function() {
                        if (confirm('Вы уверены, что хотите удалить категорию \'{{ addslashes($category['name']) }}\'? Все подкатегории будут перемещены на уровень выше.')) {
                            @this.deleteCategory({{ str_replace('category_', '', $category['id']) }});
                        }
                    })();"
                    class="p-1 text-gray-400 hover:text-red-600"
                    title="Удалить">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    @if(!empty($category['children']))
        <div class="category-children mt-1 space-y-1">
            @foreach($category['children'] as $child)
                @include('filament.resources.product-resource.pages.category-tree-node', ['category' => $child, 'level' => ($level ?? 0) + 1])
            @endforeach
        </div>
    @endif
</div>
