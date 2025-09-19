@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 animate-fade-in-up">
                <h1 class="display-4 fw-bold mb-4">
                    Лучшая бытовая техника для вашего дома
                </h1>
                <p class="lead mb-4">
                    Широкий выбор качественной техники от ведущих мировых брендов. 
                    Доставка по всей Украине, гарантия качества и отличные цены.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-light btn-lg fw-semibold">
                        <i class="fas fa-shopping-cart me-2"></i>Перейти в каталог
                    </a>
                    <a href="#" class="btn btn-outline-light btn-lg fw-semibold">
                        <i class="fas fa-play me-2"></i>Смотреть видео
                    </a>
                </div>
            </div>
            <div class="col-lg-6 animate-fade-in-up">
                <div class="text-center">
                    <img src="https://via.placeholder.com/600x400/ffffff20/ffffff?text=ZMART+Tech" 
                         alt="Бытовая техника" class="img-fluid rounded-custom shadow-custom">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Категории -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold text-gradient mb-3">Популярные категории</h2>
                <p class="lead text-muted">Выберите нужную категорию и найдите идеальную технику</p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach (get_all_category()->take(6) as $category)
            <div class="col-lg-4 col-md-6 animate-fade-in-up">
                <div class="card category-card h-100 hover-lift transition-all">
                    <div class="card-body text-center p-4">
                        <div class="category-icon mb-3">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-tv fa-2x text-primary"></i>
                            </div>
                        </div>
                        <h5 class="card-title fw-semibold mb-2">{{ $category->name }}</h5>
                        <p class="card-text text-muted mb-3">{{ $category->products()->count() }} товаров</p>
                        <a href="{{ route('catalog_category_page', $category->url) }}" 
                           class="btn btn-outline-primary stretched-link">
                            Перейти в категорию
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Преимущества -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold text-gradient mb-3">Почему выбирают ZMART?</h2>
                <p class="lead text-muted">Мы заботимся о качестве и удобстве наших клиентов</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 animate-fade-in-up">
                <div class="text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-shipping-fast fa-2x text-primary"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Быстрая доставка</h5>
                    <p class="text-muted">Доставляем по всей Украине в кратчайшие сроки</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 animate-fade-in-up">
                <div class="text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-shield-alt fa-2x text-success"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Гарантия качества</h5>
                    <p class="text-muted">Официальная гарантия на всю продукцию</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 animate-fade-in-up">
                <div class="text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-headset fa-2x text-warning"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Поддержка 24/7</h5>
                    <p class="text-muted">Наши специалисты всегда готовы помочь</p>
                </div>
            </div>
            
            <div class="col-lg-3 col-md-6 animate-fade-in-up">
                <div class="text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 80px; height: 80px;">
                        <i class="fas fa-tags fa-2x text-info"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">Лучшие цены</h5>
                    <p class="text-muted">Конкурентные цены и регулярные акции</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Популярные товары -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold text-gradient mb-3">Популярные товары</h2>
                <p class="lead text-muted">Товары, которые выбирают наши клиенты</p>
            </div>
        </div>
        
        <!-- Мобильная кнопка для открытия боковой панели -->
        <div class="d-lg-none mb-4">
            <button class="btn btn-primary w-100" type="button" data-bs-toggle="offcanvas" data-bs-target="#categoriesOffcanvas">
                <i class="fas fa-th-large me-2"></i>Категории
            </button>
        </div>

        <div class="row">
            <!-- Боковая панель с категориями -->
            <div class="col-12 col-lg-3 d-none d-lg-block">
                @include('includes.catalog.categories-sidebar')
            </div>
            
            <!-- Основной контент -->
            <div class="col-12 col-lg-9">
                @include('includes.catalog.recomendet')
            </div>
        </div>
    </div>
</section>

<!-- Бренды -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold text-gradient mb-3">Наши бренды</h2>
                <p class="lead text-muted">Работаем только с проверенными производителями</p>
            </div>
        </div>
        
        <div class="row g-4 align-items-center">
            <div class="col-lg-2 col-md-3 col-6">
                <div class="brand-item text-center p-3">
                    <div class="bg-white rounded-custom p-4 shadow-sm hover-lift transition-all">
                        <i class="fas fa-tv fa-3x text-primary"></i>
                        <h6 class="mt-2 mb-0 fw-semibold">Samsung</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="brand-item text-center p-3">
                    <div class="bg-white rounded-custom p-4 shadow-sm hover-lift transition-all">
                        <i class="fas fa-tv fa-3x text-primary"></i>
                        <h6 class="mt-2 mb-0 fw-semibold">LG</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="brand-item text-center p-3">
                    <div class="bg-white rounded-custom p-4 shadow-sm hover-lift transition-all">
                        <i class="fas fa-tv fa-3x text-primary"></i>
                        <h6 class="mt-2 mb-0 fw-semibold">Sony</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="brand-item text-center p-3">
                    <div class="bg-white rounded-custom p-4 shadow-sm hover-lift transition-all">
                        <i class="fas fa-tv fa-3x text-primary"></i>
                        <h6 class="mt-2 mb-0 fw-semibold">Panasonic</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="brand-item text-center p-3">
                    <div class="bg-white rounded-custom p-4 shadow-sm hover-lift transition-all">
                        <i class="fas fa-tv fa-3x text-primary"></i>
                        <h6 class="mt-2 mb-0 fw-semibold">Philips</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-3 col-6">
                <div class="brand-item text-center p-3">
                    <div class="bg-white rounded-custom p-4 shadow-sm hover-lift transition-all">
                        <i class="fas fa-tv fa-3x text-primary"></i>
                        <h6 class="mt-2 mb-0 fw-semibold">Bosch</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-gradient-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="display-5 fw-bold mb-4">Готовы к покупке?</h2>
                <p class="lead mb-4">
                    Присоединяйтесь к тысячам довольных клиентов ZMART. 
                    Получите качественную технику по лучшим ценам!
                </p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="#" class="btn btn-light btn-lg fw-semibold">
                        <i class="fas fa-shopping-cart me-2"></i>Начать покупки
                    </a>
                    <a href="#" class="btn btn-outline-light btn-lg fw-semibold">
                        <i class="fas fa-phone me-2"></i>Позвонить нам
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Мобильная боковая панель с категориями -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="categoriesOffcanvas" 
     aria-labelledby="categoriesOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="categoriesOffcanvasLabel">Категории</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        @include('includes.catalog.categories-sidebar')
    </div>
</div>
@endsection
