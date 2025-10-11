@extends('layouts.app')

@section('styles')
    <style>
        /* CSS переменные для совместимости */
        :root {
            --primary-color: #3b82f6;
            --primary-hover: #2563eb;
            --secondary-color: #f59e0b;
            --secondary-hover: #d97706;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --gray-color: #64748b;
            --border-color: #e2e8f0;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        /* Основные стили страницы */
        .product-page {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .product-container {
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            margin: 20px 0;
            overflow: hidden;
        }

        .product-header {
            padding: 20px 0;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
            font-size: 14px;
        }

        .breadcrumb-item a {
            color: #6c757d;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            color: var(--primary-color);
        }

        .breadcrumb-item.active {
            color: #495057;
        }

        /* Галерея изображений */
        .product-gallery {
            padding: 30px;
            background: white;
        }

        .main-image-container {
            position: relative;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            overflow: hidden;
            background: white;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
        }

        .main-image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            background: white;
            padding: 20px;
        }

        .thumbnail-container {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            object-fit: cover;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .thumbnail img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .thumbnail:hover {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }

        .thumbnail.active {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        /* Информация о товаре */
        .product-info {
            padding: 30px;
            background: white;
        }

        .product-title {
            font-size: 28px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .product-articule {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .product-subtitle {
            color: #6c757d;
            font-size: 16px;
            margin-bottom: 25px;
        }

        /* Индикатор наличия */
        .availability-badge-page {
            background: var(--danger-color);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
            box-shadow: var(--shadow-sm);
        }

        /* Цена */
        .price-section {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e9ecef;
        }

        .discount-badge {
            background: var(--danger-color);
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 15px;
        }

        .price-current {
            font-size: 32px;
            font-weight: 700;
            color: var(--success-color);
            margin-right: 15px;
        }

        .price-old {
            font-size: 18px;
            color: #6c757d;
            text-decoration: line-through;
        }

        /* Кнопки действий */
        .product-actions {
            display: flex;
            gap: 15px;
            margin: 25px 0;
            flex-wrap: wrap;
        }

        .btn-add-to-cart {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s ease;
            flex: 1;
            min-width: 200px;
        }

        .btn-add-to-cart:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-add-to-cart:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
            opacity: 0.6;
        }

        .btn-add-to-cart:disabled:hover {
            background: #6c757d;
            transform: none;
            box-shadow: none;
        }

        .btn-wishlist {
            background: white;
            color: #6c757d;
            border: 2px solid #e9ecef;
            padding: 15px;
            border-radius: 6px;
            font-weight: 600;
            transition: all 0.2s ease;
            min-width: 60px;
        }

        .btn-wishlist:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .btn-wishlist.active {
            border-color: var(--danger-color);
            color: var(--danger-color);
            background: rgba(239, 68, 68, 0.1);
        }

        .btn-wishlist.active i {
            color: var(--danger-color);
        }

        /* Особенности товара */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin: 25px 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .feature-item:hover {
            background: white;
            box-shadow: var(--shadow-sm);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .feature-text {
            flex: 1;
        }

        .feature-title {
            font-weight: 600;
            color: #212529;
            margin-bottom: 4px;
            font-size: 14px;
        }

        .feature-description {
            color: #6c757d;
            font-size: 12px;
        }

        /* Табы */
        .product-tabs {
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            margin: 20px 0;
            overflow: hidden;
        }

        .nav-tabs {
            border: none;
            background: #f8f9fa;
            padding: 0;
            margin: 0;
        }


        .nav-tabs .nav-link {
            border: none;
            border-radius: 0;
            margin: 0;
            padding: 20px;
            color: #6c757d;
            font-weight: 500;
            font-size: 16px;
            transition: all 0.2s ease;
            text-align: center;
            position: relative;
        }

        .nav-tabs .nav-link:hover {
            background: white;
            color: var(--primary-color);
        }

        .nav-tabs .nav-link.active {
            background: white;
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
        }

        .tab-content {
            padding: 0;
        }

        .tab-pane {
            padding: 30px;
        }

        .tab-pane h4 {
            color: #212529;
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 20px;
        }

        .specs-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .specs-list li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
            transition: all 0.2s ease;
        }

        .specs-list li:hover {
            background: #f8f9fa;
            margin: 0 -15px;
            padding: 15px;
            border-radius: 6px;
        }

        .specs-list li:last-child {
            border-bottom: none;
        }

        .spec-name {
            font-weight: 500;
            color: #212529;
        }

        .spec-value {
            color: #6c757d;
            font-weight: 400;
        }

        .delivery-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e9ecef;
        }

        .delivery-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #e9ecef;
        }

        .delivery-item:last-child {
            border-bottom: none;
        }

        .delivery-icon {
            width: 40px;
            height: 40px;
            background: white;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 18px;
            box-shadow: var(--shadow-sm);
        }

        .delivery-text {
            flex: 1;
        }

        .delivery-title {
            font-weight: 600;
            color: #212529;
            margin-bottom: 4px;
        }

        .delivery-subtitle {
            color: #6c757d;
            font-size: 14px;
        }

        /* Рекомендуемые товары в стиле Rozetka */
        .recommended-section {
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            margin: 20px 0;
            padding: 30px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 24px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 10px;
        }

        .section-subtitle {
            color: #6c757d;
            font-size: 16px;
            max-width: 500px;
            margin: 0 auto;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-color);
        }

        .card-img-wrapper {
            position: relative;
            overflow: hidden;
            background: #f8f9fa;
            padding: 20px;
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.2s ease;
        }

        .product-card:hover .card-img {
            transform: scale(1.05);
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 16px;
            font-weight: 500;
            color: #212529;
            margin-bottom: 15px;
            line-height: 1.4;
            height: 44px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .card-articule {
            color: #6c757d;
            font-size: 12px;
            margin-bottom: 10px;
            font-weight: 400;
        }

        .card-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .current-price {
            font-size: 18px;
            font-weight: 600;
            color: var(--success-color);
        }

        .old-price {
            font-size: 14px;
            color: #6c757d;
            text-decoration: line-through;
        }

        .card-actions {
            display: flex;
            gap: 10px;
        }

        .btn-card {
            flex: 1;
            padding: 10px;
            border-radius: 6px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .btn-card-primary {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-card-primary:hover {
            background: var(--primary-hover);
            color: white;
        }

        .btn-card-outline {
            background: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-card-outline:hover {
            background: var(--primary-color);
            color: white;
        }

        /* Адаптивность */
        @media (max-width: 1200px) {
            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 15px;
            }
        }

        @media (max-width: 768px) {
            .product-container {
                margin: 10px 0;
            }

            .product-gallery,
            .product-info {
                padding: 20px;
            }

            .product-title {
                font-size: 24px;
            }

            .price-current {
                font-size: 28px;
            }

            .product-actions {
                flex-direction: column;
            }

            .btn-add-to-cart {
                min-width: auto;
            }

            .tab-pane {
                padding: 20px;
            }

            .recommended-section {
                padding: 20px;
            }

            .section-title {
                font-size: 20px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }
        }

        @media (max-width: 576px) {
            .product-title {
                font-size: 20px;
            }

            .price-current {
                font-size: 24px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }

            .nav-tabs .nav-link {
                padding: 15px 10px;
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('seo')
    <title>
        {{ $product->name }} - Деталі про товар. Замовити онлайн {{ $product->categories->first()?->name }}
    </title>
@endsection

@section('content')
    <div class="product-page">
        <div class="container">
            <!-- Хлебные крошки -->
            <div class="product-header">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('welcome') }}">Главная</a></li>
                        @if ($product->categories->first())
                            <li class="breadcrumb-item">
                                <a href="{{ route('catalog_category_page', $product->categories->first()->url) }}">
                                    {{ $product->categories->first()->name }}
                                </a>
                            </li>
                        @endif
                        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                    </ol>
                </nav>
            </div>

            <!-- Основная информация о товаре -->
            <div class="product-container">
                <div class="row g-0">
                    <!-- Галерея изображений -->
                    <div class="col-lg-6">
                        <div class="product-gallery">
                            <div class="main-image-container">
                                @if ($product->image_path)
                                    <img src="{{ $product->image_path }}" alt="{{ $product->name }}" class="main-image"
                                        id="mainImage"
                                        onerror="this.src='https://via.placeholder.com/400x400/f8f9fa/6c757d?text=Изображение+не+найдено'">
                                @else
                                    <img src="https://via.placeholder.com/400x400/f8f9fa/6c757d?text=Изображение+отсутствует"
                                        alt="{{ $product->name }}" class="main-image" id="mainImage">
                                @endif
                            </div>

                            @if ($images && $images->count() > 0)
                                <div class="thumbnail-container">
                                    @if ($product->image_path)
                                        <div class="thumbnail active"
                                            onclick="changeMainImage('{{ $product->image_path }}')">
                                            <img src="{{ $product->image_path }}" alt="{{ $product->name }}"
                                                onerror="this.src='https://via.placeholder.com/80x80/f8f9fa/6c757d?text=Фото'">
                                        </div>
                                    @endif
                                    @foreach ($images as $image)
                                        <div class="thumbnail" onclick="changeMainImage('{{ $image->src }}')">
                                            <img src="{{ $image->src }}" alt="{{ $product->name }}"
                                                onerror="this.src='https://via.placeholder.com/80x80/f8f9fa/6c757d?text=Фото'">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Информация о товаре -->
                    <div class="col-lg-6">
                        <div class="product-info">
                            <div class="product-header">
                                <h1 class="product-title">{{ $product->name }}</h1>
                                <p class="product-articule">Артикул: {{ $product->articule ?? 'Не указан' }}</p>
                                @if ($product->availability === 2)
                                    <div class="availability-badge-page">
                                        <i class="fas fa-times-circle me-2"></i>Нет в наличии
                                    </div>
                                @endif
                                <p class="product-subtitle">
                                    {{ $product->description ? mb_substr(strip_tags($product->description), 0, 150) . '...' : 'Описание товара отсутствует' }}
                                </p>
                            </div>

                            <!-- Цена -->
                            <div class="price-section">
                                @if ($product->discount > 0)
                                    <div class="discount-badge">
                                        <i class="fas fa-tag me-2"></i>Скидка {{ $product->discount }}%
                                    </div>
                                @endif
                                <div class="d-flex align-items-center">
                                    <span class="price-current">{{ number_format($product->price, 0, ',', ' ') }} ₴</span>
                                    @if ($product->discount > 0)
                                        <span
                                            class="old-price">{{ number_format($product->price * (1 + $product->discount / 100), 0, ',', ' ') }}
                                            ₴</span>
                                    @endif
                                </div>
                                
                                @if($product->is_wholesale && $product->wholesale_price > 0 && $product->wholesale_min_quantity)
                                    <div class="wholesale-price mt-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-boxes text-primary"></i>
                                            <span class="text-muted">Оптовая цена:</span>
                                            <span class="fw-bold text-primary">{{ number_format($product->wholesale_price, 0, ',', ' ') }} ₴</span>
                                        </div>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-info-circle me-1"></i>Доступна при заказе от {{ $product->wholesale_min_quantity }} {{ $product->wholesale_min_quantity == 1 ? 'единицы' : 'единиц' }}
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <!-- Кнопки действий -->
                            <div class="product-actions">
                                <cart-button 
                                    data-product-id="{{ $product->id }}" 
                                    data-product-name="{{ $product->name }}"
                                    data-product-price="{{ number_format($product->price, 0, ',', ' ') }}"
                                    data-product-image="{{ $product->image_path }}" 
                                    data-product-articule="{{ $product->articule }}"
                                    @if($product->is_wholesale && $product->wholesale_price && $product->wholesale_min_quantity)
                                    data-product-is-wholesale="true"
                                    data-product-wholesale-price="{{ $product->wholesale_price }}"
                                    data-product-wholesale-min-quantity="{{ $product->wholesale_min_quantity }}"
                                    @endif
                                    id="{{ $product->id }}" 
                                    name="{{ $product->name }}"
                                    price="{{ number_format($product->price, 0, ',', ' ') }}"
                                    image="{{ $product->image_path }}" 
                                    articule="{{ $product->articule }}"
                                    @if($product->is_wholesale && $product->wholesale_price && $product->wholesale_min_quantity)
                                    is-wholesale="true"
                                    wholesale-price="{{ $product->wholesale_price }}"
                                    wholesale-min-quantity="{{ $product->wholesale_min_quantity }}"
                                    @endif
                                    ></cart-button>
                                <button class="btn btn-wishlist" onclick="toggleWishlist({{ $product->id }})">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>

                            <!-- Особенности товара -->
                            <div class="features-grid">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fas fa-shipping-fast"></i>
                                    </div>
                                    <div class="feature-text">
                                        <div class="feature-title">Быстрая доставка</div>
                                        <div class="feature-description">По всей Украине</div>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="feature-text">
                                        <div class="feature-title">Гарантия качества</div>
                                        <div class="feature-description">12 месяцев</div>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fas fa-undo"></i>
                                    </div>
                                    <div class="feature-text">
                                        <div class="feature-title">Возврат товара</div>
                                        <div class="feature-description">14 дней</div>
                                    </div>
                                </div>
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fas fa-headset"></i>
                                    </div>
                                    <div class="feature-text">
                                        <div class="feature-title">Поддержка 24/7</div>
                                        <div class="feature-description">Консультации</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Табы с детальной информацией -->
            <div class="product-tabs">
                <ul class="nav nav-tabs" id="productTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                            data-bs-target="#description" type="button" role="tab">
                            <i class="fas fa-info-circle me-2"></i>Описание
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specs-tab" data-bs-toggle="tab" data-bs-target="#specs"
                            type="button" role="tab">
                            <i class="fas fa-list me-2"></i>Характеристики
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="delivery-tab" data-bs-toggle="tab" data-bs-target="#delivery"
                            type="button" role="tab">
                            <i class="fas fa-truck me-2"></i>Доставка и оплата
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews"
                            type="button" role="tab">
                            <i class="fas fa-star me-2"></i>Отзывы
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="productTabsContent">
                    <!-- Описание -->
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <h4>Описание товара</h4>
                        <div class="row">
                            <div class="col-lg-8">
                                {!! $product->description ?? '<p>Подробное описание товара отсутствует.</p>' !!}
                            </div>
                            <div class="col-lg-4">
                                <div class="delivery-info">
                                    <h6 class="fw-semibold mb-3">
                                        <i class="fas fa-truck me-2"></i>Информация о доставке
                                    </h6>
                                    <div class="delivery-item">
                                        <div class="delivery-icon">
                                            <i class="fas fa-shipping-fast"></i>
                                        </div>
                                        <div class="delivery-text">
                                            <div class="delivery-title">Новая Почта</div>
                                            <div class="delivery-subtitle">Отправим завтра</div>
                                        </div>
                                    </div>
                                    <div class="delivery-item">
                                        <div class="delivery-icon">
                                            <i class="fas fa-globe"></i>
                                        </div>
                                        <div class="delivery-text">
                                            <div class="delivery-title">Meest Express</div>
                                            <div class="delivery-subtitle">Отправим завтра</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Характеристики -->
                    <div class="tab-pane fade" id="specs" role="tabpanel">
                        @php
                            $hasValidCharacteristics = false;
                            $validCharacteristics = [];
                            
                            // Проверяем характеристики из продукта
                            if (!empty($product->characteristics) && is_array($product->characteristics)) {
                                foreach ($product->characteristics as $charKey => $charValue) {
                                    if (!is_null($charValue) && $charValue !== '' && $charValue !== 'Не указано') {
                                        $hasValidCharacteristics = true;
                                        $validCharacteristics[] = [
                                            'name' => is_string($charKey) ? ucwords(str_replace(['_', '-'], ' ', $charKey)) : 'Параметр ' . count($validCharacteristics) + 1,
                                            'value' => is_array($charValue) ? implode(', ', $charValue) : $charValue
                                        ];
                                    }
                                }
                            }
                            
                            // Если нет валидных характеристик из продукта, проверяем шаблон
                            if (!$hasValidCharacteristics && !empty($characteristics) && count($characteristics) > 0) {
                                foreach ($characteristics as $char) {
                                    $value = (is_array($product->characteristics) ? $product->characteristics[$char['key']] : null) ?? $char['default_value'] ?? '-';
                                    if ($value !== '-' && $value !== '' && $value !== 'Не указано') {
                                        $hasValidCharacteristics = true;
                                        $validCharacteristics[] = [
                                            'name' => $char['name'] ?? 'Не указано',
                                            'value' => $value
                                        ];
                                    }
                                }
                            }
                            
                            // Если все еще нет валидных характеристик, проверяем старую систему
                            if (!$hasValidCharacteristics && isset($product->package) && count($product->package) > 0) {
                                foreach ($product->package as $item) {
                                    if (!empty($item->value) && $item->value !== 'Не указано') {
                                        $hasValidCharacteristics = true;
                                        $validCharacteristics[] = [
                                            'name' => $item->name,
                                            'value' => $item->value
                                        ];
                                    }
                                }
                            }
                        @endphp
                        
                        @if ($hasValidCharacteristics)
                            <h4>Технические характеристики</h4>
                            <ul class="specs-list">
                                @foreach ($validCharacteristics as $char)
                                    <li>
                                        <span class="spec-name">{{ $char['name'] }}</span>
                                        <span class="spec-value">{{ $char['value'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if (!empty($modifications) && count($modifications) > 0)
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-3">Модификации</h6>
                                <ul class="specs-list">
                                    @foreach ($modifications as $mod)
                                        <li>
                                            <span class="spec-name">{{ $mod['name'] ?? 'Не указано' }}</span>
                                            <span class="spec-value">{{ $product->modifications[$mod['key']] ?? $mod['default_value'] ?? '-' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (!empty($additionalFields) && count($additionalFields) > 0)
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-3">Дополнительная информация</h6>
                                <ul class="specs-list">
                                    @foreach ($additionalFields as $field)
                                        <li>
                                            <span class="spec-name">{{ $field['name'] ?? 'Не указано' }}</span>
                                            <span class="spec-value">{{ $product->additional_fields[$field['key']] ?? $field['default_value'] ?? '-' }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($product->complectation)
                            <div class="mt-4">
                                <h6 class="fw-semibold mb-3">Комплектация</h6>
                                <div class="bg-light p-3 rounded-custom">
                                    {!! strip_tags($product->complectation, '<p><br><ul><ol><li><strong><em><b><i><a>') !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Доставка и оплата -->
                    <div class="tab-pane fade" id="delivery" role="tabpanel">
                        <h4>Доставка и оплата</h4>

                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="delivery-info">
                                    <h6 class="fw-semibold mb-3">
                                        <i class="fas fa-truck me-2"></i>Способы доставки
                                    </h6>
                                    <div class="delivery-item">
                                        <div class="delivery-icon">
                                            <i class="fas fa-shipping-fast"></i>
                                        </div>
                                        <div class="delivery-text">
                                            <div class="delivery-title">Новая Почта</div>
                                            <div class="delivery-subtitle">Отправим завтра</div>
                                        </div>
                                    </div>
                                    <div class="delivery-item">
                                        <div class="delivery-icon">
                                            <i class="fas fa-globe"></i>
                                        </div>
                                        <div class="delivery-text">
                                            <div class="delivery-title">Meest Express</div>
                                            <div class="delivery-subtitle">Отправим завтра</div>
                                        </div>
                                    </div>
                                    <p class="text-muted small mt-3 mb-0">
                                        *Оплата за доставку осуществляется согласно тарифам выбранной службы доставки.
                                    </p>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="delivery-info">
                                    <h6 class="fw-semibold mb-3">
                                        <i class="fas fa-credit-card me-2"></i>Способы оплаты
                                    </h6>
                                    <div class="delivery-item">
                                        <div class="delivery-icon">
                                            <i class="fas fa-credit-card"></i>
                                        </div>
                                        <div class="delivery-text">
                                            <div class="delivery-title">Предоплата на карту банка</div>
                                        </div>
                                    </div>
                                    <div class="delivery-item">
                                        <div class="delivery-icon">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div class="delivery-text">
                                            <div class="delivery-title">Накладным платежом при получении</div>
                                        </div>
                                    </div>
                                    <div class="delivery-item">
                                        <div class="delivery-icon">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </div>
                                        <div class="delivery-text">
                                            <div class="delivery-title">Наличными на нашем складе</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Отзывы -->
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <h4>Отзывы о товаре</h4>
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Отзывы пока отсутствуют</h6>
                            <p class="text-muted">Будьте первым, кто оставит отзыв об этом товаре!</p>
                            <button class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Написать отзыв
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Рекомендуемые товары -->
            @if ($recommendedProducts && $recommendedProducts->count() > 0)
                <div class="recommended-section">
                    <div class="section-header">
                        <h3 class="section-title">Рекомендуемые товары</h3>
                        <p class="section-subtitle">Возможно, вас также заинтересуют эти товары</p>
                    </div>

                    <div class="products-grid">
                        @foreach ($recommendedProducts as $recProduct)
                            <div class="product-card">
                                <div class="card-img-wrapper">
                                    @if ($recProduct->image_path)
                                        <img src="{{ $recProduct->image_path }}" alt="{{ $recProduct->name }}"
                                            class="card-img"
                                            onerror="this.src='https://via.placeholder.com/300x300/f8f9fa/6c757d?text=Товар'">
                                    @else
                                        <img src="https://via.placeholder.com/300x300/f8f9fa/6c757d?text=Товар"
                                            alt="{{ $recProduct->name }}" class="card-img">
                                    @endif
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">{{ $recProduct->name }}</h5>
                                    <p class="card-articule">Артикул: {{ $recProduct->articule ?? 'Не указан' }}</p>
                                    <div class="card-price">
                                        <span class="current-price">{{ number_format($recProduct->price, 0, ',', ' ') }}
                                            ₴</span>
                                        @if ($recProduct->discount > 0)
                                            <span
                                                class="old-price">{{ number_format($recProduct->price * (1 + $recProduct->discount / 100), 0, ',', ' ') }}
                                                ₴</span>
                                        @endif
                                    </div>
                                    <div class="card-actions">
                                        <cart-button 
                                            data-product-id="{{ $recProduct->id }}" 
                                            data-product-name="{{ $recProduct->name }}"
                                            data-product-price="{{ number_format($recProduct->price, 0, ',', ' ') }}"
                                            data-product-image="{{ $recProduct->image_path }}" 
                                            data-product-articule="{{ $recProduct->articule }}"
                                            @if($recProduct->is_wholesale && $recProduct->wholesale_price && $recProduct->wholesale_min_quantity)
                                            data-product-is-wholesale="true"
                                            data-product-wholesale-price="{{ $recProduct->wholesale_price }}"
                                            data-product-wholesale-min-quantity="{{ $recProduct->wholesale_min_quantity }}"
                                            @endif
                                            id="{{ $recProduct->id }}" 
                                            name="{{ $recProduct->name }}"
                                            price="{{ number_format($recProduct->price, 0, ',', ' ') }}"
                                            image="{{ $recProduct->image_path }}"
                                            articule="{{ $recProduct->articule }}"
                                            @if($recProduct->is_wholesale && $recProduct->wholesale_price && $recProduct->wholesale_min_quantity)
                                            is-wholesale="true"
                                            wholesale-price="{{ $recProduct->wholesale_price }}"
                                            wholesale-min-quantity="{{ $recProduct->wholesale_min_quantity }}"
                                            @endif
                                            ></cart-button>
                                        <button class="btn btn-card btn-card-outline"
                                            onclick="toggleRecommendedWishlist({{ $recProduct->id }})">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            console.log('Страница товара загружена');

            // Инициализация Bootstrap табов
            try {
                var triggerTabList = [].slice.call(document.querySelectorAll('#productTabs button'));
                triggerTabList.forEach(function(triggerEl) {
                    var tabTrigger = new bootstrap.Tab(triggerEl);
                    triggerEl.addEventListener('click', function(event) {
                        event.preventDefault();
                        tabTrigger.show();
                    });
                });
                console.log('Bootstrap табы инициализированы');
            } catch (error) {
                console.error('Ошибка инициализации табов:', error);
            }

            // Анимация появления элементов
            $('.animate-fade-in-up').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
            });
        });

        // Функция переключения главного изображения
        function changeMainImage(src) {
            $('#mainImage').attr('src', src);
            $('.thumbnail').removeClass('active');
            event.target.closest('.thumbnail').classList.add('active');
        }

        // Функция тестирования тостера
        function testToast() {
            console.log('Тестируем тостер...');

            // Простой тостер
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: {
                    title: 'Тест тостера!',
                    message: 'Это тестовое уведомление для проверки работы',
                    type: 'success',
                    duration: 5000
                }
            }));

            // Тостер с товаром
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Тест с товаром!',
                        message: 'Тостер с данными товара',
                        type: 'info',
                        product: {
                            id: 999,
                            name: 'Тестовый товар',
                            price: '100.00',
                            image: 'https://via.placeholder.com/100x100/ff6b35/ffffff?text=Тест'
                        },
                        duration: 5000
                    }
                }));
            }, 1000);
        }

        // Функция добавления в корзину
        function addToCart(productId) {
            console.log('addToCart вызван для товара:', productId);
            try {
                // Проверяем доступность товара
                const availabilityBadge = document.querySelector('.availability-badge-page');
                if (availabilityBadge) {
                    console.log('Товар недоступен для покупки');
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            title: 'Товар недоступен',
                            message: 'Этот товар временно отсутствует в наличии',
                            type: 'warning',
                            duration: 4000
                        }
                    }));
                    return;
                }

                // Получаем данные о товаре
                const productContainer = document.querySelector('.product-container');
                const productName = productContainer.querySelector('.product-title').textContent;
                const productPrice = productContainer.querySelector('.price-current').textContent;
                const productImage = productContainer.querySelector('.main-image').src;

                console.log('Данные товара:', {
                    productName,
                    productPrice,
                    productImage
                });

                // Получаем текущую корзину
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                // Проверяем, есть ли уже такой товар в корзине
                let existingItem = cart.find(item => item.id === productId);

                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    // Добавляем новый товар
                    cart.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        image: productImage,
                        quantity: 1
                    });
                }

                // Сохраняем корзину
                localStorage.setItem('cart', JSON.stringify(cart));

                // Отправляем событие обновления корзины
                window.dispatchEvent(new Event('cart-updated'));

                // Показываем уведомление с данными товара
                console.log('Отправляем событие show-toast');
                const toastEvent = new CustomEvent('show-toast', {
                    detail: {
                        title: 'Товар добавлен в корзину!',
                        message: 'Товар успешно добавлен в вашу корзину',
                        type: 'success',
                        product: {
                            id: productId,
                            name: productName,
                            price: productPrice,
                            image: productImage
                        },
                        duration: 4000
                    }
                });

                console.log('Событие создано:', toastEvent);
                window.dispatchEvent(toastEvent);
                console.log('Событие отправлено');

            } catch (error) {
                console.error('Ошибка добавления в корзину:', error);

                // Показываем уведомление об ошибке
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Ошибка!',
                        message: 'Не удалось добавить товар в корзину',
                        type: 'error',
                        duration: 4000
                    }
                }));
            }
        }

        // Функция добавления в избранное
        function toggleWishlist(productId) {
            try {
                // Проверяем доступность товара
                const availabilityBadge = document.querySelector('.availability-badge-page');
                if (availabilityBadge) {
                    console.log('Товар недоступен для добавления в избранное');
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            title: 'Товар недоступен',
                            message: 'Недоступные товары нельзя добавить в избранное',
                            type: 'warning',
                            duration: 4000
                        }
                    }));
                    return;
                }

                // Получаем данные о товаре
                const productContainer = document.querySelector('.product-container');
                const productName = productContainer.querySelector('.product-title').textContent;
                const productPrice = productContainer.querySelector('.price-current').textContent;
                const productImage = productContainer.querySelector('.main-image').src;

                // Получаем текущее избранное
                let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

                // Проверяем, есть ли уже такой товар в избранном
                let existingItem = wishlist.find(item => item.id === productId);

                if (existingItem) {
                    // Убираем из избранного
                    wishlist = wishlist.filter(item => item.id !== productId);

                    // Обновляем кнопку
                    const button = document.querySelector(`[onclick="toggleWishlist(${productId})"]`);
                    button.classList.remove('active');

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
                        price: productPrice,
                        image: productImage
                    });

                    // Обновляем кнопку
                    const button = document.querySelector(`[onclick="toggleWishlist(${productId})"]`);
                    button.classList.add('active');

                    // Показываем уведомление
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            title: 'Добавлено в избранное!',
                            message: 'Товар добавлен в ваше избранное',
                            type: 'success',
                            product: {
                                id: productId,
                                name: productName,
                                price: productPrice,
                                image: productImage
                            },
                            duration: 4000
                        }
                    }));
                }

                // Сохраняем избранное
                localStorage.setItem('wishlist', JSON.stringify(wishlist));

                // Отправляем событие обновления избранного
                window.dispatchEvent(new Event('wishlist-updated'));

            } catch (error) {
                console.error('Ошибка работы с избранным:', error);

                // Показываем уведомление об ошибке
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Ошибка!',
                        message: 'Не удалось обновить избранное',
                        type: 'error',
                        duration: 4000
                    }
                }));
            }
        }

        // Функция показа уведомлений
        function showNotification(message, type = 'info') {
            // Создаем элемент уведомления
            let notification = document.createElement('div');
            notification.className =
                `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
            notification.style.cssText =
                'top: 20px; right: 20px; z-index: 9999; min-width: 300px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);';
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} me-2"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Автоматически скрываем через 3 секунды
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 3000);
        }

        // Функция обновления счетчика корзины
        function updateCartCounter() {
            try {
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);

                // Обновляем счетчик корзины, если он есть на странице
                let cartCounter = document.querySelector('.cart-counter, .cart-count');
                if (cartCounter) {
                    cartCounter.textContent = totalItems;
                    cartCounter.style.display = totalItems > 0 ? 'block' : 'none';
                }
            } catch (error) {
                console.error('Ошибка обновления счетчика корзины:', error);
            }
        }

        // Дополнительная отладка
        window.addEventListener('load', function() {
            console.log('Все ресурсы загружены');

            // Проверяем загрузку Bootstrap
            if (typeof bootstrap !== 'undefined') {
                console.log('Bootstrap загружен успешно');
            } else {
                console.error('Bootstrap не загружен');
            }

            // Инициализируем счетчик корзины
            updateCartCounter();
        });

        window.addEventListener('error', function(e) {
            console.error('JavaScript ошибка:', e.error);
        });

        // Функции для рекомендуемых товаров
        function addRecommendedToCart(productId) {
            try {
                // Получаем данные о товаре из карточки
                const productCard = document.querySelector(`[onclick="addRecommendedToCart(${productId})"]`).closest(
                    '.product-card');

                // Проверяем доступность товара
                const availabilityBadge = productCard.querySelector('.availability-badge');
                if (availabilityBadge) {
                    console.log('Рекомендуемый товар недоступен для покупки');
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            title: 'Товар недоступен',
                            message: 'Этот товар временно отсутствует в наличии',
                            type: 'warning',
                            duration: 4000
                        }
                    }));
                    return;
                }

                const productName = productCard.querySelector('.card-title').textContent;
                const productPrice = productCard.querySelector('.current-price').textContent;
                const productImage = productCard.querySelector('.card-img').src;

                // Получаем текущую корзину
                let cart = JSON.parse(localStorage.getItem('cart')) || [];

                // Проверяем, есть ли уже такой товар в корзине
                let existingItem = cart.find(item => item.id === productId);

                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    // Добавляем новый товар
                    cart.push({
                        id: productId,
                        name: productName,
                        price: productPrice,
                        image: productImage,
                        quantity: 1
                    });
                }

                // Сохраняем корзину
                localStorage.setItem('cart', JSON.stringify(cart));

                // Отправляем событие обновления корзины
                window.dispatchEvent(new Event('cart-updated'));

                // Показываем уведомление с данными товара
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Товар добавлен в корзину!',
                        message: 'Рекомендуемый товар добавлен в вашу корзину',
                        type: 'success',
                        product: {
                            id: productId,
                            name: productName,
                            price: productPrice,
                            image: productImage
                        },
                        duration: 4000
                    }
                }));

            } catch (error) {
                console.error('Ошибка добавления рекомендуемого товара в корзину:', error);

                // Показываем уведомление об ошибке
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Ошибка!',
                        message: 'Не удалось добавить товар в корзину',
                        type: 'error',
                        duration: 4000
                    }
                }));
            }
        }

        function toggleRecommendedWishlist(productId) {
            try {
                // Получаем данные о товаре из карточки
                const productCard = document.querySelector(`[onclick="toggleRecommendedWishlist(${productId})"]`).closest(
                    '.product-card');

                // Проверяем доступность товара
                const availabilityBadge = productCard.querySelector('.availability-badge');
                if (availabilityBadge) {
                    console.log('Рекомендуемый товар недоступен для добавления в избранное');
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            title: 'Товар недоступен',
                            message: 'Недоступные товары нельзя добавить в избранное',
                            type: 'warning',
                            duration: 4000
                        }
                    }));
                    return;
                }

                const productName = productCard.querySelector('.card-title').textContent;
                const productPrice = productCard.querySelector('.current-price').textContent;
                const productImage = productCard.querySelector('.card-img').src;

                // Получаем текущее избранное
                let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];

                // Проверяем, есть ли уже такой товар в избранном
                let existingItem = wishlist.find(item => item.id === productId);

                if (existingItem) {
                    // Убираем из избранного
                    wishlist = wishlist.filter(item => item.id !== productId);

                    // Обновляем кнопку
                    const button = document.querySelector(`[onclick="toggleRecommendedWishlist(${productId})"]`);
                    button.classList.remove('active');

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
                        price: productPrice,
                        image: productImage
                    });

                    // Обновляем кнопку
                    const button = document.querySelector(`[onclick="toggleRecommendedWishlist(${productId})"]`);
                    button.classList.add('active');

                    // Показываем уведомление
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            title: 'Добавлено в избранное!',
                            message: 'Товар добавлен в ваше избранное',
                            type: 'success',
                            product: {
                                id: productId,
                                name: productName,
                                price: productPrice,
                                image: productImage
                            },
                            duration: 4000
                        }
                    }));
                }

                // Сохраняем избранное
                localStorage.setItem('wishlist', JSON.stringify(wishlist));

                // Отправляем событие обновления избранного
                window.dispatchEvent(new Event('wishlist-updated'));

            } catch (error) {
                console.error('Ошибка работы с избранным:', error);

                // Показываем уведомление об ошибке
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Ошибка!',
                        message: 'Не удалось обновить избранное',
                        type: 'error',
                        duration: 4000
                    }
                }));
            }
        }
    </script>
@endsection
