<!-- Стрічка категорій -->
<section class="categories-carousel-section py-4 bg-white">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold mb-2">Категорії товарів</h2>
                <p class="text-muted mb-0">Оберіть потрібну категорію</p>
            </div>
        </div>
        
        <div class="categories-carousel-wrapper">
            <div class="categories-carousel" id="categoriesCarousel">
                @php
                    // Отримуємо лише батьківські категорії (сортування вже в функції)
                    $categories = get_all_category()->whereNull('parent_id');
                @endphp
                
                @foreach ($categories as $category)
                    @php
                        // Отримуємо зображення для категорії
                        $categoryImage = null;
                        
                        // 1. Спершу перевіряємо, чи є товари в самій категорії
                        $latestProduct = $category->products()->latest()->first();
                        if ($latestProduct && $latestProduct->image_path) {
                            $categoryImage = $latestProduct->image_path;
                        }
                        
                        // 2. Якщо в категорії немає товарів, шукаємо в дочірніх категоріях
                        if (!$categoryImage) {
                            $childCategories = $category->childCategories()->where('is_active', true)->get();
                            foreach ($childCategories as $childCategory) {
                                $childProduct = $childCategory->products()->latest()->first();
                                if ($childProduct && $childProduct->image_path) {
                                    $categoryImage = $childProduct->image_path;
                                    break; // Знайшли зображення — виходимо з циклу
                                }
                            }
                        }
                        
                        // 3. Якщо зображення все ще немає, використовуємо placeholder
                        if (!$categoryImage) {
                            $categoryImage = 'https://via.placeholder.com/150/e2e8f0/64748b?text=' . mb_substr($category->name, 0, 1);
                        }
                        
                        // Підраховуємо загальну кількість товарів (включно з дочірніми категоріями)
                        $totalProductCount = get_category_total_products($category);
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
                            <div class="category-count">{{ $totalProductCount }} {{ $totalProductCount == 1 ? 'товар' : 'товарів' }}</div>
                        @endif
                    </a>
                @endforeach
            </div>
            
            <!-- Кнопки навігації -->
            <button class="carousel-nav carousel-nav-prev" id="carouselPrev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="carousel-nav carousel-nav-next" id="carouselNext">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

