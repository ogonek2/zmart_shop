@extends('layouts.app')

@section('styles')
    <style>
        .category-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
        }

        .sort-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .sort-select {
            padding: 0.5rem 1rem;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            background: white;
            color: var(--dark-color);
            font-size: 0.9rem;
            cursor: pointer;
        }

        .sort-select:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .view-toggle {
            display: flex;
            gap: 0.5rem;
        }

        .view-btn {
            padding: 0.5rem;
            border: 2px solid var(--border-color);
            background: white;
            color: var(--gray-color);
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .view-btn:hover,
        .view-btn.active {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: rgba(37, 99, 235, 0.1);
        }

        /* Современная сетка товаров - компактнее */
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        /* Современная карточка товара - компактная и минималистичная */
        .product-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #f1f5f9;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border-color: #e2e8f0;
        }

        /* Изображение - компактнее */
        .product-image-container {
            position: relative;
            overflow: hidden;
            background: #f8fafc;
        }

        .product-image {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover .product-image {
            transform: scale(1.08);
        }

        .no-image-placeholder {
            width: 100%;
            aspect-ratio: 1 / 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            color: #94a3b8;
        }

        /* Компактные бейджи */
        .discount-badge {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: #ef4444;
            color: white;
            padding: 0.35rem 0.6rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8rem;
            z-index: 2;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .availability-badge {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            background: #f59e0b;
            color: white;
            padding: 0.35rem 0.6rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.8rem;
            z-index: 2;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
        }

        /* Компактная кнопка избранного */
        .wishlist-btn {
            position: absolute;
            top: 0.75rem;
            left: 0.75rem;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border: none;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .product-card:has(.availability-badge) .wishlist-btn {
            left: 7rem;
        }

        .wishlist-btn:hover {
            background: white;
            color: #ef4444;
            transform: scale(1.15);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Компактная информация о товаре */
        .product-info {
            padding: 1rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .product-title {
            font-weight: 600;
            color: #1e293b;
            line-height: 1.3;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-size: 0.9rem;
            min-height: 2.6em;
        }

        .product-title a {
            color: inherit;
            text-decoration: none;
            transition: color 0.2s;
        }

        .product-title a:hover {
            color: #2563eb;
        }

        /* Компактная цена */
        .product-price {
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
            margin-top: auto;
        }

        .price-current {
            font-size: 1.15rem;
            font-weight: 700;
            color: #10b981;
        }

        .price-old {
            font-size: 0.85rem;
            color: #94a3b8;
            text-decoration: line-through;
        }

        .product-actions {
            margin-top: 0.75rem;
        }

        .pagination-wrapper {
            margin-top: 3rem;
            display: flex;
            justify-content: center;
        }

        .pagination .page-link {
            border: none;
            color: var(--primary-color);
            padding: 0.75rem 1rem;
            margin: 0 0.25rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .pagination .page-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            color: white;
        }

        /* Стили для подкатегорий (как в карусели) */
        .subcategories-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: flex-start;
        }

        .subcategory-carousel-item {
            flex: 0 0 auto;
            width: 110px;
            text-align: center;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }
        
        .subcategory-carousel-item:hover {
            transform: translateY(-5px);
        }
        
        .subcategory-carousel-item:hover .subcategory-image {
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
            border-color: var(--primary-color);
        }
        
        .subcategory-image-wrapper {
            width: 90px;
            height: 90px;
            margin: 0 auto 0.75rem;
            position: relative;
        }
        
        .subcategory-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #e5e7eb;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .subcategory-icon-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 3px solid #e5e7eb;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            color: #94a3b8;
            font-size: 2rem;
            transition: all 0.3s ease;
        }

        .subcategory-carousel-item:hover .subcategory-icon-placeholder {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: var(--primary-color);
            border-color: var(--primary-color);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }
        
        .subcategory-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
            line-height: 1.2;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .subcategory-count {
            font-size: 0.75rem;
            color: #6b7280;
            font-weight: 500;
        }

        /* Улучшенная адаптивность */
        @media (max-width: 992px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 0.75rem;
            }
            
            .sort-section {
                flex-direction: column;
                align-items: stretch;
                padding: 0 0.5rem;
            }

            .category-header {
                padding: 2rem 0;
            }

            .category-header h1 {
                font-size: 2rem;
            }

            .category-header .lead {
                font-size: 1rem;
            }

            .product-info {
                padding: 0.85rem;
            }

            .product-title {
                font-size: 0.85rem;
            }

            .price-current {
                font-size: 1.05rem;
            }

            .subcategories-grid {
                gap: 1.5rem;
            }

            .subcategory-carousel-item {
                width: 90px;
            }

            .subcategory-image-wrapper {
                width: 75px;
                height: 75px;
            }

            .subcategory-name {
                font-size: 0.8rem;
            }

            .subcategory-count {
                font-size: 0.7rem;
            }

            .subcategory-icon-placeholder {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(145px, 1fr));
                gap: 0.6rem;
            }

            .category-header {
                padding: 1.5rem 0;
            }

            .category-header h1 {
                font-size: 1.75rem;
            }

            .sort-section {
                padding: 0 0.25rem;
            }

            .sort-select {
                font-size: 0.85rem;
                padding: 0.4rem 0.8rem;
            }

            .view-btn {
                padding: 0.4rem;
                font-size: 0.85rem;
            }

            .product-info {
                padding: 0.75rem;
            }

            .product-title {
                font-size: 0.8rem;
                min-height: 2.4em;
            }

            .price-current {
                font-size: 1rem;
            }

            .price-old {
                font-size: 0.75rem;
            }

            .discount-badge,
            .availability-badge {
                padding: 0.3rem 0.5rem;
                font-size: 0.7rem;
            }

            .wishlist-btn {
                width: 30px;
                height: 30px;
                font-size: 0.85rem;
            }

            .subcategories-grid {
                gap: 1rem;
                justify-content: center;
            }

            .subcategory-carousel-item {
                width: 80px;
            }

            .subcategory-image-wrapper {
                width: 65px;
                height: 65px;
            }

            .subcategory-name {
                font-size: 0.75rem;
            }

            .subcategory-count {
                font-size: 0.7rem;
            }

            .subcategory-icon-placeholder {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .category-header h1 {
                font-size: 1.5rem;
            }

            .category-header .lead {
                font-size: 0.9rem;
            }
        }
    </style>
@endsection

@section('seo')
    <title>{{ $category->name }} - Каталог товаров ZMART</title>
@endsection

@section('content')
    <!-- Заголовок категории -->
    <section class="category-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-5 fw-bold mb-3">{{ $category->name }}</h1>
                    <p class="lead mb-0">Найдите идеальную технику для вашего дома</p>
                </div>
                <div class="col-lg-4 text-end">
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <div class="text-center">
                            <div class="h3 fw-bold mb-0">{{ $products->total() }}</div>
                            <small class="opacity-75">товаров</small>
                        </div>
                        <div class="text-center">
                            <div class="h3 fw-bold mb-0">{{ $products->count() }}</div>
                            <small class="opacity-75">на странице</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Подкатегории (если есть) -->
    @if($subcategories->count() > 0)
    <section class="subcategories-section py-4" style="background: #f8f9fa;">
        <div class="container">
            <h3 class="mb-4 fw-bold">
                <i class="fas fa-layer-group me-2 text-primary"></i>Подкатегории
            </h3>
            <div class="subcategories-grid">
                @foreach($subcategories as $subcategory)
                    @php
                        $productCount = $subcategory->products()->count();
                        // Получаем изображение из последнего товара подкатегории
                        $latestProduct = $subcategory->products()->latest()->first();
                        $hasProductImage = $latestProduct && $latestProduct->image_path;
                        $subcategoryImage = $hasProductImage ? $latestProduct->image_path : null;
                    @endphp
                    <a href="{{ route('catalog_category_page', $subcategory->url) }}" 
                       class="subcategory-carousel-item">
                        <div class="subcategory-image-wrapper">
                            @if($subcategoryImage)
                                <img src="{{ $subcategoryImage }}" 
                                     alt="{{ $subcategory->name }}" 
                                     class="subcategory-image"
                                     loading="lazy">
                            @else
                                <div class="subcategory-icon-placeholder">
                                    <i class="fas fa-folder-open"></i>
                                </div>
                            @endif
                        </div>
                        <div class="subcategory-name">{{ $subcategory->name }}</div>
                        @if($productCount > 0)
                            <div class="subcategory-count">{{ $productCount }} товаров</div>
                        @else
                            <div class="subcategory-count text-muted">Пусто</div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="py-4">
        <div class="container">
            <div class="row g-4">
                <!-- Боковая панель -->
                <div class="col-lg-3">
                    <!-- Мобильная кнопка фильтров -->
                    <button class="btn btn-primary w-100 d-lg-none mobile-filters-btn mb-3" 
                            type="button" data-bs-toggle="offcanvas" data-bs-target="#filtersOffcanvas">
                        <i class="fas fa-th-large me-2"></i>Категории
                    </button>

                    <!-- Десктопная боковая панель -->
                    <div class="d-none d-lg-block">
                        @include('includes.catalog.categories-sidebar', ['currentCategory' => $category])
                    </div>
                </div>

                <!-- Основной контент -->
                <div class="col-lg-9">
                    <!-- Сортировка и вид -->
                    <div class="sort-section">
                        <div class="d-flex align-items-center gap-3">
                            <label for="sort-select" class="fw-semibold">Сортировать:</label>
                            <select class="sort-select" id="sort-select">
                                <option value="popular">По популярности</option>
                                <option value="price-asc">По цене (возрастание)</option>
                                <option value="price-desc">По цене (убывание)</option>
                                <option value="name-asc">По названию (А-Я)</option>
                                <option value="name-desc">По названию (Я-А)</option>
                                <option value="new">По новизне</option>
                            </select>
                        </div>

                        <div class="view-toggle">
                            <button class="view-btn active" data-view="grid" title="Вид сеткой">
                                <i class="fas fa-th"></i>
                            </button>
                            <button class="view-btn" data-view="list" title="Вид списком">
                                <i class="fas fa-list"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Сетка товаров -->
                    <div class="products-grid">
                        @foreach($products->items() as $product)
                            @php
                                $finalPrice = $product->discount > 0 
                                    ? $product->price - ($product->price * $product->discount / 100) 
                                    : $product->price;
                            @endphp
                            <div class="product-card" 
                                 data-id="{{ $product->id }}"
                                 data-name="{{ $product->name }}"
                                 data-price="{{ $finalPrice }}">
                                <!-- Бейдж скидки -->
                                @if($product->discount > 0)
                                    <div class="discount-badge">
                                        <i class="fas fa-tag me-1"></i>-{{ $product->discount }}%
                                    </div>
                                @endif
                                
                                <!-- Индикатор наличия -->
                                @if($product->availability == 2)
                                    <div class="availability-badge">
                                        <i class="fas fa-times-circle me-1"></i>Нет в наличии
                                    </div>
                                @endif
                                
                                <!-- Кнопка избранного -->
                                <button class="wishlist-btn" title="Добавить в избранное" 
                                        onclick="toggleWishlist({{ $product->id }}, '{{ $product->name }}', {{ $finalPrice }}, '{{ $product->image_path ?? '' }}', {{ $product->discount ?? 0 }})">
                                    <i class="far fa-heart"></i>
                                </button>

                                <!-- Изображение товара -->
                                <div class="product-image-container">
                                    <a href="{{ route('catalog_product_page', $product->url) }}" 
                                       class="product-link" 
                                       title="{{ $product->name }}">
                                        @if($product->image_path)
                                            <img src="{{ $product->image_path }}" 
                                                 alt="{{ $product->name }}" 
                                                 loading="lazy" 
                                                 class="product-image">
                                        @else
                                            <div class="no-image-placeholder">
                                                <i class="fas fa-image fa-2x text-muted"></i>
                                                <small class="text-muted d-block mt-2">Нет фото</small>
                                            </div>
                                        @endif
                                    </a>
                                </div>

                                <!-- Информация о товаре -->
                                <div class="product-info">
                                    <h5 class="product-title">
                                        <a href="{{ route('catalog_product_page', $product->url) }}" 
                                           class="text-decoration-none">
                                            {{ $product->name }}
                                        </a>
                                    </h5>

                                    <!-- Цена -->
                                    <div class="product-price mb-3">
                                        <span class="price-current">{{ number_format($finalPrice, 0, ',', ' ') }} ₴</span>
                                        @if($product->discount > 0)
                                            <span class="price-old">{{ number_format($product->price, 0, ',', ' ') }} ₴</span>
                                        @endif
                                    </div>

                                    <!-- Кнопка корзины -->
                                    <div class="product-actions">
                                        <cart-button 
                                            data-product-id="{{ $product->id }}" 
                                            data-product-name="{{ $product->name }}"
                                            data-product-price="{{ $finalPrice }}"
                                            data-product-image="{{ $product->image_path ?? '' }}"
                                            data-product-articule="{{ $product->articule ?? 'Не указан' }}"
                                            @if($product->is_wholesale && $product->wholesale_price && $product->wholesale_min_quantity)
                                            data-product-is-wholesale="true"
                                            data-product-wholesale-price="{{ $product->wholesale_price }}"
                                            data-product-wholesale-min-quantity="{{ $product->wholesale_min_quantity }}"
                                            @endif
                                            :id="{{ $product->id }}" 
                                            name="{{ $product->name }}"
                                            :price="{{ $finalPrice }}"
                                            image="{{ $product->image_path ?? '' }}"
                                            articule="{{ $product->articule ?? 'Не указан' }}"
                                            :availability="{{ $product->availability ?? 1 }}"
                                            @if($product->is_wholesale && $product->wholesale_price && $product->wholesale_min_quantity)
                                            is-wholesale="true"
                                            :wholesale-price="{{ $product->wholesale_price }}"
                                            :wholesale-min-quantity="{{ $product->wholesale_min_quantity }}"
                                            @endif
                                            >
                                        </cart-button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Пагинация -->
                    <div class="pagination-wrapper">
                        {!! $products->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Мобильная боковая панель -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="filtersOffcanvas" 
         aria-labelledby="filtersOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filtersOffcanvasLabel">Категории</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            @include('includes.catalog.categories-sidebar', ['currentCategory' => $category])
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Функция работы с избранным
        function toggleWishlist(productId, productName, price, image, discount) {
            let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
            const index = wishlist.findIndex(item => item.id === productId);
            const button = event.currentTarget;
            const icon = button.querySelector('i');
            
            if (index > -1) {
                // Убираем из избранного
                wishlist.splice(index, 1);
                icon.className = 'far fa-heart';
                
                // Показываем уведомление
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Убрано из избранного',
                        message: 'Товар убран из вашего избранного',
                        type: 'info',
                        duration: 3000
                    }
                }));
            } else {
                // Добавляем в избранное
                wishlist.push({
                    id: productId,
                    name: productName,
                    price: price,
                    image: image,
                    discount: discount
                });
                icon.className = 'fas fa-heart text-danger';
                
                // Показываем уведомление
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Добавлено в избранное!',
                        message: 'Товар добавлен в ваше избранное',
                        type: 'success',
                        duration: 3000
                    }
                }));
            }
            
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
            window.dispatchEvent(new Event('wishlist-updated'));
        }

        // Проверка избранного при загрузке
        function updateWishlistButtons() {
            const wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
            wishlist.forEach(item => {
                const button = document.querySelector(`.wishlist-btn[onclick*="${item.id}"]`);
                if (button) {
                    const icon = button.querySelector('i');
                    if (icon) {
                        icon.className = 'fas fa-heart text-danger';
                    }
                }
            });
        }

        $(document).ready(function() {
            // Обновляем кнопки избранного при загрузке
            updateWishlistButtons();

            // Переключение вида товаров
            $('.view-btn').on('click', function() {
                $('.view-btn').removeClass('active');
                $(this).addClass('active');
                
                const view = $(this).data('view');
                if (view === 'list') {
                    $('.products-grid').css('grid-template-columns', '1fr');
                } else {
                    $('.products-grid').css('grid-template-columns', 'repeat(auto-fill, minmax(200px, 1fr))');
                }
            });

            // Анимация появления элементов
            $('.product-card').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
            });

            // Сортировка товаров
            $('#sort-select').on('change', function() {
                const sortValue = $(this).val();
                const productsGrid = $('.products-grid');
                const products = productsGrid.find('.product-card').get();
                
                products.sort(function(a, b) {
                    const productA = $(a);
                    const productB = $(b);
                    
                    switch(sortValue) {
                        case 'price-asc':
                            return parseFloat(productA.data('price')) - parseFloat(productB.data('price'));
                        case 'price-desc':
                            return parseFloat(productB.data('price')) - parseFloat(productA.data('price'));
                        case 'name-asc':
                            return productA.data('name').localeCompare(productB.data('name'), 'uk');
                        case 'name-desc':
                            return productB.data('name').localeCompare(productA.data('name'), 'uk');
                        case 'new':
                            return parseInt(productB.data('id')) - parseInt(productA.data('id'));
                        default: // popular
                            return 0; // Оставляем как есть
                    }
                });
                
                // Перестраиваем сетку
                productsGrid.empty();
                products.forEach(function(product) {
                    productsGrid.append(product);
                });
                
                // Обновляем анимацию
                $('.product-card').each(function(index) {
                    $(this).css('animation-delay', (index * 0.1) + 's');
                });
            });
        });
    </script>
@endsection
