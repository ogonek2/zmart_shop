@extends('layouts.app')

@section('seo')
    <title>Каталог товарів - ZMART</title>
    <meta name="description" content="Широкий вибір якісної техніки від провідних брендів. Швидка доставка по всій Україні.">
@endsection

@section('content')
<!-- Breadcrumbs -->
<section class="bg-gray-50 py-4">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ url('/') }}" class="text-gray-600 hover:text-emerald-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">Каталог</span>
        </nav>
    </div>
</section>

<!-- Main Content -->
<section class="py-8 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                <!-- Categories Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white p-4">
                        <h3 class="text-lg font-bold flex items-center">
                            <i class="fas fa-th-large mr-2"></i>
                            Категорії
                        </h3>
                    </div>
                    
                    <div class="p-2">
                        @foreach (get_all_category()->whereNull('parent_id') as $category)
                        <a href="{{ route('catalog_category_page', $category->url) }}" 
                           class="flex items-center justify-between p-3 hover:bg-emerald-50 rounded-lg transition-all duration-200 group">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-folder text-white text-xs"></i>
                                </div>
                                <span class="text-gray-700 group-hover:text-emerald-700 font-medium">{{ $category->name }}</span>
                            </div>
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">{{ $category->products()->count() }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Filters Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white p-4">
                        <h3 class="text-lg font-bold flex items-center">
                            <i class="fas fa-filter mr-2"></i>
                            Фільтри
                        </h3>
                    </div>
                    
                    <div class="p-4 space-y-4">
                        <!-- Price Range -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-dollar-sign text-emerald-600 mr-2"></i>
                                Ціна
                            </h4>
                            <div class="flex flex-col gap-3">
                                <input type="number" placeholder="Від" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                                <input type="number" placeholder="До" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                            </div>
                        </div>

                        <!-- Availability -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-check-circle text-emerald-600 mr-2"></i>
                                Наявність
                            </h4>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="text-gray-700">В наявності</span>
                            </label>
                        </div>

                        <!-- Discounts -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-tag text-emerald-600 mr-2"></i>
                                Знижки
                            </h4>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="text-gray-700">Зі знижкою</span>
                            </label>
                        </div>

                        <!-- Wholesale -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-boxes text-emerald-600 mr-2"></i>
                                Опт
                            </h4>
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="text-gray-700">Оптові ціни</span>
                            </label>
                        </div>

                        <!-- Apply Filters Button -->
                        <button class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white py-3 rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all duration-200 shadow-lg">
                            <i class="fas fa-filter mr-2"></i>
                            Застосувати
                        </button>

                        <!-- Reset Filters -->
                        <button class="w-full border-2 border-gray-300 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Скинути фільтри
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="lg:col-span-3">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Каталог товарів</h1>
                    <p class="text-lg text-gray-600">Знайдено {{ $getProducts->total() }} товарів</p>
                </div>

                <!-- Sorting & View Options -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                    <!-- Sort -->
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-600 font-medium">Сортувати:</span>
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white text-sm">
                            <option value="default">За замовчуванням</option>
                            <option value="price_asc">Ціна: від низької</option>
                            <option value="price_desc">Ціна: від високої</option>
                            <option value="name_asc">Назва: А-Я</option>
                            <option value="name_desc">Назва: Я-А</option>
                            <option value="newest">Новинки</option>
                        </select>
                    </div>

                    <!-- View Toggle -->
                    <div class="flex items-center space-x-2">
                        <button class="p-2 border-2 border-emerald-500 bg-emerald-50 text-emerald-600 rounded-lg transition-all">
                            <i class="fas fa-th"></i>
                        </button>
                        <button class="p-2 border-2 border-gray-300 text-gray-600 rounded-lg hover:border-emerald-500 hover:text-emerald-600 transition-all">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                <product-list 
                    :products="{{ json_encode($getProducts->items()) }}"
                    :pagination="{{ json_encode([
                        'current_page' => $getProducts->currentPage(),
                        'last_page' => $getProducts->lastPage(),
                        'per_page' => $getProducts->perPage(),
                        'total' => $getProducts->total(),
                        'from' => $getProducts->firstItem(),
                        'to' => $getProducts->lastItem(),
                    ]) }}"
                ></product-list>

                <!-- Pagination -->
                @if($getProducts->hasPages())
                <div class="mt-12">
                    <div class="flex items-center justify-center space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($getProducts->onFirstPage())
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $getProducts->previousPageUrl() }}" 
                               class="px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:border-emerald-500 hover:text-emerald-600 transition-all">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($getProducts->getUrlRange(1, $getProducts->lastPage()) as $page => $url)
                            @if ($page == $getProducts->currentPage())
                                <span class="px-4 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-lg font-bold shadow-lg">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" 
                                   class="px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:border-emerald-500 hover:text-emerald-600 transition-all">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($getProducts->hasMorePages())
                            <a href="{{ $getProducts->nextPageUrl() }}" 
                               class="px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:border-emerald-500 hover:text-emerald-600 transition-all">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        @else
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        @endif
                    </div>

                    <!-- Pagination Info -->
                    <div class="text-center mt-4 text-sm text-gray-600">
                        Показано {{ $getProducts->firstItem() }}-{{ $getProducts->lastItem() }} з {{ $getProducts->total() }} товарів
                    </div>
                </div>
                @endif
            </main>
        </div>
    </div>
</section>

<!-- Categories Banner -->
<section class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Не знайшли що шукали?</h2>
        <p class="text-xl text-emerald-100 mb-8">Зв'яжіться з нами, і ми допоможемо підібрати ідеальний товар</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('kontaktna_informatsiia') }}" 
               class="bg-white text-emerald-600 px-8 py-4 rounded-xl font-bold hover:bg-gray-50 transition-all duration-200 shadow-lg">
                <i class="fas fa-phone mr-2"></i>
                Зателефонувати
            </a>
            <a href="mailto:info@zmart.com" 
               class="border-2 border-white text-white px-8 py-4 rounded-xl font-bold hover:bg-white hover:text-emerald-600 transition-all duration-200">
                <i class="fas fa-envelope mr-2"></i>
                Написати нам
            </a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sorting functionality
    const sortSelect = document.querySelector('select');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            // Implement sorting logic
            console.log('Sort by:', sortValue);
        });
    }
    
    // View toggle
    const viewButtons = document.querySelectorAll('.flex.items-center.space-x-2 button');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            viewButtons.forEach(btn => {
                btn.classList.remove('border-emerald-500', 'bg-emerald-50', 'text-emerald-600');
                btn.classList.add('border-gray-300', 'text-gray-600');
            });
            this.classList.add('border-emerald-500', 'bg-emerald-50', 'text-emerald-600');
            this.classList.remove('border-gray-300', 'text-gray-600');
        });
    });
});
</script>
@endsection