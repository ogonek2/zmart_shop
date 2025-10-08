<div class="catalog-tree-node" data-id="{{ $catalog['id'] }}" data-type="{{ $catalog['type'] }}">
    <div class="flex items-center justify-between p-2 rounded hover:bg-gray-100 cursor-pointer {{ $selectedId === $catalog['id'] ? 'bg-green-50 border-l-4 border-green-500' : '' }}"
         style="margin-left: {{ $level * 20 }}px;"
         wire:click="selectItem('{{ $catalog['id'] }}', '{{ $catalog['type'] }}')">
        <div class="flex items-center space-x-2">
            @if(!empty($catalog['children']))
                <svg class="w-4 h-4 text-gray-400 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            @else
                <div class="w-4 h-4"></div>
            @endif
            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
            </svg>
            <span class="text-sm font-medium text-gray-900">{{ $catalog['name'] }}</span>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-xs text-gray-500">{{ $catalog['products_count'] }}</span>
            <span class="px-1.5 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-800">
                {{ $catalog['type_label'] }}
            </span>
        </div>
    </div>
    
    @if(!empty($catalog['children']))
        <div class="ml-4 space-y-1">
            @foreach($catalog['children'] as $child)
                @include('filament.resources.product-resource.pages.catalog-tree-node', ['catalog' => $child, 'level' => $level + 1])
            @endforeach
        </div>
    @endif
</div>
