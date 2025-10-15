<div class="recommended-products bg-white rounded-2xl shadow-sm p-8">
    <!-- Loading Indicator -->
    <div id="loading-indicator" class="text-center py-12">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-2xl mb-4">
            <div class="w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
        </div>
        <p class="text-gray-600 font-medium">Загружаем рекомендуемые товары...</p>
    </div>

    <!-- Products Grid -->
    <div id="products-grid" class="hidden grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        <!-- Products will be loaded via Ajax -->
    </div>

    <!-- Swiper Container (for mobile) -->
    <div id="swiper-container" class="hidden">
        <div class="swiper recommendedSwiper">
            <div class="swiper-wrapper" id="swiper-wrapper">
                <!-- Products will be loaded via Ajax -->
            </div>

            <!-- Navigation -->
            <div class="flex justify-center mt-8 space-x-4">
                <button class="swiper-button-prev w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl flex items-center justify-center hover:from-emerald-600 hover:to-teal-600 transition-all duration-200 shadow-lg">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="swiper-button-next w-12 h-12 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl flex items-center justify-center hover:from-emerald-600 hover:to-teal-600 transition-all duration-200 shadow-lg">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            
            <!-- Pagination -->
            <div class="swiper-pagination mt-6"></div>
        </div>
    </div>

    <!-- Error Message -->
    <div id="error-message" class="hidden text-center py-12">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-2xl mb-4">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
        </div>
        <h4 class="text-lg font-bold text-gray-900 mb-2">Ошибка загрузки</h4>
        <p class="text-gray-600 mb-4">Не удалось загрузить рекомендуемые товары</p>
        <button onclick="loadRecommendedProducts()" class="btn-primary">
            <i class="fas fa-refresh mr-2"></i>
            Попробовать снова
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let recommendedSwiper = null;
    
    // Function to load recommended products
    async function loadRecommendedProducts() {
        try {
            console.log('Loading recommended products...');
            
            // Show loading indicator
            const loadingIndicator = document.getElementById('loading-indicator');
            const productsGrid = document.getElementById('products-grid');
            const swiperContainer = document.getElementById('swiper-container');
            const errorMessage = document.getElementById('error-message');
            
            if (loadingIndicator) loadingIndicator.classList.remove('hidden');
            if (productsGrid) productsGrid.classList.add('hidden');
            if (swiperContainer) swiperContainer.classList.add('hidden');
            if (errorMessage) errorMessage.classList.add('hidden');
            
            const response = await fetch('/api/recommended-products');
            console.log('Response received:', response);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            console.log('Data received:', data);
            
            if (data.success && data.products.length > 0) {
                console.log('Products loaded successfully:', data.products.length);
                
                // Hide loading indicator
                if (loadingIndicator) loadingIndicator.classList.add('hidden');
                
                // Show products
                if (window.innerWidth >= 1024) {
                    // Desktop: Show grid
                    populateProductsGrid(data.products);
                    if (productsGrid) productsGrid.classList.remove('hidden');
                } else {
                    // Mobile: Show swiper
                    populateSwiper(data.products);
                    if (swiperContainer) swiperContainer.classList.remove('hidden');
                    
                    // Initialize Swiper after Vue components are ready
                    setTimeout(() => {
                        initializeSwiper();
                    }, 100);
                }
                
            } else {
                console.log('No products or data error:', data);
                throw new Error(data.message || 'No data');
            }
            
        } catch (error) {
            console.error('Error loading recommended products:', error);
            
            // Hide loading indicator
            if (loadingIndicator) loadingIndicator.classList.add('hidden');
            
            // Show error message
            if (errorMessage) errorMessage.classList.remove('hidden');
        }
    }
    
    // Function to populate products grid (desktop)
    function populateProductsGrid(products) {
        console.log('Populating products grid:', products);
        
        const productsGrid = document.getElementById('products-grid');
        if (!productsGrid) {
            console.error('Products grid element not found!');
            return;
        }
        
        productsGrid.innerHTML = '';
        
        products.forEach((product, index) => {
            const productCard = createProductCard(product, index);
            productsGrid.appendChild(productCard);
        });
        
        console.log(`Created ${products.length} product cards for grid`);
    }
    
    // Function to populate swiper (mobile)
    function populateSwiper(products) {
        console.log('Populating swiper:', products);
        
        const swiperWrapper = document.getElementById('swiper-wrapper');
        if (!swiperWrapper) {
            console.error('Swiper wrapper element not found!');
            return;
        }
        
        swiperWrapper.innerHTML = '';
        
        products.forEach((product, index) => {
            const slide = document.createElement('div');
            slide.className = 'swiper-slide';
            
            const productCard = createProductCard(product, index);
            slide.appendChild(productCard);
            
            swiperWrapper.appendChild(slide);
        });
        
        console.log(`Created ${products.length} slides for swiper`);
    }
    
    // Function to create product card
    function createProductCard(product, index) {
        const card = document.createElement('div');
        card.className = 'product-card bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden';
        card.style.animationDelay = `${index * 100}ms`;
        
        const finalPrice = product.discount > 0 
            ? product.price * (1 - product.discount / 100) 
            : product.price;
        
        card.innerHTML = `
            <div class="relative overflow-hidden">
                <!-- Product Image -->
                <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200">
                    <img src="${escapeUrl(product.image_path) || 'https://via.placeholder.com/300x300/f3f4f6/9ca3af?text=Нет+фото'}" 
                         alt="${escapeHtml(product.name)}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>
                
                <!-- Discount Badge -->
                ${product.discount > 0 ? `
                    <div class="absolute top-3 left-3">
                        <span class="badge-danger">-${product.discount}%</span>
                    </div>
                ` : ''}
                
                <!-- Wholesale Badge -->
                ${product.is_wholesale && product.wholesale_price ? `
                    <div class="absolute top-3 right-3">
                        <span class="badge-warning">
                            <i class="fas fa-boxes mr-1"></i>Опт
                        </span>
                    </div>
                ` : ''}
                
                <!-- Wishlist Button -->
                <button class="absolute top-3 right-3 w-8 h-8 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-sm transition-all hover:scale-110" 
                        onclick="toggleWishlist(${product.id})">
                    <i class="fas fa-heart text-gray-400 hover:text-red-500 transition-colors"></i>
                </button>
            </div>
            
            <!-- Product Info -->
            <div class="p-4">
                <h4 class="font-bold text-gray-900 mb-2 line-clamp-2 text-sm">
                    ${escapeHtml(product.name)}
                </h4>
                
                <!-- Price -->
                <div class="flex items-center gap-2 mb-3">
                    <span class="text-lg font-bold text-emerald-600">
                        ${Math.round(finalPrice).toLocaleString()} ₴
                    </span>
                    ${product.discount > 0 ? `
                        <span class="text-sm text-gray-500 line-through">
                            ${Math.round(product.price).toLocaleString()} ₴
                        </span>
                    ` : ''}
                </div>
                
                <!-- Wholesale Price -->
                ${product.is_wholesale && product.wholesale_price && product.wholesale_min_quantity ? `
                    <div class="flex items-center text-xs text-teal-600 mb-3">
                        <i class="fas fa-boxes mr-1"></i>
                        Опт от ${product.wholesale_min_quantity} шт: ${Math.round(product.wholesale_price).toLocaleString()} ₴
                    </div>
                ` : ''}
                
                <!-- Actions -->
                <div class="flex gap-2">
                    <a href="/catalog/${escapeUrl(product.url)}" 
                       class="flex-1 btn-primary text-center py-2 text-sm">
                        <i class="fas fa-eye mr-1"></i>Подробнее
                    </a>
                    <button class="btn-secondary px-3 py-2" 
                            onclick="addToCart(${product.id}, '${escapeHtml(product.name)}', ${finalPrice}, '${escapeUrl(product.image_path) || ''}', '${escapeHtml(product.articule || 'Не указан')}', ${product.availability || 1}${product.is_wholesale ? `, true, ${product.wholesale_price || 0}, ${product.wholesale_min_quantity || 0}` : ''})">
                        <i class="fas fa-shopping-cart"></i>
                    </button>
                </div>
            </div>
        `;
        
        return card;
    }
    
    // Function to initialize Swiper
    function initializeSwiper() {
        console.log('Initializing Swiper...');
        
        const swiperElement = document.querySelector('.recommendedSwiper');
        if (!swiperElement) {
            console.error('Swiper element not found!');
            return;
        }
        
        if (recommendedSwiper) {
            console.log('Destroying existing Swiper...');
            recommendedSwiper.destroy(true, true);
            recommendedSwiper = null;
        }
        
        try {
            recommendedSwiper = new Swiper('.recommendedSwiper', {
                slidesPerView: 1,
                spaceBetween: 16,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },
                breakpoints: {
                    576: {
                        slidesPerView: 2,
                        spaceBetween: 16,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    }
                },
                grabCursor: true,
                keyboard: {
                    enabled: true,
                    onlyInViewport: true,
                },
                mousewheel: {
                    forceToAxis: true,
                },
                effect: 'slide',
                speed: 600,
            });
            
            console.log('Swiper initialized successfully');
            
        } catch (error) {
            console.error('Error initializing Swiper:', error);
        }
    }
    
    // Helper functions
    function escapeHtml(text) {
        if (!text) return '';
        
        return text.toString()
            .replace(/<[^>]*>/g, '')
            .replace(/[^\w\sа-яёіїєА-ЯЁІЇЄ]/g, '')
            .replace(/\s+/g, ' ')
            .trim() || 'Без названия';
    }
    
    function escapeUrl(text) {
        if (!text) return '';
        
        return text.toString()
            .replace(/<[^>]*>/g, '')
            .replace(/[^\w\-\.\/\:]/g, '')
            .trim() || '';
    }
    
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
    
    // Load products on page load
    loadRecommendedProducts();
    
    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            if (recommendedSwiper && window.innerWidth >= 1024) {
                // Destroy swiper on desktop
                recommendedSwiper.destroy(true, true);
                recommendedSwiper = null;
            }
        }, 250);
    });
    
    // Refresh products every 30 minutes
    setInterval(loadRecommendedProducts, 30 * 60 * 1000);
});
</script>