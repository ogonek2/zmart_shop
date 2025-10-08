<x-filament::page>
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">
                Управление связями для товара: {{ $this->record->name }}
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Категории
                    </label>
                    <select 
                        wire:model="selectedCategories" 
                        multiple 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        size="8"
                    >
                        @foreach($this->getCategories() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">
                        Удерживайте Ctrl для выбора нескольких категорий
                    </p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Каталоги/Группы
                    </label>
                    <select 
                        wire:model="selectedCatalogs" 
                        multiple 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        size="8"
                    >
                        @foreach($this->getCatalogs() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500">
                        Удерживайте Ctrl для выбора нескольких каталогов
                    </p>
                </div>
            </div>
            
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Порядок сортировки
                    </label>
                    <input 
                        type="number" 
                        wire:model="sortOrder" 
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>
                
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        wire:model="isPrimary" 
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                    >
                    <label class="ml-2 block text-sm text-gray-900">
                        Основные связи
                    </label>
                </div>
            </div>
            
            <div class="mt-6">
                <h3 class="text-md font-medium text-gray-900 mb-2">Текущие связи:</h3>
                <div class="space-y-2">
                    @if($this->record->categories->count() > 0)
                        <div>
                            <span class="font-medium">Категории:</span>
                            @foreach($this->record->categories as $category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    
                    @if($this->record->catalogs->count() > 0)
                        <div>
                            <span class="font-medium">Каталоги:</span>
                            @foreach($this->record->catalogs as $catalog)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-1">
                                    {{ $catalog->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                    
                    @if($this->record->categories->count() == 0 && $this->record->catalogs->count() == 0)
                        <p class="text-gray-500">Нет активных связей</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament::page>
