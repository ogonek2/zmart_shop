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

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
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

        /* Улучшенная адаптивность */
        @media (max-width: 1200px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1.25rem;
            }
        }

        @media (max-width: 992px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 1rem;
            }
        }

        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 1rem;
                padding: 0 0.5rem;
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
        }

        @media (max-width: 576px) {
            .products-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 0.75rem;
                padding: 0 0.25rem;
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
        }

        @media (max-width: 480px) {
            .products-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 0.5rem;
                padding: 0 0.25rem;
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
                            <product-card 
                                id="{{ $product->id }}"
                                name="{{ $product->name }}"
                                price="{{ $product->price }}"
                                discount="{{ $product->discount }}"
                                image_path="{{ $product->image_path ?? 'https://via.placeholder.com/300x300/f8f9fa/6c757d?text=Нет+фото' }}"
                                url="{{ $product->url }}"
                                articule="{{ $product->articule ?? 'Не указан' }}"
                                rating="4.5"
                                availability="{{ $product->availability ?? 1 }}"
                                description="{{ $product->description ?? '' }}"
                                variant="default"
                                show-rating="true"
                                show-compare="false"
                                show-additional-info="false">
                            </product-card>
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
        $(document).ready(function() {
            // Переключение вида товаров
            $('.view-btn').on('click', function() {
                $('.view-btn').removeClass('active');
                $(this).addClass('active');
                
                const view = $(this).data('view');
                if (view === 'list') {
                    $('.products-grid').css('grid-template-columns', '1fr');
                } else {
                    $('.products-grid').css('grid-template-columns', 'repeat(auto-fill, minmax(280px, 1fr))');
                }
            });

            // Обработка избранного
            $('.wishlist-btn').on('click', function() {
                const icon = $(this).find('i');
                if (icon.hasClass('far')) {
                    icon.removeClass('far').addClass('fas text-danger');
                } else {
                    icon.removeClass('fas text-danger').addClass('far');
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
                            return parseFloat(productA.attr('price')) - parseFloat(productB.attr('price'));
                        case 'price-desc':
                            return parseFloat(productB.attr('price')) - parseFloat(productA.attr('price'));
                        case 'name-asc':
                            return productA.attr('name').localeCompare(productB.attr('name'), 'uk');
                        case 'name-desc':
                            return productB.attr('name').localeCompare(productA.attr('name'), 'uk');
                        case 'new':
                            return parseInt(productB.attr('id')) - parseInt(productA.attr('id'));
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
