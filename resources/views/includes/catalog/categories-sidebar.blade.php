<div class="categories-sidebar">
    <!-- Список категорий -->
    <div class="categories-list">
        @foreach (get_all_category()->whereNull('parent_id') as $categoryItem)
            @php
                $isActive = isset($currentCategory) && $currentCategory->id == $categoryItem->id;
                $productCount = $categoryItem->products()->count();
            @endphp
            
            <a href="{{ route('catalog_category_page', $categoryItem->url) }}" 
               class="category-link {{ $isActive ? 'active' : '' }}">
                <span class="category-name">{{ $categoryItem->name }}</span>
                @if($productCount > 0)
                    <span class="category-count">{{ $productCount }}</span>
                @endif
            </a>
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

<style>
    /* Компактный сайдбар категорий */
    .categories-sidebar {
        background: white;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        overflow: hidden;
    }
    
    .sidebar-header {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
        padding: 0.75rem 1rem;
    }
    
    .sidebar-title {
        color: white;
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
    }
    
    .categories-list {
        padding: 0.5rem 0;
    }
    
    /* Простая ссылка категории */
    .category-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.7rem 1rem;
        text-decoration: none;
        color: #64748b;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
        font-size: 0.875rem;
    }
    
    .category-link:hover {
        background: #f8fafc;
        color: #2563eb;
        border-left-color: #cbd5e1;
    }
    
    .category-link.active {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 600;
        border-left-color: #2563eb;
    }
    
    .category-name {
        flex: 1;
    }
    
    .category-count {
        background: #f1f5f9;
        color: #64748b;
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        border-radius: 10px;
        font-weight: 500;
        min-width: 24px;
        text-align: center;
    }
    
    .category-link.active .category-count {
        background: #dbeafe;
        color: #2563eb;
    }
    
    /* Быстрые ссылки */
    .quick-links {
        padding: 0.75rem 0;
        border-top: 1px solid #f1f5f9;
    }
    
    .quick-links-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        padding: 0 1rem;
        margin-bottom: 0.5rem;
    }
    
    .quick-links-list {
        display: flex;
        flex-direction: column;
    }
    
    .quick-link {
        display: flex;
        align-items: center;
        padding: 0.6rem 1rem;
        text-decoration: none;
        color: #64748b;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }
    
    .quick-link:hover {
        background: #f8fafc;
        color: #2563eb;
        border-left-color: #2563eb;
    }
    
    .quick-link i {
        width: 18px;
        text-align: center;
        font-size: 0.75rem;
    }
    
    /* Адаптивность */
    @media (max-width: 991px) {
        .categories-sidebar {
            border-radius: 0;
            border-left: none;
            border-right: none;
        }
    }
</style>