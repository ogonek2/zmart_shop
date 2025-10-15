@extends('layouts.app')

@section('seo')
    <title>{{ $product->name }} - ZMART</title>
    <meta name="description" content="{{ $product->description ? strip_tags(mb_substr($product->description, 0, 160)) : $product->name }}">
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
            @if ($product->categories->first())
            <a href="{{ route('catalog_category_page', $product->categories->first()->url) }}" 
               class="text-gray-600 hover:text-emerald-600 transition-colors">
                {{ $product->categories->first()->name }}
            </a>
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
            @endif
            <span class="text-gray-900 font-medium">{{ $product->name }}</span>
        </nav>
    </div>
</section>

<!-- Product Content -->
<section class="bg-white py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Gallery -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl overflow-hidden shadow-lg">
                    @if ($product->image_path)
                        <img src="{{ $product->image_path }}" 
                             alt="{{ $product->name }}" 
                             id="mainImage"
                             class="w-full h-full object-contain p-8"
                             onerror="this.src='https://via.placeholder.com/600x600/f3f4f6/9ca3af?text=Фото+відсутнє'">
                    @else
                        <img src="https://via.placeholder.com/600x600/f3f4f6/9ca3af?text=Фото+відсутнє" 
                             alt="{{ $product->name }}" 
                             id="mainImage"
                             class="w-full h-full object-contain p-8">
                    @endif
                </div>

                <!-- Thumbnails -->
                @if ($images && $images->count() > 0)
                <div class="grid grid-cols-5 gap-3">
                    @if ($product->image_path)
                        <button onclick="changeMainImage('{{ $product->image_path }}')" 
                                class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl overflow-hidden border-2 border-emerald-500 hover:border-emerald-600 transition-all">
                            <img src="{{ $product->image_path }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        </button>
                    @endif
                    @foreach ($images as $image)
                        <button onclick="changeMainImage('{{ $image->src }}')" 
                                class="aspect-square bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl overflow-hidden border-2 border-gray-200 hover:border-emerald-500 transition-all">
                            <img src="{{ $image->src }}" 
                                 alt="{{ $product->name }}"
                                 class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Title & Article -->
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">{{ $product->name }}</h1>
                    <p class="text-gray-600">Артикул: <span class="font-medium">{{ $product->articule ?? 'Не вказано' }}</span></p>
                    @if ($product->availability === 2)
                        <div class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-xl mt-3">
                            <i class="fas fa-times-circle mr-2"></i>
                            Немає в наявності
                        </div>
                    @endif
                </div>

                <!-- Price -->
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-6">
                    @if ($product->discount > 0)
                        <div class="inline-flex items-center px-3 py-1 bg-red-500 text-white rounded-full text-sm font-bold mb-3">
                            <i class="fas fa-tag mr-1"></i>-{{ $product->discount }}%
                        </div>
                    @endif
                    <div class="flex items-baseline gap-4">
                        <span class="text-4xl font-bold text-emerald-600">{{ number_format($product->price, 0, ',', ' ') }} ₴</span>
                        @if ($product->discount > 0)
                            <span class="text-2xl text-gray-500 line-through">
                                {{ number_format($product->price * (1 + $product->discount / 100), 0, ',', ' ') }} ₴
                            </span>
                        @endif
                    </div>
                    
                    @if($product->is_wholesale && $product->wholesale_price > 0 && $product->wholesale_min_quantity)
                        <div class="mt-4 pt-4 border-t border-emerald-200">
                            <div class="flex items-center gap-2 mb-2">
                                <i class="fas fa-boxes text-teal-600"></i>
                                <span class="text-gray-700 font-medium">Оптова ціна:</span>
                                <span class="text-2xl font-bold text-teal-600">{{ number_format($product->wholesale_price, 0, ',', ' ') }} ₴</span>
                            </div>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-1"></i>
                                Доступна при замовленні від {{ $product->wholesale_min_quantity }} {{ $product->wholesale_min_quantity == 1 ? 'одиниці' : 'одиниць' }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
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
                        @if($product->is_wholesale && $product->wholesale_price && $product->wholesale_min_quantity)
                        is-wholesale="true"
                        wholesale-price="{{ $product->wholesale_price }}"
                        wholesale-min-quantity="{{ $product->wholesale_min_quantity }}"
                        @endif
                    ></cart-button>
                    <button onclick="toggleWishlist({{ $product->id }})" 
                            class="w-14 h-14 bg-gray-100 hover:bg-red-100 text-gray-600 hover:text-red-600 rounded-xl transition-all flex items-center justify-center">
                        <i class="fas fa-heart text-xl"></i>
                    </button>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shipping-fast text-emerald-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">Швидка доставка</div>
                            <div class="text-xs text-gray-600">По всій Україні</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-shield-alt text-blue-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">Гарантія</div>
                            <div class="text-xs text-gray-600">12 місяців</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-undo text-purple-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">Повернення</div>
                            <div class="text-xs text-gray-600">14 днів</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-headset text-orange-600"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-sm">Підтримка</div>
                            <div class="text-xs text-gray-600">24/7</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tabs -->
<section class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" role="tablist">
                    <button class="py-4 px-2 border-b-2 border-emerald-500 text-emerald-600 font-medium" 
                            onclick="showTab('description')" id="tab-description">
                        <i class="fas fa-info-circle mr-2"></i>Опис
                    </button>
                    <button class="py-4 px-2 border-b-2 border-transparent text-gray-600 hover:text-gray-900 font-medium" 
                            onclick="showTab('specs')" id="tab-specs">
                        <i class="fas fa-list mr-2"></i>Характеристики
                    </button>
                    <button class="py-4 px-2 border-b-2 border-transparent text-gray-600 hover:text-gray-900 font-medium" 
                            onclick="showTab('delivery')" id="tab-delivery">
                        <i class="fas fa-truck mr-2"></i>Доставка
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-8">
                <!-- Description -->
                <div id="content-description" class="tab-content">
                    <div class="prose max-w-none">
                        {!! $product->description !!}
                    </div>
                </div>

                <!-- Specifications -->
                <div id="content-specs" class="tab-content hidden">
                    @php
                        $hasCharacteristics = false;
                        if (!empty($product->characteristics) && is_array($product->characteristics)) {
                            foreach ($product->characteristics as $key => $value) {
                                if (!empty($value) && $value !== 'Не вказано' && $value !== '-') {
                                    $hasCharacteristics = true;
                                    break;
                                }
                            }
                        }
                    @endphp

                    @if($hasCharacteristics)
                        <div class="grid gap-3">
                            @foreach ($product->characteristics as $key => $value)
                                @if (!empty($value) && $value !== 'Не вказано' && $value !== '-')
                                    <div class="flex justify-between py-3 px-4 bg-gray-50 rounded-lg">
                                        <span class="font-medium text-gray-700">{{ ucwords(str_replace(['_', '-'], ' ', $key)) }}</span>
                                        <span class="text-gray-900">
                                            @if (is_array($value))
                                                {{ implode(', ', $value) }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-info-circle text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-600">Технічні характеристики відсутні</p>
                        </div>
                    @endif
                </div>

                <!-- Delivery -->
                <div id="content-delivery" class="tab-content hidden">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Способи доставки</h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-truck text-emerald-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Нова Пошта</div>
                                        <p class="text-sm text-gray-600">Доставка в відділення або кур'єром</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-box text-blue-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Самовивіз</div>
                                        <p class="text-sm text-gray-600">З нашого магазину в Одесі</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Способи оплати</h3>
                            <div class="space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-money-bill-wave text-green-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Готівкою</div>
                                        <p class="text-sm text-gray-600">При отриманні товару</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-credit-card text-purple-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">Картою</div>
                                        <p class="text-sm text-gray-600">Онлайн оплата на сайті</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recommended Products -->
@if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
<section class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Схожі товари</h2>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @include('includes.catalog.recomendet')
        </div>
    </div>
</section>
@endif
@endsection

@section('scripts')
<script>
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
    
    // Update active thumbnail
    document.querySelectorAll('.grid button').forEach(btn => {
        btn.classList.remove('border-emerald-500');
        btn.classList.add('border-gray-200');
    });
    event.currentTarget.classList.remove('border-gray-200');
    event.currentTarget.classList.add('border-emerald-500');
}

function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('[id^="tab-"]').forEach(tab => {
        tab.classList.remove('border-emerald-500', 'text-emerald-600');
        tab.classList.add('border-transparent', 'text-gray-600');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active state to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.add('border-emerald-500', 'text-emerald-600');
    activeTab.classList.remove('border-transparent', 'text-gray-600');
}

function toggleWishlist(productId) {
    let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
    const index = wishlist.indexOf(productId);
    
    if (index > -1) {
        wishlist.splice(index, 1);
        console.log('Removed from wishlist');
    } else {
        wishlist.push(productId);
        console.log('Added to wishlist');
    }
    
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
    window.dispatchEvent(new Event('wishlist-updated'));
}
</script>
@endsection
