<div class="categories-sidebar">
    <div class="categories-list">
        @foreach (get_all_category() as $categoryItem)
            <div class="category-item">
                <a href="{{ route('catalog_category_page', $categoryItem->url) }}" 
                   class="category-link {{ isset($currentCategory) && $currentCategory->id == $categoryItem->id ? 'active' : '' }}">
                    <div class="category-info">
                        <span class="category-name">{{ $categoryItem->name }}</span>
                        <span class="category-count">{{ $categoryItem->products()->count() }}</span>
                    </div>
                    <i class="fas fa-chevron-right category-arrow"></i>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Быстрые ссылки -->
    <div class="quick-links">
        <h6 class="quick-links-title">
            <i class="fas fa-star me-2"></i>Популярное
        </h6>
                 <div class="quick-links-list">
             <a href="{{ route('home') }}" class="quick-link">
                 <i class="fas fa-home me-2"></i>Все товары
             </a>
             <a href="{{ route('home') }}?sort=new" class="quick-link">
                 <i class="fas fa-fire me-2"></i>Новинки
             </a>
             <a href="{{ route('home') }}?sort=discount" class="quick-link">
                 <i class="fas fa-tag me-2"></i>Со скидкой
             </a>
         </div>
    </div>
</div>