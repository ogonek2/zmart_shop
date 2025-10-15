@extends('layouts.app')

@section('seo')
    <title>{{ $product->name }} - ZMART</title>
    <meta name="description" content="{{ $product->description ? strip_tags(mb_substr($product->description, 0, 160)) : $product->name }}">
@endsection

@section('content')
<!-- Breadcrumbs -->
<div class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex items-center space-x-2 text-sm">
            <a href="{{ url('/') }}" class="text-gray-600 hover:text-emerald-600 transition-colors">
                <i class="fas fa-home"></i>
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            @if ($product->categories && $product->categories->first())
            <a href="{{ route('catalog_category_page', $product->categories->first()->url) }}" 
               class="text-gray-600 hover:text-emerald-600 transition-colors truncate">
                {{ $product->categories->first()->name }}
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            @endif
            <span class="text-gray-900 font-medium truncate">{{ $product->name }}</span>
        </nav>
    </div>
</div>

<!-- Main Product Section -->
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 lg:items-start">
            
            <!-- Image Gallery -->
            <div class="lg:sticky lg:top-24">
                @php
                    $allImages = [];
                    if ($product->image_path) {
                        $allImages[] = $product->image_path;
                    }
                    if ($images && $images->count() > 0) {
                        foreach ($images as $image) {
                            $allImages[] = $image->src;
                        }
                    }
                    $totalImages = count($allImages);
                @endphp

                <!-- Main Image Container with Navigation -->
                <div class="relative">
                    <div class="aspect-square bg-white rounded-2xl overflow-hidden border border-gray-200 relative group">
                        <!-- Badges -->
                        @if ($product->discount > 0)
                        <div class="absolute top-4 left-4 z-10">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-500 text-white shadow-lg">
                                -{{ $product->discount }}%
                            </span>
                        </div>
                        @endif
                        
                        @if($product->is_wholesale && $product->wholesale_price)
                        <div class="absolute top-4 right-4 z-10">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-yellow-500 text-white shadow-lg">
                                <i class="fas fa-boxes mr-1"></i>Опт
                            </span>
                        </div>
                        @endif

                        <!-- Main Image -->
                        <div id="mainImageContainer" class="w-full h-full">
                            @if ($product->image_path)
                                <img src="{{ $product->image_path }}" 
                                     alt="{{ $product->name }}" 
                                     id="mainImage"
                                     class="w-full h-full object-contain p-8 transition-opacity duration-300"
                                     onerror="this.src='https://via.placeholder.com/800x800/f9fafb/9ca3af?text=Фото+відсутнє'">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-50">
                                    <i class="fas fa-image text-gray-300 text-6xl"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Navigation Arrows -->
                        @if($totalImages > 1)
                        <button onclick="prevImage()" 
                                class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all hover:scale-110 z-10">
                            <i class="fas fa-chevron-left text-gray-700"></i>
                        </button>
                        <button onclick="nextImage()" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-all hover:scale-110 z-10">
                            <i class="fas fa-chevron-right text-gray-700"></i>
                        </button>
                        
                        <!-- Image Counter -->
                        <div class="absolute bottom-4 right-4 bg-black/60 backdrop-blur-sm text-white px-3 py-1 rounded-full text-sm font-medium z-10">
                            <span id="currentImageIndex">1</span> / {{ $totalImages }}
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Thumbnails Slider -->
                @if($totalImages > 1)
                <div class="mt-4 relative">
                    <div class="flex space-x-3 overflow-x-auto pb-2 scrollbar-hide" id="thumbnailsContainer">
                        @foreach($allImages as $index => $imageSrc)
                            <button onclick="changeImage('{{ $imageSrc }}', {{ $index }})" 
                                    data-index="{{ $index }}"
                                    class="thumbnail-btn flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-all hover:border-emerald-500 hover:scale-105 {{ $index === 0 ? 'border-emerald-500' : 'border-gray-200' }}">
                                <img src="{{ $imageSrc }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.src='https://via.placeholder.com/100x100/f9fafb/9ca3af?text=Фото'">
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="mt-8 lg:mt-0">
                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 sm:text-4xl">{{ $product->name }}</h1>
                
                <!-- Article & Availability -->
                <div class="mt-3 flex items-center space-x-4">
                    <p class="text-sm text-gray-500">
                        Артикул: <span class="font-medium text-gray-900">{{ $product->articule ?? 'Не вказано' }}</span>
                    </p>
                    @if ($product->availability == 1)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>В наявності
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>Немає в наявності
                        </span>
                    @endif
                </div>

                <!-- Short Description -->
                @if($product->description)
                <p class="mt-4 text-gray-600 leading-relaxed">
                    {{ mb_substr(strip_tags($product->description), 0, 200) }}{{ mb_strlen(strip_tags($product->description)) > 200 ? '...' : '' }}
                </p>
                @endif

                <!-- Price Section -->
                <div class="mt-8 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-6 border border-gray-200">
                    <div class="flex items-end justify-between flex-wrap gap-4">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Ціна</p>
                            <div class="flex items-baseline space-x-3">
                                <span class="text-5xl font-bold text-gray-900">
                                    {{ number_format($product->price, 0, ',', ' ') }}
                                </span>
                                <span class="text-2xl font-bold text-gray-900">₴</span>
                            </div>
                            @if ($product->discount > 0)
                                <div class="flex items-center space-x-2 mt-2">
                                    <span class="text-xl text-gray-400 line-through">
                                        {{ number_format($product->price * (1 + $product->discount / 100), 0, ',', ' ') }} ₴
                                    </span>
                                    <span class="text-sm font-medium text-red-600">
                                        Економія {{ number_format($product->price * $product->discount / 100, 0, ',', ' ') }} ₴
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Wholesale Price -->
                    @if($product->is_wholesale && $product->wholesale_price && $product->wholesale_min_quantity)
                    <div class="mt-6 pt-6 border-t border-gray-300">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-2 mb-1">
                                    <i class="fas fa-boxes text-yellow-600"></i>
                                    <span class="text-sm font-medium text-gray-700">Оптова ціна</span>
                                </div>
                                <div class="flex items-baseline space-x-2">
                                    <span class="text-3xl font-bold text-yellow-700">
                                        {{ number_format($product->wholesale_price, 0, ',', ' ') }}
                                    </span>
                                    <span class="text-xl font-bold text-yellow-700">₴</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">від</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $product->wholesale_min_quantity }}</p>
                                <p class="text-xs text-gray-500">одиниць</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Add to Cart -->
                <div class="mt-8">
                    <div class="flex space-x-3">
                        <cart-button 
                            class="flex-1"
                            data-product-id="{{ $product->id }}" 
                            data-product-name="{{ $product->name }}"
                            data-product-price="{{ $product->price }}"
                            data-product-image="{{ $product->image_path }}" 
                            data-product-articule="{{ $product->articule }}"
                            @if($product->is_wholesale && $product->wholesale_price && $product->wholesale_min_quantity)
                            data-product-is-wholesale="true"
                            data-product-wholesale-price="{{ $product->wholesale_price }}"
                            data-product-wholesale-min-quantity="{{ $product->wholesale_min_quantity }}"
                            @endif
                            id="{{ $product->id }}" 
                            name="{{ $product->name }}"
                            price="{{ $product->price }}"
                            image="{{ $product->image_path }}" 
                            articule="{{ $product->articule }}"
                            availability="{{ $product->availability ?? 1 }}"
                            @if($product->is_wholesale && $product->wholesale_price && $product->wholesale_min_quantity)
                            is-wholesale="true"
                            wholesale-price="{{ $product->wholesale_price }}"
                            wholesale-min-quantity="{{ $product->wholesale_min_quantity }}"
                            @endif
                        ></cart-button>
                        
                        <button onclick="toggleWishlist({{ $product->id }})" 
                                id="wishlistBtn"
                                class="w-14 h-14 bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 rounded-xl transition-all flex items-center justify-center flex-shrink-0 group">
                            <i class="far fa-heart text-xl group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>
                    
                    <p class="mt-3 text-sm text-center text-gray-500">
                        <i class="fas fa-shield-alt text-emerald-600 mr-1"></i>
                        Гарантія безпечної оплати
                    </p>
                </div>

                <!-- Features -->
                <div class="mt-8 grid grid-cols-2 gap-4">
                    <div class="flex items-center space-x-3 p-4 bg-white rounded-xl border border-gray-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-truck text-emerald-600"></i>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Доставка</p>
                            <p class="text-xs text-gray-500">По всій Україні</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-4 bg-white rounded-xl border border-gray-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-shield-alt text-blue-600"></i>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Гарантія</p>
                            <p class="text-xs text-gray-500">12 місяців</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-4 bg-white rounded-xl border border-gray-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-undo text-purple-600"></i>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Повернення</p>
                            <p class="text-xs text-gray-500">Протягом 14 днів</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-4 bg-white rounded-xl border border-gray-200">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-headset text-orange-600"></i>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Підтримка</p>
                            <p class="text-xs text-gray-500">Цілодобово</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Details Tabs -->
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <!-- Tabs -->
            <div class="border-b border-gray-200">
                <nav class="flex -mb-px" aria-label="Tabs">
                    <button onclick="switchTab('description')" 
                            id="tab-description"
                            class="tab-button active w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        <i class="fas fa-align-left mr-2"></i>Опис
                    </button>
                    <button onclick="switchTab('specifications')" 
                            id="tab-specifications"
                            class="tab-button w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        <i class="fas fa-list-ul mr-2"></i>Характеристики
                    </button>
                    <button onclick="switchTab('delivery')" 
                            id="tab-delivery"
                            class="tab-button w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        <i class="fas fa-shipping-fast mr-2"></i>Доставка
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6 sm:p-8">
                <!-- Description -->
                <div id="content-description" class="tab-content">
                    <div class="prose prose-emerald max-w-none">
                        {!! $product->description ?? '<p class="text-gray-500 text-center py-8">Опис товару відсутній</p>' !!}
                    </div>
                </div>

                <!-- Specifications -->
                <div id="content-specifications" class="tab-content hidden">
                    @php
                        $hasSpecs = !empty($product->characteristics) && is_array($product->characteristics);
                        $validSpecs = [];
                        if ($hasSpecs) {
                            foreach ($product->characteristics as $key => $value) {
                                if (!empty($value) && $value !== 'Не вказано' && $value !== '-') {
                                    $validSpecs[$key] = $value;
                                }
                            }
                        }
                    @endphp

                    @if(count($validSpecs) > 0)
                        <dl class="divide-y divide-gray-200">
                            @foreach ($validSpecs as $key => $value)
                                <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                    <dt class="text-sm font-medium text-gray-500">
                                        {{ ucwords(str_replace(['_', '-'], ' ', $key)) }}
                                    </dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        @if (is_array($value))
                                            {{ implode(', ', $value) }}
                                        @else
                                            {{ $value }}
                                        @endif
                                    </dd>
                                </div>
                            @endforeach
                        </dl>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-clipboard-list text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Технічні характеристики відсутні</p>
                        </div>
                    @endif
                </div>

                <!-- Delivery -->
                <div id="content-delivery" class="tab-content hidden">
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Delivery Methods -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Способи доставки</h3>
                            <div class="space-y-4">
                                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-truck text-emerald-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Нова Пошта</h4>
                                        <p class="text-sm text-gray-600 mt-1">Доставка у відділення або кур'єром по Україні</p>
                                        <p class="text-sm text-emerald-600 mt-2 font-medium">1-3 робочих дні</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-store text-blue-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Самовивіз</h4>
                                        <p class="text-sm text-gray-600 mt-1">З нашого магазину в Одесі</p>
                                        <p class="text-sm text-blue-600 mt-2 font-medium">Безкоштовно</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Methods -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Способи оплати</h3>
                            <div class="space-y-4">
                                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Готівкою</h4>
                                        <p class="text-sm text-gray-600 mt-1">При отриманні товару в магазині або кур'єру</p>
                                    </div>
                                </div>

                                <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl">
                                    <div class="flex-shrink-0">
                                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-credit-card text-purple-600 text-xl"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Картою онлайн</h4>
                                        <p class="text-sm text-gray-600 mt-1">Безпечна оплата на сайті через Visa/Mastercard</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recommended Products -->
@if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Схожі товари</h2>
            <a href="{{ route('catalog') }}" class="text-emerald-600 hover:text-emerald-700 font-medium flex items-center">
                Всі товари
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
        
        @include('includes.catalog.recomendet')
    </div>
</div>
@endif
@endsection

@section('scripts')
<style>
.tab-button {
    border-color: transparent;
    color: #6b7280;
    transition: all 0.2s;
}

.tab-button:hover {
    color: #10b981;
}

.tab-button.active {
    border-color: #10b981;
    color: #10b981;
}

.prose p {
    margin-bottom: 1rem;
    line-height: 1.75;
}

.prose h2, .prose h3 {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.prose ul, .prose ol {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.prose li {
    margin-bottom: 0.5rem;
}

/* Hide scrollbar but keep functionality */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>

<script>
// Gallery state
let currentImageIndex = 0;
const allImages = @json($allImages ?? []);

function changeImage(src, index) {
    const mainImage = document.getElementById('mainImage');
    
    // Fade effect
    mainImage.style.opacity = '0';
    setTimeout(() => {
        mainImage.src = src;
        mainImage.style.opacity = '1';
    }, 150);
    
    currentImageIndex = index;
    updateImageCounter();
    updateActiveThumbnail(index);
}

function nextImage() {
    if (allImages.length === 0) return;
    
    currentImageIndex = (currentImageIndex + 1) % allImages.length;
    const nextSrc = allImages[currentImageIndex];
    changeImage(nextSrc, currentImageIndex);
}

function prevImage() {
    if (allImages.length === 0) return;
    
    currentImageIndex = (currentImageIndex - 1 + allImages.length) % allImages.length;
    const prevSrc = allImages[currentImageIndex];
    changeImage(prevSrc, currentImageIndex);
}

function updateImageCounter() {
    const counter = document.getElementById('currentImageIndex');
    if (counter) {
        counter.textContent = currentImageIndex + 1;
    }
}

function updateActiveThumbnail(index) {
    // Update active thumbnail
    document.querySelectorAll('.thumbnail-btn').forEach((btn, i) => {
        if (i === index) {
            btn.classList.remove('border-gray-200');
            btn.classList.add('border-emerald-500');
        } else {
            btn.classList.add('border-gray-200');
            btn.classList.remove('border-emerald-500');
        }
    });
    
    // Scroll thumbnail into view
    const activeThumb = document.querySelector(`.thumbnail-btn[data-index="${index}"]`);
    if (activeThumb) {
        activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
    }
}

// Keyboard navigation
document.addEventListener('keydown', function(e) {
    if (e.key === 'ArrowLeft') {
        prevImage();
    } else if (e.key === 'ArrowRight') {
        nextImage();
    }
});

function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active state to selected tab button
    document.getElementById('tab-' + tabName).classList.add('active');
}

function toggleWishlist(productId) {
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    const index = wishlist.indexOf(productId);
    
    const btn = event.currentTarget;
    const icon = btn.querySelector('i');
    
    if (index > -1) {
        wishlist.splice(index, 1);
        icon.classList.remove('fas');
        icon.classList.add('far');
    } else {
        wishlist.push(productId);
        icon.classList.remove('far');
        icon.classList.add('fas');
    }
    
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    window.dispatchEvent(new Event('wishlist-updated'));
}

// Check wishlist state on load
document.addEventListener('DOMContentLoaded', function() {
    const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    const productId = {{ $product->id }};
    
    if (wishlist.includes(productId)) {
        document.querySelectorAll('.fa-heart').forEach(icon => {
            icon.classList.remove('far');
            icon.classList.add('fas');
        });
    }
});
</script>
@endsection
