<header class="bg-white shadow-sm sticky top-0 z-50">
    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white py-2 hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center text-sm">
                <!-- Left side -->
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-map-marker-alt text-yellow-300"></i>
                        <span>Одесса</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-phone text-yellow-300"></i>
                        <span>+38 073-077-75-72</span>
                    </div>
                </div>
                
                <!-- Right side -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('oplata_i_dostavka') }}" class="hover:text-yellow-300 transition-colors">
                        <i class="fas fa-truck mr-1"></i>Доставка
                    </a>
                    <a href="{{ route('obmin_ta_povernennia') }}" class="hover:text-yellow-300 transition-colors">
                        <i class="fas fa-shield-alt mr-1"></i>Гарантия
                    </a>
                    <a href="{{ route('kontaktna_informatsiia') }}" class="hover:text-yellow-300 transition-colors">
                        <i class="fas fa-headset mr-1"></i>Поддержка
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform shadow-lg">
                        <i class="fas fa-bolt text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold text-gray-900">ZMART</span>
                </a>

                <!-- Desktop Search -->
                <div class="hidden lg:block flex-1 max-w-2xl mx-8">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <search></search>
                    </div>
                </div>

                <!-- Right side navigation -->
                <div class="flex items-center space-x-4">
                    <!-- Wishlist -->
                    <div class="relative">
                        <wishlist-button></wishlist-button>
                    </div>

                    <!-- Cart -->
                    <div class="relative">
                        <open-cart-button></open-cart-button>
                    </div>

                    <!-- Mobile menu button -->
                    <button class="lg:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors" 
                            onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Categories Navigation -->
    <nav class="bg-gradient-to-r from-gray-900 to-gray-800 text-white hidden lg:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center">
                <!-- Catalog Button -->
                <button onclick="toggleCatalogMenu()" 
                        class="flex items-center space-x-2 px-6 py-4 hover:bg-gray-700 transition-colors group">
                    <i class="fas fa-th-large"></i>
                    <span class="font-medium">Каталог</span>
                    <i class="fas fa-chevron-down group-hover:rotate-180 transition-transform"></i>
                </button>

                <!-- Navigation Links -->
                <div class="flex items-center space-x-8 ml-8">
                    <a href="{{ route('pro_kompaniiu') }}" 
                       class="flex items-center space-x-2 py-4 hover:bg-gray-700 transition-colors">
                        <i class="fas fa-info-circle"></i>
                        <span class="font-medium">О нас</span>
                    </a>
                </div>

                <!-- Right side -->
                <div class="ml-auto">
                    <a href="{{ route('kontaktna_informatsiia') }}" 
                       class="flex items-center space-x-2 px-6 py-4 hover:bg-gray-700 transition-colors">
                        <i class="fas fa-phone"></i>
                        <span class="font-medium">Заказать звонок</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Mobile Menu Overlay -->
<div id="mobileMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden lg:hidden" onclick="closeMobileMenu()">
</div>

<!-- Mobile Menu -->
<div id="mobileMenu" class="fixed top-0 right-0 h-full w-80 bg-white shadow-2xl z-50 transform translate-x-full transition-transform lg:hidden">
    <!-- Mobile Menu Header -->
    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white p-4 flex items-center justify-between">
        <h3 class="text-lg font-bold">Меню</h3>
        <button onclick="closeMobileMenu()" class="text-white hover:text-yellow-300">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Mobile Search -->
    <div class="p-4 border-b border-gray-200">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <search></search>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div class="p-4 space-y-2">
        <!-- Catalog -->
        <div class="border-b border-gray-200 pb-4">
            <button onclick="toggleMobileCatalog()" 
                    class="flex items-center justify-between w-full p-3 text-left hover:bg-gray-50 rounded-lg transition-colors">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-th-large text-emerald-600"></i>
                    <span class="font-medium text-gray-900">Каталог</span>
                </div>
                <i class="fas fa-chevron-down transition-transform" id="mobileCatalogIcon"></i>
            </button>
            
            <!-- Mobile Catalog Items -->
            <div id="mobileCatalogItems" class="hidden mt-2 space-y-1">
                @foreach (get_all_category()->whereNull('parent_id') as $category)
                <a href="{{ route('catalog_category_page', $category->url) }}" 
                   class="flex items-center justify-between p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-folder text-gray-400"></i>
                        <span>{{ $category->name }}</span>
                    </div>
                    <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-xs">{{ $category->products()->count() }}</span>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Other Links -->
        <a href="{{ route('pro_kompaniiu') }}" 
           class="flex items-center space-x-3 p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
            <i class="fas fa-info-circle text-emerald-600"></i>
            <span class="font-medium">О нас</span>
        </a>

        <a href="{{ route('kontaktna_informatsiia') }}" 
           class="flex items-center space-x-3 p-3 text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
            <i class="fas fa-phone text-emerald-600"></i>
            <span class="font-medium">Заказать звонок</span>
        </a>
    </div>

    <!-- Mobile Footer Links -->
    <div class="p-4 border-t border-gray-200">
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('oplata_i_dostavka') }}" 
               class="flex flex-col items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-truck text-emerald-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Доставка</span>
            </a>
            <a href="{{ route('obmin_ta_povernennia') }}" 
               class="flex flex-col items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-shield-alt text-emerald-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Гарантия</span>
            </a>
        </div>
    </div>
</div>

<!-- Desktop Catalog Menu -->
<div id="catalogMenu" class="fixed top-0 left-0 h-full w-96 bg-white shadow-2xl z-50 transform -translate-x-full transition-transform hidden lg:block">
    <!-- Catalog Menu Header -->
    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white p-4 flex items-center justify-between">
        <h3 class="text-lg font-bold">Каталог товаров</h3>
        <button onclick="closeCatalogMenu()" class="text-white hover:text-yellow-300">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    <!-- Catalog Items -->
    <div class="flex-1 overflow-y-auto max-h-96">
        <div id="catalogList" class="p-2">
            @foreach (get_all_category()->whereNull('parent_id') as $category)
            <a href="{{ route('catalog_category_page', $category->url) }}" 
               class="flex items-center justify-between p-3 text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg transition-all duration-200 hover:translate-x-1">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-folder text-emerald-600"></i>
                    <span class="font-medium">{{ $category->name }}</span>
                </div>
                <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full text-xs">{{ $category->products()->count() }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Catalog Menu Footer -->
    <div class="p-4 border-t border-gray-200">
        <div class="grid grid-cols-2 gap-3">
            <a href="/catalog?promo=1" 
               class="flex flex-col items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-tag text-emerald-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Акции</span>
            </a>
            <a href="/catalog?new=1" 
               class="flex flex-col items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                <i class="fas fa-star text-emerald-600 mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Новинки</span>
            </a>
        </div>
    </div>
</div>

<!-- Catalog Menu Overlay -->
<div id="catalogMenuOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" onclick="closeCatalogMenu()">
</div>

<!-- Mini Cart -->
<mini-cart></mini-cart>

<!-- Wishlist Panel -->
<wishlist-panel></wishlist-panel>