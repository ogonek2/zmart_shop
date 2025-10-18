<div class="recommended-products bg-white rounded-2xl shadow-sm p-8">
    @if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            @foreach($recommendedProducts as $product)
                @php
                    $finalPrice = $product->discount > 0 
                        ? $product->price * (1 - $product->discount / 100) 
                        : $product->price;
                @endphp
                
                <div class="product-card bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
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
    @else
        <!-- No Products Message -->
        <div class="text-center py-12">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-2xl mb-4">
                <i class="fas fa-box-open text-3xl text-gray-400"></i>
            </div>
            <h4 class="text-lg font-bold text-gray-900 mb-2">Нет похожих товаров</h4>
            <p class="text-gray-600 mb-4">В данной категории пока нет других товаров</p>
            <a href="{{ route('catalog') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Перейти в каталог
            </a>
        </div>
    @endif
</div>

<script>
// Global functions for product interactions
window.toggleWishlist = function(productId) {
    // Implement wishlist toggle
    console.log('Toggle wishlist for product:', productId);
};

window.addToCart = function(productId, name, price, image, articule, availability, isWholesale = false, wholesalePrice = 0, wholesaleMinQuantity = 0) {
    // Implement add to cart
    console.log('Add to cart:', {productId, name, price, image, articule, availability, isWholesale, wholesalePrice, wholesaleMinQuantity});
    
    // Trigger Vue cart component if available
    if (window.Vue && window.Vue.globalProperties.$cart) {
        window.Vue.globalProperties.$cart.addItem({
            id: productId,
            name: name,
            price: price,
            image: image,
            articule: articule,
            quantity: 1,
            availability: availability,
            isWholesale: isWholesale,
            wholesalePrice: wholesalePrice,
            wholesaleMinQuantity: wholesaleMinQuantity
        });
    }
};
</script>