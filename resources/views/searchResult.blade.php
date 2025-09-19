@extends('layouts.app')

@section('styles')
<style>
    .search-results-page {
        padding: 2rem 0;
    }
    
    .search-header {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        padding: 3rem 0;
        margin-bottom: 2rem;
        border-radius: 16px;
    }
    
    .search-query {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .search-stats {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    .suggestions-section {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }
    
    .suggestions-title {
        color: var(--dark-color);
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .suggestion-links {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .suggestion-link {
        background: var(--light-color);
        color: var(--primary-color);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }
    
    .suggestion-link:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-1px);
        text-decoration: none;
    }
    
    .alternative-query {
        background: rgba(255, 255, 255, 0.1);
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-top: 1rem;
        font-size: 0.9rem;
    }
    
    .no-results-section {
        background: white;
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }
    
    .no-results-icon {
        font-size: 4rem;
        color: var(--gray-color);
        margin-bottom: 1.5rem;
    }
    
    .no-results-title {
        color: var(--dark-color);
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .no-results-text {
        color: var(--gray-color);
        font-size: 1.1rem;
        margin-bottom: 2rem;
    }
    
    .search-tips {
        background: var(--light-color);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: left;
    }
    
    .search-tips h6 {
        color: var(--dark-color);
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .search-tips ul {
        margin: 0;
        padding-left: 1.5rem;
    }
    
    .search-tips li {
        color: var(--gray-color);
        margin-bottom: 0.5rem;
    }
    
    .search-tips li:last-child {
        margin-bottom: 0;
    }
    
    .back-to-catalog {
        background: var(--primary-color);
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .back-to-catalog:hover {
        background: var(--primary-hover);
        color: white;
        transform: translateY(-2px);
        text-decoration: none;
        box-shadow: var(--shadow-md);
    }
    
    @media (max-width: 768px) {
        .search-header {
            padding: 2rem 0;
            margin-bottom: 1.5rem;
        }
        
        .search-query {
            font-size: 1.5rem;
        }
        
        .suggestions-section {
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .suggestion-links {
            gap: 0.5rem;
        }
        
        .suggestion-link {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }
        
        .no-results-section {
            padding: 2rem 1rem;
        }
        
        .no-results-icon {
            font-size: 3rem;
        }
        
        .no-results-title {
            font-size: 1.25rem;
        }
    }
</style>
@endsection

@section('seo')
    <title>ZMART - Результаты поиска: "{{ $query }}" | Поиск</title>
@endsection

@section('content')
<section class="search-results-page">
    <div class="container">
        <!-- Заголовок результатов поиска -->
        <div class="search-header text-center">
            <h1 class="search-query">
                <i class="fas fa-search me-3"></i>
                Результаты поиска «{{ $originalQuery }}»
            </h1>
            <div class="search-stats">
                @if (!empty($getProducts) && $getProducts->count() > 0)
                    Найдено {{ $getProducts->total() }} товаров
                @else
                    По вашему запросу ничего не найдено
                @endif
            </div>
            
            @if ($usedAlternative && $query !== $originalQuery)
                <div class="alternative-query">
                    <i class="fas fa-info-circle me-2"></i>
                    Показаны результаты для <strong>{{ $query }}</strong>
                </div>
            @endif
        </div>

        @if (!empty($suggestions))
            <!-- Предложения по поиску -->
            <div class="suggestions-section">
                <h3 class="suggestions-title">
                    <i class="fas fa-lightbulb text-warning"></i>
                    Возможно, вы имели в виду:
                </h3>
                <div class="suggestion-links">
                    @foreach ($suggestions as $suggestion)
                        <a href="{{ url('/search?q=' . urlencode($suggestion)) }}" 
                           class="suggestion-link">
                            «{{ $suggestion }}»
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($getProducts->isEmpty())
            <!-- Сообщение об отсутствии результатов -->
            <div class="no-results-section">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h2 class="no-results-title">Ничего не найдено</h2>
                <p class="no-results-text">
                    По вашему запросу «<strong>{{ $originalQuery }}</strong>» товары не найдены.
                </p>
                
                <!-- Советы по поиску -->
                <div class="search-tips">
                    <h6><i class="fas fa-lightbulb text-warning me-2"></i>Советы по поиску:</h6>
                    <ul>
                        <li>Проверьте правильность написания</li>
                        <li>Попробуйте использовать более общие слова</li>
                        <li>Используйте синонимы</li>
                        <li>Убедитесь, что все слова написаны правильно</li>
                    </ul>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('home') }}" class="back-to-catalog">
                        <i class="fas fa-arrow-left"></i>
                        Вернуться в каталог
                    </a>
                </div>
            </div>
        @else
            <!-- Мобильная кнопка для открытия боковой панели -->
            <div class="d-lg-none mb-3">
                <button class="btn btn-primary w-100" type="button" data-bs-toggle="offcanvas" data-bs-target="#categoriesOffcanvas">
                    <i class="fas fa-th-large me-2"></i>Категории
                </button>
            </div>

            <!-- Результаты поиска -->
            <div class="row">
                <!-- Боковая панель с категориями -->
                <div class="col-12 col-lg-3 d-none d-lg-block">
                    @include('includes.catalog.categories-sidebar')
                </div>

                <!-- Основной контент -->
                <main class="col-12 col-lg-9">
                    <div class="results-info mb-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h5 class="mb-0 text-muted">
                                    <i class="fas fa-list me-2"></i>
                                    Найдено {{ $getProducts->total() }} товаров
                                </h5>
                                <small class="text-muted">
                                    Страница {{ $getProducts->currentPage() }} из {{ $getProducts->lastPage() }}
                                </small>
                            </div>
                            
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-arrow-left me-2"></i>В каталог
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Список товаров -->
                    <catalog-product-list :products='@json($getProducts->items())'
                                        :pagination='@json($paginationData)'>
                    </catalog-product-list>
                </main>
            </div>
        @endif
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
