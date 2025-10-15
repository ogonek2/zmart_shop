@extends('layouts.app')

@section('seo')
    <title>ZMART - Інтернет-магазин техніки</title>
    <meta name="description" content="Широкий вибір якісної техніки від провідних брендів. Швидка доставка по всій Україні. Офіційна гарантія.">
@endsection

@section('content')
<!-- Hero Section - Modern Clean Style -->
<section class="bg-gradient-to-br from-slate-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-5 gap-8 items-center">
            <!-- Left: Quick Categories -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Швидкий доступ</h3>
                    <div class="space-y-3">
                        @foreach(get_all_category()->whereNull('parent_id')->take(6) as $category)
                        <a href="{{ route('catalog_category_page', $category->url) }}" 
                           class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition-all duration-200 group border border-transparent hover:border-gray-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-folder text-white text-sm"></i>
                                </div>
                                <span class="font-medium text-gray-700 group-hover:text-gray-900">{{ $category->name }}</span>
                            </div>
                            <span class="text-sm text-gray-400 bg-gray-100 px-2 py-1 rounded-full">{{ $category->products()->count() }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Center: Main Hero -->
            <div class="lg:col-span-3">
                <div class="relative">
                    <!-- Main Banner -->
                    <div class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 rounded-3xl p-8 md:p-12 text-white relative overflow-hidden">
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 right-0 w-40 h-40 bg-white opacity-10 rounded-full transform translate-x-20 -translate-y-20"></div>
                        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white opacity-5 rounded-full transform -translate-x-16 translate-y-16"></div>
                        
                        <div class="relative z-10">
                            <div class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full text-sm font-medium mb-6">
                                <span class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                                Онлайн зараз
                            </div>
                            
                            <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                                Техніка для вашого
                                <span class="text-yellow-300">дому</span>
                            </h1>
                            
                            <p class="text-xl text-emerald-100 mb-8 leading-relaxed">
                                Знайдіть ідеальну техніку від провідних брендів з гарантією якості та швидкою доставкою
                            </p>
                            
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="/catalog" class="bg-white text-emerald-600 px-8 py-4 rounded-xl font-bold hover:bg-gray-50 transition-all duration-200 text-center shadow-lg">
                                    <i class="fas fa-search mr-2"></i>
                                    Шукати товари
                                </a>
                                <a href="#categories" class="border-2 border-white text-white px-8 py-4 rounded-xl font-bold hover:bg-white hover:text-emerald-600 transition-all duration-200 text-center">
                                    <i class="fas fa-th-large mr-2"></i>
                                    Категорії
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-emerald-600 mb-2">500+</div>
                <div class="text-gray-600 font-medium">Товарів</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-teal-600 mb-2">10K+</div>
                <div class="text-gray-600 font-medium">Клієнтів</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-cyan-600 mb-2">24/7</div>
                <div class="text-gray-600 font-medium">Підтримка</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-emerald-600 mb-2">100%</div>
                <div class="text-gray-600 font-medium">Гарантія</div>
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

<!-- Features Section -->
<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Чому обирають ZMART?</h2>
            <p class="text-xl text-gray-600">Ми забезпечуємо найкращий сервіс для наших клієнтів</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-shipping-fast text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Швидка доставка</h3>
                <p class="text-gray-600">Доставляємо по всій Україні в найкоротші терміни</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-shield-alt text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Гарантія якості</h3>
                <p class="text-gray-600">Офіційна гарантія на всю продукцію</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-headset text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Підтримка 24/7</h3>
                <p class="text-gray-600">Наші фахівці завжди готові допомогти</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-tags text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Кращі ціни</h3>
                <p class="text-gray-600">Конкурентні ціни та регулярні акції</p>
            </div>
        </div>
    </div>
</section>

<!-- Popular Products -->
<section id="categories" class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-16">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 mb-2">Популярні товари</h2>
                <p class="text-xl text-gray-600">Товари, які обирають наші клієнти</p>
            </div>
            <a href="/catalog" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all duration-200 shadow-lg">
                Всі товари
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        
        @include('includes.catalog.recomendet')
    </div>
</section>

<!-- Brands Section -->
<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Наші бренди</h2>
            <p class="text-xl text-gray-600">Працюємо тільки з перевіреними виробниками</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
            @php
                $brands = [
                    ['name' => 'Samsung', 'icon' => 'fa-tv', 'color' => 'from-blue-400 to-blue-600'],
                    ['name' => 'LG', 'icon' => 'fa-tv', 'color' => 'from-red-400 to-red-600'],
                    ['name' => 'Sony', 'icon' => 'fa-headphones', 'color' => 'from-purple-400 to-purple-600'],
                    ['name' => 'Panasonic', 'icon' => 'fa-camera', 'color' => 'from-green-400 to-green-600'],
                    ['name' => 'Philips', 'icon' => 'fa-lightbulb', 'color' => 'from-yellow-400 to-yellow-600'],
                    ['name' => 'Bosch', 'icon' => 'fa-wrench', 'color' => 'from-indigo-400 to-indigo-600'],
                ];
            @endphp
            
            @foreach($brands as $brand)
            <div class="text-center group">
                <div class="w-24 h-24 bg-gradient-to-br {{ $brand['color'] }} rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas {{ $brand['icon'] }} text-white text-2xl"></i>
                </div>
                <h3 class="font-bold text-gray-900">{{ $brand['name'] }}</h3>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="bg-gradient-to-r from-emerald-500 via-teal-500 to-cyan-500 py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl font-bold text-white mb-4">Отримуйте найкращі пропозиції</h2>
        <p class="text-xl text-emerald-100 mb-8">Підпишіться на розсилку та отримуйте інформацію про знижки та новинки</p>
        
        <div class="max-w-md mx-auto flex gap-3">
            <input type="email" placeholder="Ваш email" 
                   class="flex-1 px-6 py-4 rounded-xl border-0 focus:ring-4 focus:ring-white/30 text-gray-900 placeholder-gray-500 shadow-lg">
            <button class="bg-white text-emerald-600 px-8 py-4 rounded-xl font-bold hover:bg-gray-50 transition-all duration-200 shadow-lg">
                Підписатися
            </button>
        </div>
        
        <p class="text-sm text-emerald-200 mt-6">Без спаму. Відписатися можна в будь-який час.</p>
    </div>
</section>
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
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);
    
    // Observe cards for animation
    document.querySelectorAll('.grid > a, .grid > div').forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
        observer.observe(el);
    });
});
</script>
@endsection