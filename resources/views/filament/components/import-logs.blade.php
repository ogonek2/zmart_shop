<div class="space-y-4">
    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            📋 Последние логи импорта товаров
        </h3>
        
        @if(empty($logs))
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Логи импорта не найдены
                </p>
            </div>
        @else
            <div class="max-h-96 overflow-y-auto space-y-2">
                @foreach($logs as $log)
                    <div class="bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-700 p-3">
                        <pre class="text-xs text-gray-700 dark:text-gray-300 whitespace-pre-wrap font-mono">{{ $log }}</pre>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                Отображены последние 50 записей, связанных с импортом
            </div>
        @endif
    </div>
    
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
        <h4 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">
            💡 Совет по использованию импорта
        </h4>
        <ul class="text-xs text-blue-800 dark:text-blue-200 space-y-1 list-disc list-inside">
            <li>Используйте режим предпросмотра перед импортом больших файлов</li>
            <li>Скачайте шаблон для правильного формата данных</li>
            <li>Проверяйте логи при возникновении ошибок</li>
            <li>Максимальный размер файла: 10MB</li>
        </ul>
    </div>
</div>
