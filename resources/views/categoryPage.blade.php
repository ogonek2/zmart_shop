@extends('layouts.app')

@section('seo')
    <title>{{ $category->name }} - ZMART</title>
    <meta name="description" content="{{ $category->description ?? 'Купити ' . $category->name . ' в інтернет-магазині ZMART' }}">
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
            <a href="{{ url('/catalog') }}" class="text-gray-600 hover:text-emerald-600 transition-colors">Каталог</a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            <span class="text-gray-900 font-medium">{{ $category->name }}</span>
        </nav>
    </div>
</section>

<!-- Category Header -->
<section class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $category->name }}</h1>
                @if($category->description)
                <p class="text-lg text-emerald-100">{{ $category->description }}</p>
                @endif
            </div>
            <div class="hidden md:block">
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl px-6 py-4 text-white">
                    <div class="text-3xl font-bold">{{ $products->total() }}</div>
                    <div class="text-sm text-emerald-100">{{ $products->total() == 1 ? 'товар' : 'товарів' }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Subcategories -->
@if($subcategories->count() > 0)
<section class="bg-white py-8 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-layer-group text-emerald-600 mr-2"></i>
            Підкатегорії
        </h2>
        
        <div class="flex flex-wrap gap-4">
            @foreach($subcategories as $index => $subcategory)
            @php
                $latestProduct = $subcategory->products()->latest()->first();
                $subcategoryImage = $latestProduct ? $latestProduct->image_path : null;
                $productCount = $subcategory->products()->count();
                
                $gradients = [
                    'from-pink-400 to-rose-500',
                    'from-purple-400 to-indigo-500', 
                    'from-blue-400 to-cyan-500',
                    'from-green-400 to-emerald-500',
                    'from-yellow-400 to-orange-500',
                    'from-red-400 to-pink-500',
                ];
                $gradient = $gradients[$index % count($gradients)];
            @endphp
            
            <a href="{{ route('catalog_category_page', $subcategory->url) }}" 
               class="group flex flex-col items-center text-center hover:opacity-80 transition-all duration-300 w-24">
                <!-- Круглая картинка -->
                <div class="w-20 h-20 bg-gradient-to-br {{ $gradient }} rounded-full overflow-hidden relative mb-2 shadow-md group-hover:shadow-lg transition-shadow duration-300">
                    @if($subcategoryImage)
                        <img src="{{ $subcategoryImage }}" 
                             alt="{{ $subcategory->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-folder-open text-white text-xl opacity-90"></i>
                        </div>
                    @endif
                </div>
                <!-- Название -->
                <h3 class="font-semibold text-gray-900 text-xs w-20 leading-tight group-hover:text-emerald-600 transition-colors overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                    {{ $subcategory->name }}
                </h3>
                <!-- Счетчик -->
                <p class="text-xs text-gray-500 mt-1">{{ $productCount }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Main Content -->
<section class="py-8 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-4 gap-8">
            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                <!-- Filters Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-4">
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white p-4">
                        <h3 class="text-lg font-bold flex items-center">
                            <i class="fas fa-filter mr-2"></i>
                            Фільтри
                        </h3>
                    </div>
                    
                    <form id="filterForm" class="p-4 space-y-4">
                        <!-- Price Range -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3 flex items-center">
                                <i class="fas fa-dollar-sign text-emerald-600 mr-2"></i>
                                Ціна
                            </h4>
                            <div class="flex flex-col gap-3">
                                <input type="number" name="price_min" placeholder="Від" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm">
                                <input type="number" name="price_max" placeholder="До" 
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
                                <input type="checkbox" name="availability" value="1" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
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
                                <input type="checkbox" name="discount" value="1" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
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
                                <input type="checkbox" name="wholesale" value="1" class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500">
                                <span class="text-gray-700">Оптові ціни</span>
                            </label>
                        </div>

                        <!-- Apply Filters Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white py-3 rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all duration-200 shadow-lg">
                            <i class="fas fa-filter mr-2"></i>
                            Застосувати
                        </button>

                        <!-- Reset Filters -->
                        <button type="button" id="resetFilters" class="w-full border-2 border-gray-300 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-50 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Скинути фільтри
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Products -->
            <main class="lg:col-span-3">
                <!-- Sorting & View Options -->
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-6">
                    <!-- Sort -->
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-600 font-medium">Сортувати:</span>
                        <select id="sortSelect" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent bg-white text-sm">
                            <option value="default">За замовчуванням</option>
                            <option value="price_asc">Ціна: від низької</option>
                            <option value="price_desc">Ціна: від високої</option>
                            <option value="name_asc">Назва: А-Я</option>
                            <option value="name_desc">Назва: Я-А</option>
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
                <div id="productsContainer">
                    <product-list 
                        :products="{{ json_encode($products->items()) }}"
                        :pagination="{{ json_encode([
                            'current_page' => $products->currentPage(),
                            'last_page' => $products->lastPage(),
                            'per_page' => $products->perPage(),
                            'total' => $products->total(),
                            'from' => $products->firstItem(),
                            'to' => $products->lastItem(),
                        ]) }}"
                    ></product-list>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="mt-12">
                    <div class="flex items-center justify-center space-x-2">
                        {{-- Previous Page Link --}}
                        @if ($products->onFirstPage())
                            <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        @else
                            <a href="{{ $products->previousPageUrl() }}" 
                               class="px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:border-emerald-500 hover:text-emerald-600 transition-all">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                            @if ($page == $products->currentPage())
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
                        @if ($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" 
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
                        Показано {{ $products->firstItem() }}-{{ $products->lastItem() }} з <span id="productsCount">{{ $products->total() }}</span> товарів
                    </div>
                </div>
                @endif
            </main>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter form elements
    const filterForm = document.getElementById('filterForm');
    const resetFilters = document.getElementById('resetFilters');
    const sortSelect = document.getElementById('sortSelect');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const productsContainer = document.getElementById('productsContainer');
    const productsCount = document.getElementById('productsCount');
    
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

    // Filter form submission
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }

    // Reset filters
    if (resetFilters) {
        resetFilters.addEventListener('click', function() {
            filterForm.reset();
            applyFilters();
        });
    }

    // Sort change
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            applyFilters();
        });
    }

    // Apply filters function
    function applyFilters() {
        showLoading();
        
        const formData = new FormData(filterForm);
        const sortValue = sortSelect ? sortSelect.value : 'default';
        
        // Build URL parameters
        const params = new URLSearchParams();
        
        // Add form data
        for (let [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }
        
        // Add sort parameter
        if (sortValue && sortValue !== 'default') {
            params.append('sort', sortValue);
        }
        
        // Reload page with filters
        window.location.href = '{{ route("catalog_category_page", $category->url) }}?' + params.toString();
    }

    // Show loading spinner
    function showLoading() {
        if (loadingSpinner) {
            loadingSpinner.classList.remove('hidden');
        }
        if (productsContainer) {
            productsContainer.style.opacity = '0.5';
        }
    }

    // Hide loading spinner
    function hideLoading() {
        if (loadingSpinner) {
            loadingSpinner.classList.add('hidden');
        }
        if (productsContainer) {
            productsContainer.style.opacity = '1';
        }
    }

    // Initialize filters from URL parameters
    function initializeFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        
        if (filterForm) {
            // Set price filters
            if (urlParams.get('price_min')) {
                const priceMinInput = filterForm.querySelector('input[name="price_min"]');
                if (priceMinInput) priceMinInput.value = urlParams.get('price_min');
            }
            if (urlParams.get('price_max')) {
                const priceMaxInput = filterForm.querySelector('input[name="price_max"]');
                if (priceMaxInput) priceMaxInput.value = urlParams.get('price_max');
            }
            
            // Set checkboxes
            if (urlParams.get('availability')) {
                const availabilityInput = filterForm.querySelector('input[name="availability"]');
                if (availabilityInput) availabilityInput.checked = true;
            }
            if (urlParams.get('discount')) {
                const discountInput = filterForm.querySelector('input[name="discount"]');
                if (discountInput) discountInput.checked = true;
            }
            if (urlParams.get('wholesale')) {
                const wholesaleInput = filterForm.querySelector('input[name="wholesale"]');
                if (wholesaleInput) wholesaleInput.checked = true;
            }
        }
        
        // Set sort
        if (sortSelect && urlParams.get('sort')) {
            sortSelect.value = urlParams.get('sort');
        }
    }

    // Update pagination links to preserve filters
    function updatePaginationLinks() {
        const paginationLinks = document.querySelectorAll('a[href*="page"]');
        paginationLinks.forEach(link => {
            const url = new URL(link.href);
            const currentParams = new URLSearchParams(window.location.search);
            
            // Add current filter parameters to pagination links
            currentParams.forEach((value, key) => {
                if (key !== 'page') {
                    url.searchParams.set(key, value);
                }
            });
            
            link.href = url.toString();
        });
    }

    // Initialize filters on page load
    initializeFiltersFromURL();
    
    // Update pagination links after page load
    setTimeout(() => {
        updatePaginationLinks();
    }, 100);
});
</script>
@endsection