<!-- Лента категорий -->
<section class="categories-carousel-section py-4 bg-white">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-2">Категории товаров</h2>
                <p class="text-muted mb-0">Выберите интересующую категорию</p>
            </div>
        </div>
        
        <div class="categories-carousel-wrapper">
            <div class="categories-carousel" id="categoriesCarousel">
                @php
                    // Получаем только родительские категории (сортировка уже в функции)
                    $categories = get_all_category()->whereNull('parent_id');
                @endphp
                
                @foreach ($categories as $category)
                    @php
                        // Получаем последний товар из категории для изображения
                        $latestProduct = $category->products()->latest()->first();
                        $categoryImage = $latestProduct && $latestProduct->image_path 
                            ? $latestProduct->image_path 
                            : 'https://via.placeholder.com/150/e2e8f0/64748b?text=' . mb_substr($category->name, 0, 1);
                        $productCount = $category->products()->count();
                    @endphp
                    
                    <a href="{{ route('catalog_category_page', $category->url) }}" class="category-carousel-item">
                        <div class="category-image-wrapper">
                            <img src="{{ $categoryImage }}" 
                                 alt="{{ $category->name }}" 
                                 class="category-image"
                                 loading="lazy">
                        </div>
                        <div class="category-name">{{ $category->name }}</div>
                        @if($productCount > 0)
                            <div class="category-count">{{ $productCount }} товаров</div>
                        @endif
                    </a>
                @endforeach
            </div>
            
            <!-- Кнопки навигации -->
            <button class="carousel-nav carousel-nav-prev" id="carouselPrev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carousel-nav carousel-nav-next" id="carouselNext">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

