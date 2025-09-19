<header class="sticky-top">
    <!-- Верхняя панель -->
    <div class="bg-dark text-white py-2 d-none d-md-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-4">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-map-marker-alt text-warning"></i>
                            <span class="small">Одесса</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-phone text-warning"></i>
                            <span class="small">+38 073-077-75-72</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="d-flex align-items-center justify-content-end gap-4">
                        <a href="{{ route('oplata_i_dostavka') }}" class="text-white-50 small text-decoration-none">
                            <i class="fas fa-truck me-1"></i>Доставка
                        </a>
                        <a href="{{ route('obmin_ta_povernennia') }}" class="text-white-50 small text-decoration-none">
                            <i class="fas fa-shield-alt me-1"></i>Гарантия
                        </a>
                        <a href="{{ route('kontaktna_informatsiia') }}" class="text-white-50 small text-decoration-none">
                            <i class="fas fa-headset me-1"></i>Поддержка
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Основная навигация -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <!-- Логотип -->
            <a class="navbar-brand fw-bold text-primary fs-3" href="{{ url('/') }}">
                <i class="fas fa-bolt text-warning me-2"></i>ZMART
            </a>

            <!-- Поиск (десктоп) -->
            <div class="d-none d-lg-block flex-grow-1 mx-4">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <search></search>
                </div>
            </div>

            <!-- Правая часть навигации -->
            <div class="d-flex align-items-center gap-3">
                <!-- Избранное -->
                <div class="position-relative">
                    <wishlist-button></wishlist-button>
                </div>

                <!-- Корзина -->
                <div class="position-relative">
                    <open-cart-button></open-cart-button>
                </div>

                <!-- Бургер меню -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu" aria-controls="mobileMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Меню категорий -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary d-none d-lg-block">
        <div class="container">
            <ul class="navbar-nav me-auto">
                <!-- Каталог -->
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="#" data-bs-toggle="offcanvas" data-bs-target="#catalogMenu" aria-controls="catalogMenu">
                        <i class="fas fa-th-large me-2"></i>
                        <span>Каталог</span>
                    </a>
                </li>

                <!-- О нас -->
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ route('pro_kompaniiu') }}">
                        <i class="fas fa-info-circle me-2"></i>О нас
                    </a>
                </li>
            </ul>

            <!-- Дополнительные ссылки -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="{{ route('kontaktna_informatsiia') }}">
                        <i class="fas fa-phone me-2"></i>Заказать звонок
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Боковое мобильное меню -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title" id="mobileMenuLabel">
                <i class="fas fa-bars me-2"></i>Меню
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <!-- Мобильный поиск -->
            <div class="p-3 bg-light border-bottom">
                <div class="position-relative">
                    <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                    <search></search>
                </div>
            </div>

            <!-- Навигационные ссылки -->
            <div class="list-group list-group-flush">
                <!-- Каталог -->
                <div class="list-group-item p-0">
                    <a class="list-group-item list-group-item-action d-flex align-items-center justify-content-between" 
                       data-bs-toggle="collapse" href="#catalogCollapse" role="button" aria-expanded="false" aria-controls="catalogCollapse">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-th-large me-3 text-primary"></i>
                            <span class="fw-semibold">Каталог</span>
                        </div>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                    <div class="collapse" id="catalogCollapse">
                        <div class="list-group list-group-flush">
                            @foreach (get_all_category() as $item)
                                <a href="{{ route('catalog_category_page', $item->url) }}" class="list-group-item list-group-item-action ps-5">
                                    <i class="fas fa-tv me-2 text-muted"></i>
                                    {{ $item->name }}
                                    @php
                                        $productCount = $item->products ? $item->products->count() : 0;
                                    @endphp
                                    <span class="badge bg-primary-subtle text-primary ms-2">{{ $productCount }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Заказать звонок -->
                <a href="{{ route('kontaktna_informatsiia') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                    <i class="fas fa-phone me-3 text-primary"></i>
                    <span class="fw-semibold">Заказать звонок</span>
                </a>
            </div>

            <!-- Дополнительная информация -->
            <div class="p-3 bg-light border-top">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('oplata_i_dostavka') }}" class="text-decoration-none d-block text-center">
                            <div class="p-3 bg-white rounded">
                                <i class="fas fa-truck text-primary mb-2"></i>
                                <div class="small fw-semibold">Доставка</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('obmin_ta_povernennia') }}" class="text-decoration-none d-block text-center">
                            <div class="p-3 bg-white rounded">
                                <i class="fas fa-shield-alt text-primary mb-2"></i>
                                <div class="small fw-semibold">Гарантия</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Боковое меню каталога (десктоп) -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="catalogMenu" aria-labelledby="catalogMenuLabel">
        <div class="offcanvas-header bg-primary text-white">
            <h5 class="offcanvas-title" id="catalogMenuLabel">
                <i class="fas fa-th-large me-2"></i>Каталог товаров
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">

            <!-- Список категорий -->
            <div class="list-group list-group-flush" id="catalogList">
                @foreach (get_all_category() as $item)
                    <a href="{{ route('catalog_category_page', $item->url) }}" class="list-group-item list-group-item-action d-flex align-items-center justify-content-between catalog-item">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-tv me-3 text-primary"></i>
                            <span class="fw-semibold">{{ $item->name }}</span>
                        </div>
                        @php
                            $productCount = $item->products ? $item->products->count() : 0;
                        @endphp
                        <span class="badge bg-primary-subtle text-primary">{{ $productCount }}</span>
                    </a>
                @endforeach
            </div>

            <!-- Дополнительная информация -->
            {{-- <div class="p-3 bg-light border-top">
                <div class="row g-2">
                    <div class="col-6">
                        <a href="{{ route('search.index') }}?promo=1" class="text-decoration-none d-block text-center">
                            <div class="p-2 bg-white rounded">
                                <i class="fas fa-tag text-primary mb-1"></i>
                                <div class="small fw-semibold">Акции</div>
                            </div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('search.index') }}?new=1" class="text-decoration-none d-block text-center">
                            <div class="p-2 bg-white rounded">
                                <i class="fas fa-star text-primary mb-1"></i>
                                <div class="small fw-semibold">Новинки</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>

    <!-- Мини-корзина -->
    <mini-cart></mini-cart>
    
    <!-- Панель избранного -->
    <wishlist-panel></wishlist-panel>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Поиск в каталоге
    const catalogSearch = document.getElementById('catalogSearch');
    const catalogList = document.getElementById('catalogList');
    const catalogItems = catalogList.querySelectorAll('.catalog-item');
    
    if (catalogSearch && catalogItems.length > 0) {
        catalogSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            catalogItems.forEach(item => {
                const itemText = item.textContent.toLowerCase();
                const itemElement = item.closest('.list-group-item');
                
                if (searchTerm === '' || itemText.includes(searchTerm)) {
                    itemElement.style.display = 'flex';
                } else {
                    itemElement.style.display = 'none';
                }
            });
        });
    }
});
</script>

<style>
/* Минимальные стили для улучшения UX */
.dropdown-item:hover {
    background-color: var(--bs-primary-bg-subtle);
    color: var(--bs-primary);
}

.list-group-item-action:hover {
    background-color: var(--bs-primary-bg-subtle);
    color: var(--bs-primary);
}

/* Боковое меню каталога */
#catalogMenu {
    width: 400px !important;
}

#catalogMenu .offcanvas-body {
    max-height: calc(100vh - 60px);
    overflow-y: auto;
}

.catalog-item {
    transition: all 0.2s ease;
}

.catalog-item:hover {
    background-color: var(--bs-primary-bg-subtle) !important;
    color: var(--bs-primary) !important;
    transform: translateX(5px);
}

/* Стилизация скроллбара для каталога */
#catalogMenu .offcanvas-body::-webkit-scrollbar {
    width: 6px;
}

#catalogMenu .offcanvas-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

#catalogMenu .offcanvas-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

#catalogMenu .offcanvas-body::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Для Firefox */
#catalogMenu .offcanvas-body {
    scrollbar-width: thin;
    scrollbar-color: #c1c1c1 #f1f1f1;
}

.offcanvas {
    width: 320px !important;
}

/* Адаптивность */
@media (max-width: 576px) {
    .offcanvas {
        width: 100% !important;
    }
    
    #catalogMenu {
        width: 100% !important;
    }
}

@media (min-width: 992px) {
    #catalogMenu {
        width: 450px !important;
    }
}

@media (min-width: 1200px) {
    #catalogMenu {
        width: 500px !important;
    }
}
</style>