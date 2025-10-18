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

<!-- Categories Section -->
@if($categories->count() > 0)
<section class="bg-white py-8 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-layer-group text-emerald-600 mr-2"></i>
                            Категорії
        </h2>
        
        <div class="flex flex-wrap gap-4">
            @foreach($categories as $index => $category)
            @php
                $latestProduct = $category->products()->latest()->first();
                $categoryImage = $latestProduct ? $latestProduct->image_path : null;
                $productCount = $category->products()->count();
                
                $gradients = [
                    'from-pink-400 to-rose-500',
                    'from-purple-400 to-indigo-500', 
                    'from-blue-400 to-cyan-500',
                    'from-green-400 to-emerald-500',
                    'from-yellow-400 to-orange-500',
                    'from-red-400 to-pink-500',
                    'from-teal-400 to-cyan-500',
                    'from-indigo-400 to-purple-500',
                ];
                $gradient = $gradients[$index % count($gradients)];
            @endphp
            
                        <a href="{{ route('catalog_category_page', $category->url) }}" 
               class="group flex flex-col items-center text-center hover:opacity-80 transition-all duration-300 w-24">
                <!-- Круглая картинка -->
                <div class="w-20 h-20 bg-gradient-to-br {{ $gradient }} rounded-full overflow-hidden relative mb-2 shadow-md group-hover:shadow-lg transition-shadow duration-300">
                    @if($categoryImage)
                        <img src="{{ $categoryImage }}" 
                             alt="{{ $category->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-folder-open text-white text-xl opacity-90"></i>
                                </div>
                    @endif
                            </div>
                <!-- Название -->
                <h3 class="font-semibold text-gray-900 text-xs w-20 leading-tight group-hover:text-emerald-600 transition-colors overflow-hidden" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                    {{ $category->name }}
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

            <!-- Main Content -->
            <main class="lg:col-span-3">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Каталог товарів</h1>
                    <p class="text-lg text-gray-600">Знайдено <span id="productsCount">{{ $getProducts->total() }}</span> товарів</p>
                </div>

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

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="hidden text-center py-8">
                    <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-emerald-500 bg-white transition ease-in-out duration-150">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-emerald-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Завантаження...
                    </div>
                </div>

                <!-- Products Container -->
                <div id="productsContainer">
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
                </div>
            </main>
        </div>
    </div>
</section>

<!-- Popular Products Section -->
@if(isset($popularProducts) && $popularProducts->count() > 0)
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                <i class="fas fa-fire text-orange-500 mr-3"></i>
                Популярні товари
            </h2>
            <a href="{{ route('catalog') }}" class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center">
                Всі товари
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($popularProducts as $product)
                @php
                    $finalPrice = $product->discount > 0 
                        ? $product->price * (1 - $product->discount / 100) 
                        : $product->price;
                @endphp
                
                <div class="product-card bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
                    <div class="relative overflow-hidden">
                        <!-- Product Image -->
                        <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200">
                            <a href="/catalog/{{ $product->url }}" class="block h-full">
                                <img src="{{ $product->image_path ?: 'https://via.placeholder.com/300x300/f3f4f6/9ca3af?text=Нет+фото' }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </a>
                        </div>
                        
                        <!-- Discount Badge -->
                        @if($product->discount > 0)
                            <div class="absolute top-3 left-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-tag mr-1"></i>-{{ $product->discount }}%
                                </span>
                            </div>
                        @endif
                        
                        <!-- Wholesale Badge -->
                        @if($product->is_wholesale && $product->wholesale_price)
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-boxes mr-1"></i>Опт
                                </span>
                            </div>
                        @endif
                        
                        <!-- Popular Badge -->
                        <div class="absolute bottom-3 left-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-fire mr-1"></i>Популярний
                            </span>
                        </div>
                        
                        <!-- Wishlist Button -->
                        <button class="absolute top-3 right-3 w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-sm transition-all hover:scale-110" 
                                onclick="toggleWishlist({{ $product->id }})">
                            <i class="fas fa-heart text-gray-400 hover:text-red-500 transition-colors"></i>
                        </button>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-4">
                        <h4 class="font-bold text-gray-900 mb-2 line-clamp-2 text-sm">
                            <a href="/catalog/{{ $product->url }}" class="hover:text-emerald-600 transition-colors">
                                {{ $product->name }}
                            </a>
                        </h4>
                        
                        <!-- Price -->
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-lg font-bold text-emerald-600">
                                {{ number_format($finalPrice, 0, ',', ' ') }} ₴
                            </span>
                            @if($product->discount > 0)
                                <span class="text-sm text-gray-500 line-through">
                                    {{ number_format($product->price, 0, ',', ' ') }} ₴
                                </span>
                            @endif
                        </div>
                        
                        <!-- Wholesale Price -->
                        @if($product->is_wholesale && $product->wholesale_price && $product->wholesale_min_quantity)
                            <div class="flex items-center text-xs text-teal-600 mb-3">
                                <i class="fas fa-boxes mr-1"></i>
                                Опт от {{ $product->wholesale_min_quantity }} шт: {{ number_format($product->wholesale_price, 0, ',', ' ') }} ₴
                            </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="/catalog/{{ $product->url }}" 
                               class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold py-2 px-4 rounded-xl transition-all duration-200 text-center text-sm">
                                <i class="fas fa-eye mr-1"></i>Подробнее
                            </a>
                            <button class="w-10 h-10 bg-gray-100 hover:bg-emerald-500 hover:text-white text-gray-600 rounded-xl flex items-center justify-center transition-all duration-200" 
                                    onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $finalPrice }}, '{{ $product->image_path ?: '' }}', '{{ $product->articule ?: 'Не указан' }}', {{ $product->availability ?: 1 }}{{ $product->is_wholesale ? ', true, ' . $product->wholesale_price . ', ' . $product->wholesale_min_quantity : '' }})">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

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

@section('styles')
<style>
/* Pagination styles */
.pagination-container {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    margin-top: 3rem;
}

.pagination-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1rem;
    border: 2px solid #d1d5db;
    color: #374151;
    text-decoration: none;
    border-radius: 0.5rem;
    transition: all 0.2s;
    min-width: 2.5rem;
    height: 2.5rem;
}

.pagination-link:hover {
    border-color: #10b981;
    color: #10b981;
    background-color: #f0fdf4;
}

.pagination-link.active {
    background: linear-gradient(to right, #10b981, #14b8a6);
    color: white;
    border-color: #10b981;
    font-weight: bold;
    box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
}

.pagination-link.disabled {
    background-color: #f3f4f6;
    color: #9ca3af;
    cursor: not-allowed;
    border-color: #e5e7eb;
}

.pagination-link.disabled:hover {
    border-color: #e5e7eb;
    color: #9ca3af;
    background-color: #f3f4f6;
}

.pagination-info {
    text-align: center;
    margin-top: 1rem;
    font-size: 0.875rem;
    color: #6b7280;
}
</style>
@endsection

@section('scripts')
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
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });

    // Reset filters
    resetFilters.addEventListener('click', function() {
        filterForm.reset();
        applyFilters();
    });

    // Sort change
    sortSelect.addEventListener('change', function() {
        applyFilters();
    });

    // Apply filters function
    function applyFilters() {
        showLoading();
        
        const formData = new FormData(filterForm);
        const sortValue = sortSelect.value;
        
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
        
        // Add AJAX header
        const headers = {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        };
        
        // Use GET request with parameters
        const url = '{{ route("catalog") }}?' + params.toString();
        
        fetch(url, {
            method: 'GET',
            headers: headers
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateProducts(data.products, data.pagination);
                updateProductsCount(data.pagination.total);
            } else {
                console.error('Filter error:', data.message);
                showError('Помилка при застосуванні фільтрів');
            }
        })
        .catch(error => {
            console.error('Filter error:', error);
            showError('Помилка при застосуванні фільтрів');
        })
        .finally(() => {
            hideLoading();
        });
    }

    // Update products display
    function updateProducts(products, pagination) {
        // For now, we'll reload the page with filters
        const formData = new FormData(filterForm);
        const sortValue = sortSelect.value;
        
        if (sortValue && sortValue !== 'default') {
            formData.append('sort', sortValue);
        }
        
        const params = new URLSearchParams();
        for (let [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }
        
        // Reload page with filters
        window.location.href = '{{ route("catalog") }}?' + params.toString();
    }

    // Update products count
    function updateProductsCount(count) {
        if (productsCount) {
            productsCount.textContent = count;
        }
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

    // Show error message
    function showError(message) {
        // Create error notification
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);
        
        // Remove after 5 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    // Initialize filters from URL parameters
    function initializeFiltersFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Set price filters
        if (urlParams.get('price_min')) {
            filterForm.querySelector('input[name="price_min"]').value = urlParams.get('price_min');
        }
        if (urlParams.get('price_max')) {
            filterForm.querySelector('input[name="price_max"]').value = urlParams.get('price_max');
        }
        
        // Set checkboxes
        if (urlParams.get('availability')) {
            filterForm.querySelector('input[name="availability"]').checked = true;
        }
        if (urlParams.get('discount')) {
            filterForm.querySelector('input[name="discount"]').checked = true;
        }
        if (urlParams.get('wholesale')) {
            filterForm.querySelector('input[name="wholesale"]').checked = true;
        }
        
        // Set sort
        if (urlParams.get('sort')) {
            sortSelect.value = urlParams.get('sort');
        }
    }

    // Update pagination links to preserve filters
    function updatePaginationLinks() {
        const paginationLinks = document.querySelectorAll('#productsContainer a[href*="page"]');
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