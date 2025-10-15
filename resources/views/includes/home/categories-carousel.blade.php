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
                        // Получаем изображение для категории
                        $categoryImage = null;
                        
                        // 1. Сначала проверяем, есть ли товары в самой категории
                        $latestProduct = $category->products()->latest()->first();
                        if ($latestProduct && $latestProduct->image_path) {
                            $categoryImage = $latestProduct->image_path;
                        }
                        
                        // 2. Если в категории нет товаров, ищем в дочерних категориях
                        if (!$categoryImage) {
                            $childCategories = $category->childCategories()->where('is_active', true)->get();
                            foreach ($childCategories as $childCategory) {
                                $childProduct = $childCategory->products()->latest()->first();
                                if ($childProduct && $childProduct->image_path) {
                                    $categoryImage = $childProduct->image_path;
                                    break; // Нашли изображение, выходим из цикла
                                }
                            }
                        }
                        
                        // 3. Если все еще нет изображения, используем placeholder
                        if (!$categoryImage) {
                            $categoryImage = 'https://via.placeholder.com/150/e2e8f0/64748b?text=' . mb_substr($category->name, 0, 1);
                        }
                        
                        // Подсчитываем общее количество товаров (включая дочерние категории)
                        $totalProductCount = $category->products()->count();
                        foreach ($category->childCategories()->where('is_active', true)->get() as $childCategory) {
                            $totalProductCount += $childCategory->products()->count();
                        }
                    @endphp
                    
                    <a href="{{ route('catalog_category_page', $category->url) }}" class="category-carousel-item">
                        <div class="category-image-wrapper">
                            @if($categoryImage && !str_contains($categoryImage, 'placeholder'))
                                <img src="{{ $categoryImage }}" 
                                 alt="{{ $category->name }}" 
                                 class="category-image"
                                 loading="lazy">
                            @else
                                <div class="category-image-placeholder">
                                    <i class="fas fa-folder-open fa-2x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="category-name">{{ $category->name }}</div>
                        @if($totalProductCount > 0)
                            <div class="category-count">{{ $totalProductCount }} {{ $totalProductCount == 1 ? 'товар' : 'товаров' }}</div>
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

