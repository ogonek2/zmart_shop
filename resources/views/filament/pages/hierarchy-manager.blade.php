<x-filament::page>
    <div class="flex items-center justify-center h-96">
        <div class="text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">Управление иерархией</h3>
            <p class="mt-2 text-sm text-gray-500">Перейдите к управлению товарами для доступа к инструментам иерархии</p>
            <div class="mt-6">
                <a href="{{ route('filament.resources.products.tree') }}" 
                   class="inline-flex items-center px-4 py-2 border border-black text-sm font-medium rounded-md shadow-sm text-black bg-blue hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd"/>
                    </svg>
                    Открыть управление иерархией
                </a>
            </div>
        </div>
    </div>
</x-filament::page>
