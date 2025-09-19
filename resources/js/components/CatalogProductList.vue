<template>
    <div>
        <!-- Панель сортировки -->
        <div class="sort-panel mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <label for="sort-select" class="fw-semibold text-muted">Сортировать:</label>
                    <select v-model="selectedSort" id="sort-select" class="form-select sort-select">
                        <option value="">По умолчанию</option>
                        <option value="price_asc">От дешевых к дорогим</option>
                        <option value="price_desc">От дорогих к дешевым</option>
                        <option value="discount">По размеру скидки</option>
                        <option value="name_asc">По названию (А-Я)</option>
                        <option value="name_desc">По названию (Я-А)</option>
                    </select>
                </div>

                <div class="view-toggle">
                    <button class="view-btn" :class="{ active: view === 'grid' }" @click="setView('grid')" title="Вид сеткой">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-btn" :class="{ active: view === 'list' }" @click="setView('list')" title="Вид списком">
                        <i class="fas fa-list"></i>
                    </button>
                    <button class="view-btn" :class="{ active: view === 'compact' }" @click="setView('compact')" title="Компактный вид">
                        <i class="fas fa-th-large"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Список товаров -->
        <div class="products-container" :class="viewClass">
            <div v-for="product in sortedProducts" :key="product.id" class="product-card">
                <!-- Бейдж скидки -->
                <div v-if="product.discount > 0" class="discount-badge">
                    <i class="fas fa-tag me-1"></i>-{{ product.discount }}%
                </div>
                
                <!-- Индикатор наличия -->
                <div v-if="product.availability === 2" class="availability-badge">
                    <i class="fas fa-times-circle me-1"></i>Нет в наличии
                </div>

                <!-- Кнопка избранного -->
                <button class="wishlist-btn" title="Добавить в избранное" @click="toggleWishlist(product.id)">
                    <i :class="isInWishlist(product.id) ? 'fas fa-heart text-danger' : 'far fa-heart'"></i>
                </button>

                <!-- Изображение товара -->
                <div class="product-image-container">
                    <a :href="`/catalog/${product.url}`" class="product-link">
                        <img v-if="product.image_path" 
                             :src="product.image_path" 
                             :alt="product.name" 
                             loading="lazy" 
                             class="product-image" />
                        <div v-else class="no-image-placeholder">
                            <i class="fas fa-image fa-2x text-muted"></i>
                            <small class="text-muted d-block mt-2">Нет фото</small>
                        </div>
                    </a>
                </div>

                <!-- Информация о товаре -->
                <div class="product-info">
                    <h5 class="product-title">
                        <a :href="`/catalog/${product.url}`" class="text-decoration-none">
                            {{ product.name }}
                        </a>
                    </h5>
                    
                    <!-- Рейтинг (заглушка) -->
                    <div class="product-rating mb-2">
                        <div class="d-flex align-items-center gap-1">
                            <div class="stars">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star-half-alt text-warning"></i>
                            </div>
                            <small class="text-muted">(4.5)</small>
                        </div>
                    </div>

                    <!-- Цена -->
                    <div class="product-price mb-3">
                        <span class="price-current">{{ formatPrice(finalPrice(product)) }} ₴</span>
                        <span v-if="product.discount > 0" class="price-old">{{ formatPrice(product.price) }} ₴</span>
                    </div>

                    <!-- Кнопка корзины -->
                    <div class="product-actions">
                        <cart-button :id="product.id" 
                                   :name="product.name" 
                                   :price="finalPrice(product)"
                                   :image="product.image_path"
                                   :articule="product.articule || 'Не указан'"
                                   :availability="product.availability" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Сообщение об отсутствии товаров -->
        <div v-if="sortedProducts.length === 0" class="no-products text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Товары не найдены</h5>
            <p class="text-muted">Попробуйте изменить параметры поиска или фильтры</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        products: Array,
        pagination: Object,
    },
    data() {
        return {
            selectedSort: '',
            view: localStorage.getItem('productView') || 'grid',
            wishlist: JSON.parse(localStorage.getItem('wishlist') || '[]'),
        };
    },
    computed: {
        sortedProducts() {
            let sorted = [...this.products];
            
            switch (this.selectedSort) {
                case 'price_asc':
                    sorted.sort((a, b) => this.finalPrice(a) - this.finalPrice(b));
                    break;
                case 'price_desc':
                    sorted.sort((a, b) => this.finalPrice(b) - this.finalPrice(a));
                    break;
                case 'discount':
                    sorted = sorted.filter(p => p.discount > 0).sort((a, b) => b.discount - a.discount);
                    break;
                case 'name_asc':
                    sorted.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'name_desc':
                    sorted.sort((a, b) => b.name.localeCompare(a.name));
                    break;
            }
            
            return sorted;
        },
        viewClass() {
            return {
                'grid-view': this.view === 'grid',
                'list-view': this.view === 'list',
                'compact-view': this.view === 'compact',
            };
        },
    },
    methods: {
        finalPrice(product) {
            return product.discount > 0 
                ? product.price - (product.price * product.discount) / 100 
                : product.price;
        },
        formatPrice(price) {
            return new Intl.NumberFormat('uk-UA').format(price);
        },
        setView(viewType) {
            this.view = viewType;
            localStorage.setItem('productView', viewType);
        },
        toggleWishlist(productId) {
            const index = this.wishlist.findIndex(item => item.id === productId);
            if (index > -1) {
                // Убираем из избранного
                this.wishlist.splice(index, 1);
                
                // Показываем уведомление через тостер
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Убрано из избранного',
                        message: 'Товар убран из вашего избранного',
                        type: 'info',
                        duration: 3000
                    }
                }));
            } else {
                // Находим товар для добавления в избранное
                const product = this.products.find(p => p.id === productId);
                if (product) {
                    // Добавляем в избранное полные данные товара
                    const wishlistItem = {
                        id: productId,
                        name: product.name,
                        price: this.finalPrice(product),
                        image: product.image_path || '',
                        discount: product.discount || 0
                    };
                    
                    this.wishlist.push(wishlistItem);
                    
                    // Показываем уведомление через тостер
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            title: 'Добавлено в избранное!',
                            message: 'Товар добавлен в ваше избранное',
                            type: 'success',
                            duration: 3000
                        }
                    }));
                }
            }
            localStorage.setItem('wishlist', JSON.stringify(this.wishlist));
            
            // Отправляем событие обновления избранного
            window.dispatchEvent(new Event('wishlist-updated'));
        },
        isInWishlist(productId) {
            return this.wishlist.some(item => item.id === productId);
        },
    },
};
</script>

<style scoped>
/* CSS переменные для совместимости */
:root {
    --primary-color: #2563eb;
    --primary-hover: #1d4ed8;
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
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}

.sort-panel {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--border-color);
}

.sort-select {
    border: 2px solid var(--border-color);
    border-radius: 8px;
    padding: 0.5rem 1rem;
    min-width: 200px;
    transition: all 0.3s ease;
}

.sort-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
}

.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    padding: 0.75rem;
    border: 2px solid var(--border-color);
    background: white;
    color: var(--gray-color);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.view-btn:hover,
.view-btn.active {
    border-color: var(--primary-color);
    color: var(--primary-color);
    background: rgba(37, 99, 235, 0.1);
    transform: translateY(-1px);
}

/* Сетка товаров */
.products-container {
    display: grid;
    gap: 1.5rem;
}

.grid-view {
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
}

.list-view {
    grid-template-columns: 1fr;
}

.compact-view {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
}

/* Карточка товара */
.product-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

/* Изображение */
.product-image-container {
    position: relative;
    overflow: hidden;
}

.product-image {
    width: 100%;
    aspect-ratio: 1 / 1;
    object-fit: cover;
    transition: all 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.no-image-placeholder {
    width: 100%;
    aspect-ratio: 1 / 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: var(--light-color);
    color: var(--gray-color);
}

/* Бейджи */
.discount-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: linear-gradient(135deg, var(--danger-color), #dc2626);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    z-index: 2;
    box-shadow: var(--shadow-sm);
}

.availability-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: var(--danger-color);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    z-index: 2;
    box-shadow: var(--shadow-sm);
}

.wishlist-btn {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: white;
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-color);
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: var(--shadow-sm);
    z-index: 2;
}

/* Если есть индикатор наличия, кнопка избранного сдвигается вправо */
.product-card:has(.availability-badge) .wishlist-btn {
    left: 8rem;
}

.wishlist-btn:hover {
    color: var(--danger-color);
    transform: scale(1.1);
    box-shadow: var(--shadow-md);
}

/* Информация о товаре */
.product-info {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.product-title {
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--dark-color);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: 1rem;
}

.product-title a {
    color: inherit;
    text-decoration: none;
}

.product-title a:hover {
    color: var(--primary-color);
}

.product-rating {
    font-size: 0.875rem;
}

.stars {
    display: flex;
    gap: 2px;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
    margin-top: auto;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.price-current {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--success-color);
}

.price-old {
    font-size: 1rem;
    color: var(--gray-color);
    text-decoration: line-through;
}

.product-actions {
    margin-top: 1rem;
}

/* Специальные стили для разных видов */
.list-view .product-card {
    flex-direction: row;
    align-items: center;
}

.list-view .product-image-container {
    flex-shrink: 0;
    width: 200px;
}

.list-view .product-info {
    flex: 1;
    border-top: none;
    padding-top: 0;
}

.list-view .product-price {
    border-top: none;
    padding-top: 0;
}

.compact-view .product-info {
    padding: 1rem;
}

.compact-view .product-title {
    font-size: 0.9rem;
    margin-bottom: 0.75rem;
}

.compact-view .product-price {
    padding-top: 0.75rem;
}

.compact-view .price-current {
    font-size: 1.1rem;
}

/* Сообщение об отсутствии товаров */
.no-products {
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-sm);
}

/* Адаптивность */
@media (max-width: 768px) {
    .sort-panel {
        padding: 1rem;
    }
    
    .sort-select {
        min-width: 150px;
    }
    
    .grid-view {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.75rem;
    }
    
    .compact-view {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }
    
    .list-view .product-image-container {
        width: 150px;
    }
}

@media (max-width: 576px) {
    .sort-panel .d-flex {
        flex-direction: column;
        align-items: stretch;
        gap: 1rem;
    }
    
    .grid-view {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    }
    
    .compact-view {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    }
}

/* CSS переменные для совместимости */
:root {
    --primary-color: #2563eb;
    --secondary-color: #f59e0b;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --warning-color: #f59e0b;
    --info-color: #3b82f6;
    --light-color: #f8fafc;
    --dark-color: #1e293b;
    --gray-color: #64748b;
    --border-color: #e2e8f0;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}
</style>
