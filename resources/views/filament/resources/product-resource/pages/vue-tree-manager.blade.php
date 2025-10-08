<x-filament::page>
    <div id="category-tree-app">
        <div style="padding: 40px; text-align: center; color: #666;">
            <div style="display: inline-block;">
                <svg style="width: 48px; height: 48px; animation: spin 1s linear infinite;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle style="opacity: 0.25;" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path style="opacity: 0.75;" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <p style="margin-top: 16px; font-size: 16px;">Загрузка управления категориями...</p>
        </div>
    </div>
    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</x-filament::page>

@push('scripts')
<script src="{{ mix('js/app.js') }}"></script>
<script>
    console.log('=== Vue Tree Manager Debug ===');
    console.log('1. Page loaded');
    console.log('2. Element exists:', !!document.getElementById('category-tree-app'));
    console.log('3. Vue available:', typeof Vue !== 'undefined');
    
    // Проверяем загрузку через интервал
    let checkInterval = setInterval(() => {
        const el = document.getElementById('category-tree-app');
        if (el && el.innerHTML.includes('Загрузка')) {
            console.log('Vue компонент еще не смонтирован');
        } else if (el && !el.innerHTML.includes('Загрузка')) {
            console.log('Vue компонент успешно смонтирован!');
            clearInterval(checkInterval);
        }
    }, 1000);
    
    setTimeout(() => clearInterval(checkInterval), 10000);
</script>
@endpush
