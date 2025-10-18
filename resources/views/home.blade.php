@extends('layouts.app')

@section('seo')
    <title>ZMART - Інтернет-магазин техніки</title>
    <meta name="description" content="Широкий вибір якісної побутової техніки та господарських товарів від провідних брендів. Швидка доставка по всій Україні. Офіційна гарантія.">
@endsection

@section('content')
<!-- Hero Section - Modern Information Rich -->
<section class="bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Hero Banner -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center px-6 py-3 bg-emerald-100 text-emerald-800 rounded-full text-sm font-medium mb-8">
                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                Онлайн магазин працює 24/7
                                </div>
            
            <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                Побутова техніка та господарські товари для вашого
                <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">дому</span>
            </h1>
            
            <p class="text-xl text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                Широкий вибір якісної побутової техніки та господарських товарів від провідних брендів. Швидка доставка по всій Україні, офіційна гарантія та підтримка 24/7.
            </p>
            
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mb-8">
                <div class="bg-white rounded-2xl shadow-lg p-2">
                    <search></search>
                            </div>
                
                <!-- Popular Searches -->
                @if(isset($popularSearchTerms) && count($popularSearchTerms) > 0)
                <div class="mt-4 flex flex-wrap justify-center gap-2">
                    <span class="text-sm text-gray-500">Популярні запити:</span>
                    @foreach($popularSearchTerms as $term)
                    <a href="/search?q={{ urlencode($term) }}" class="text-sm bg-white px-3 py-1 rounded-full text-emerald-600 hover:bg-emerald-50 transition-colors">{{ $term }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Key Benefits -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="text-center group">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-shipping-fast text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Швидка доставка</h3>
                <p class="text-gray-600">Доставляємо по всій Україні за 1-3 дні</p>
            </div>

            <div class="text-center group">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Офіційна гарантія</h3>
                <p class="text-gray-600">Гарантія якості на всю продукцію</p>
                            </div>
                            
            <div class="text-center group">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-headset text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Підтримка 24/7</h3>
                <p class="text-gray-600">Наші фахівці завжди готові допомогти</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-white rounded-3xl shadow-xl p-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-emerald-600 mb-2">500+</div>
                    <div class="text-gray-600 font-medium">Товарів в каталозі</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-teal-600 mb-2">10K+</div>
                    <div class="text-gray-600 font-medium">Задоволених клієнтів</div>
            </div>
            <div class="text-center">
                    <div class="text-4xl font-bold text-cyan-600 mb-2">5+</div>
                    <div class="text-gray-600 font-medium">Років на ринку</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-emerald-600 mb-2">100%</div>
                    <div class="text-gray-600 font-medium">Оригінальна продукція</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Grid -->
<section class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Категорії товарів</h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">Виберіть потрібну категорію та знайдіть ідеальну техніку для вашого дому</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
            @foreach (get_all_category()->whereNull('parent_id')->take(12) as $index => $category)
            @php
                // Получаем изображение для категории
                $categoryImage = null;
                $latestProduct = $category->products()->latest()->first();
                if ($latestProduct && $latestProduct->image_path) {
                    $categoryImage = $latestProduct->image_path;
                }
                
                // Если в категории нет товаров, ищем в дочерних категориях
                if (!$categoryImage) {
                    $childCategories = $category->childCategories()->where('is_active', true)->get();
                    foreach ($childCategories as $childCategory) {
                        $childProduct = $childCategory->products()->latest()->first();
                        if ($childProduct && $childProduct->image_path) {
                            $categoryImage = $childProduct->image_path;
                            break;
                        }
                    }
                }
                
                // Подсчитываем общее количество товаров
                $totalProductCount = $category->products()->count();
                foreach ($category->childCategories()->where('is_active', true)->get() as $childCategory) {
                    $totalProductCount += $childCategory->products()->count();
                }
                
                // Цветовые градиенты для карточек
                $gradients = [
                    'from-pink-400 to-rose-500',
                    'from-purple-400 to-indigo-500', 
                    'from-blue-400 to-cyan-500',
                    'from-green-400 to-emerald-500',
                    'from-yellow-400 to-orange-500',
                    'from-red-400 to-pink-500',
                    'from-indigo-400 to-purple-500',
                    'from-cyan-400 to-blue-500',
                    'from-emerald-400 to-green-500',
                    'from-orange-400 to-red-500',
                    'from-teal-400 to-cyan-500',
                    'from-violet-400 to-purple-500'
                ];
                $gradient = $gradients[$index % count($gradients)];
            @endphp
            
            <a href="{{ route('catalog_category_page', $category->url) }}" 
               class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 text-center transform hover:-translate-y-2">
                <div class="aspect-square bg-gradient-to-br {{ $gradient }} rounded-2xl mb-4 overflow-hidden relative">
                    @if($categoryImage)
                        <img src="{{ $categoryImage }}" 
                             alt="{{ $category->name }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-folder text-white text-4xl opacity-80"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                <h3 class="font-bold text-gray-900 text-sm mb-2 group-hover:text-emerald-600 transition-colors">{{ $category->name }}</h3>
                <p class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full inline-block">{{ $totalProductCount }} товарів</p>
            </a>
            @endforeach
        </div>
        
        <div class="text-center mt-12">
            <a href="/catalog" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all duration-200 shadow-lg">
                Всі категорії
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
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
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 popular-products-grid">
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
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -20px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                // Убираем элемент из наблюдения после анимации
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe different types of elements with different animations
    const animateElements = [
        // Popular products with fast staggered animation
        { selector: '.popular-products-grid .product-card', stagger: true, delay: 0.02 },
        // Other product cards with staggered animation
        { selector: '.product-card:not(.popular-products-grid .product-card)', stagger: true, delay: 0.03 },
        // Grid items with staggered animation
        { selector: '.grid > a, .grid > div:not(.product-card)', stagger: true, delay: 0.05 },
        // Stats and text elements without stagger
        { selector: '.text-center:not(.grid .text-center)', stagger: false, delay: 0 },
        // Sections
        { selector: 'section', stagger: false, delay: 0 }
    ];
    
    animateElements.forEach(({ selector, stagger, delay }) => {
        document.querySelectorAll(selector).forEach((el, index) => {
            // Пропускаем элементы, которые уже анимированы
            if (el.classList.contains('animate-on-scroll')) return;
            
            el.classList.add('animate-on-scroll');
        el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            
            // Разное время анимации для разных элементов
            if (el.closest('.popular-products-grid')) {
                el.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
            } else {
                el.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            }
            
            // Добавляем задержку только для элементов с stagger
            if (stagger) {
                el.style.transitionDelay = `${Math.min(index * delay, 0.15)}s`;
            }
            
        observer.observe(el);
        });
    });
    
    // CSS для анимации
    const style = document.createElement('style');
    style.textContent = `
        .animate-in {
            opacity: 1 !important;
            transform: translateY(0) !important;
        }
        
        .animate-on-scroll {
            will-change: opacity, transform;
        }
        
        /* Специальные анимации для разных типов элементов */
        .product-card.animate-on-scroll {
            transform: translateY(30px) scale(0.95);
        }
        
        .product-card.animate-in {
            transform: translateY(0) scale(1) !important;
        }
        
        /* Быстрая анимация для популярных товаров */
        .popular-products-grid .product-card.animate-on-scroll {
            transform: translateY(20px) scale(0.98);
            transition: opacity 0.25s ease, transform 0.25s ease;
        }
        
        .popular-products-grid .product-card.animate-in {
            transform: translateY(0) scale(1) !important;
        }
        
        /* Анимация для секций */
        section.animate-on-scroll {
            transform: translateY(40px);
        }
        
        section.animate-in {
            transform: translateY(0) !important;
        }
        
        /* Анимация для статистики */
        .text-center.animate-on-scroll {
            transform: translateY(15px);
        }
        
        .text-center.animate-in {
            transform: translateY(0) !important;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection