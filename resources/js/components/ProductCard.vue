<template>
    <div class="product-card" :class="cardClass">
        <!-- Бейдж скидки -->
        <div v-if="product.discount > 0" class="discount-badge">
            <i class="fas fa-tag me-1"></i>-{{ product.discount }}%
        </div>
        
        <!-- Индикатор наличия -->
        <div v-if="product.availability === 2" class="availability-badge">
            <i class="fas fa-times-circle me-1"></i>Нет в наличии
        </div>
        
        <!-- Кнопка избранного -->
        <button class="wishlist-btn" 
                title="Добавить в избранное" 
                @click="toggleWishlist(product.id)"
                :class="{ 'in-wishlist': isInWishlist(product.id) }">
            <i :class="isInWishlist(product.id) ? 'fas fa-heart' : 'far fa-heart'"></i>
        </button>

        <!-- Изображение товара с быстрым просмотром -->
        <div class="product-image-container" @mouseenter="showQuickView = true" @mouseleave="showQuickView = false">
            <a :href="`/catalog/${product.url}`" class="product-link" :title="product.name">
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
            
            <!-- Быстрый просмотр при наведении -->
            <div class="quick-view-overlay" v-show="showQuickView">
                <button class="quick-view-btn" @click="quickView(product)" title="Быстрый просмотр">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

        <!-- Окно быстрого просмотра (вынесено за пределы изображения) -->
        <div class="quick-view-popup" v-show="showQuickView">
            <div class="popup-content">
                <div class="popup-header">
                    <h6 class="popup-title">{{ product.name }}</h6>
                    <div class="popup-articule">Артикул: {{ product.articule }}</div>
                </div>
                <div class="popup-body">
                    <div class="popup-price">
                        <span class="current-price">{{ formatPrice(finalPrice(product)) }} ₴</span>
                        <span v-if="product.discount > 0" class="old-price">{{ formatPrice(product.price) }} ₴</span>
                    </div>
                    <div class="popup-description" v-if="product.description">
                        {{ product.description }}
                    </div>
                    <div class="popup-features" v-if="product.features">
                        <div class="feature-item" v-for="(value, key) in product.features" :key="key">
                            <strong>{{ key }}:</strong> {{ value }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Информация о товаре -->
        <div class="product-info">
            <h5 class="product-title">
                <a :href="`/catalog/${product.url}`" class="text-decoration-none">
                    {{ product.name }}
                </a>
            </h5>
            
            <!-- Рейтинг -->
            <div class="product-rating mb-2" v-if="showRating">
                <div class="d-flex align-items-center gap-1">
                    <div class="stars">
                        <i v-for="star in 5" :key="star" 
                           :class="getStarClass(star, product.rating || 4.5)"
                           class="star"></i>
                    </div>
                    <small class="text-muted">({{ product.rating || 4.5 }})</small>
                </div>
            </div>

            <!-- Цена -->
            <div class="product-price mb-3">
                <span class="price-current">{{ formatPrice(finalPrice(product)) }} ₴</span>
                <span v-if="product.discount > 0" class="price-old">{{ formatPrice(product.price) }} ₴</span>
            </div>

            <!-- Кнопки действий -->
            <div class="product-actions">
                <cart-button :id="product.id" 
                           :name="product.name" 
                           :price="finalPrice(product)"
                           :image="product.image_path"
                           :availability="product.availability" />
            </div>
        </div>

        <!-- Дополнительная информация -->
        <div v-if="showAdditionalInfo" class="product-additional-info">
            <div class="info-item">
                <i class="fas fa-truck text-primary me-2"></i>
                <small class="text-muted">Бесплатная доставка</small>
            </div>
            <div class="info-item">
                <i class="fas fa-shield-alt text-success me-2"></i>
                <small class="text-muted">Гарантия 2 года</small>
            </div>
        </div>
    </div>
</template>

<script>
import CartButton from './CartButton.vue';

export default {
    name: 'ProductCard',
    components: {
        CartButton
    },
    props: {
        // Убираем все props, будем читать из атрибутов
    },
    data() {
        return {
            showQuickView: false,
            product: {
                id: null,
                name: '',
                price: 0,
                discount: 0,
                image_path: '',
                url: '',
                articule: '',
                rating: 4.5,
                availability: 1,
                description: '',
                features: {}
            },
            variant: 'default',
            showRating: true,
            showCompare: false, // Убираем сравнение
            showAdditionalInfo: false
        };
    },
    computed: {
        cardClass() {
            return {
                'product-card': true,
                [`variant-${this.variant}`]: true,
                'has-discount': this.product.discount > 0,
                'out-of-stock': this.product.availability === 2
            };
        }
    },
    mounted() {
        // Читаем данные из атрибутов
        this.product.id = parseInt(this.$el.getAttribute('id')) || null;
        this.product.name = this.$el.getAttribute('name') || '';
        this.product.price = parseFloat(this.$el.getAttribute('price')) || 0;
        this.product.discount = parseFloat(this.$el.getAttribute('discount')) || 0;
        this.product.image_path = this.$el.getAttribute('image_path') || '';
        this.product.url = this.$el.getAttribute('url') || '';
        this.product.articule = this.$el.getAttribute('articule') || '';
        this.product.rating = parseFloat(this.$el.getAttribute('rating')) || 4.5;
        this.product.availability = parseInt(this.$el.getAttribute('availability')) || 1;
        this.product.description = this.$el.getAttribute('description') || '';
        
        // Читаем остальные атрибуты
        this.variant = this.$el.getAttribute('variant') || 'default';
        this.showRating = this.$el.getAttribute('show-rating') === 'true';
        this.showCompare = false; // Всегда false
        this.showAdditionalInfo = this.$el.getAttribute('show-additional-info') === 'true';
    },
    methods: {
        finalPrice(product) {
            if (product.discount > 0) {
                return product.price * (1 - product.discount / 100);
            }
            return product.price;
        },
        formatPrice(price) {
            return new Intl.NumberFormat('uk-UA').format(Math.round(price));
        },
        getStarClass(starNumber, rating) {
            if (starNumber <= rating) {
                return 'fas fa-star text-warning';
            } else if (starNumber - rating < 1) {
                return 'fas fa-star-half-alt text-warning';
            } else {
                return 'far fa-star text-muted';
            }
        },
        toggleWishlist(productId) {
            let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
            const existingIndex = wishlist.findIndex(item => item.id === productId);
            
            if (existingIndex > -1) {
                wishlist.splice(existingIndex, 1);
            } else {
                wishlist.push({
                    id: productId,
                    name: this.product.name,
                    price: this.product.price,
                    image: this.product.image_path
                });
            }
            
            localStorage.setItem('wishlist', JSON.stringify(wishlist));
            window.dispatchEvent(new Event('wishlist-updated'));
        },
        isInWishlist(productId) {
            const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
            return wishlist.some(item => item.id === productId);
        },
        quickView(product) {
            // Быстрый просмотр товара
            console.log('Быстрый просмотр:', product);
        }
    }
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

.product-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.product-image-container {
    position: relative;
    padding: 1.5rem;
    background: #f8fafc;
    min-height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image {
    max-width: 100%;
    max-height: 180px;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image {
    transform: scale(1.05);
}

.quick-view-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 15; /* Меньше чем у кнопки избранного */
}

.product-image-container:hover .quick-view-overlay {
    opacity: 1;
}

.quick-view-btn {
    background: white;
    color: #2563eb;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.quick-view-btn:hover {
    background: #2563eb;
    color: white;
    transform: scale(1.05);
}

.quick-view-popup {
    position: fixed; /* Меняем на fixed чтобы попап не скрывался в карточке */
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    width: 280px;
    z-index: 1000; /* Очень высокий z-index для попапа */
    border: 1px solid #e2e8f0;
    pointer-events: none; /* Чтобы не блокировать взаимодействие с карточкой */
}

.quick-view-popup .popup-content {
    pointer-events: auto; /* Восстанавливаем взаимодействие для содержимого */
}

.popup-content {
    padding: 1rem;
}

.popup-header {
    border-bottom: 1px solid #e2e8f0;
    padding-bottom: 0.75rem;
    margin-bottom: 0.75rem;
}

.popup-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.25rem 0;
    line-height: 1.3;
}

.popup-articule {
    font-size: 0.75rem;
    color: #64748b;
}

.popup-body {
    font-size: 0.85rem;
}

.popup-price {
    margin-bottom: 0.75rem;
}

.popup-price .current-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #10b981;
    margin-right: 0.5rem;
}

.popup-price .old-price {
    font-size: 0.9rem;
    color: #64748b;
    text-decoration: line-through;
}

.popup-description {
    color: #475569;
    line-height: 1.4;
    margin-bottom: 0.75rem;
}

.popup-features {
    color: #64748b;
}

.feature-item {
    margin-bottom: 0.25rem;
    font-size: 0.8rem;
}

.discount-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: #ef4444;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 10;
}

.availability-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: #6b7280;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    z-index: 10;
}

.wishlist-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: white;
    border: 1px solid #e2e8f0;
    color: #64748b;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 20; /* Увеличиваем z-index чтобы быть выше быстрого просмотра */
}

.wishlist-btn:hover {
    border-color: #ef4444;
    color: #ef4444;
}

.wishlist-btn.in-wishlist {
    background: #ef4444;
    border-color: #ef4444;
    color: white;
}

.product-info {
    padding: 1.5rem;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.75rem;
    line-height: 1.3;
    height: 2.5rem;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}

.product-title a {
    color: inherit;
    text-decoration: none;
}

.product-title a:hover {
    color: #2563eb;
}

.product-rating {
    margin-bottom: 0.75rem;
}

.stars {
    display: flex;
    gap: 2px;
}

.star {
    font-size: 0.8rem;
}

.product-price {
    margin-bottom: 1rem;
}

.price-current {
    font-size: 1.1rem;
    font-weight: 700;
    color: #10b981;
    margin-right: 0.5rem;
}

.price-old {
    font-size: 0.9rem;
    color: #64748b;
    text-decoration: line-through;
}

.product-actions {
    margin-top: auto;
}

.product-additional-info {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 0.5rem;
    font-size: 0.8rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

/* Адаптивность */
@media (max-width: 768px) {
    .product-image-container {
        padding: 1rem;
        min-height: 160px;
    }
    
    .product-info {
        padding: 1rem;
    }
    
    .quick-view-popup {
        width: 250px;
    }
}

@media (max-width: 576px) {
    .product-card {
        border-radius: 12px;
    }
    
    .product-image-container {
        padding: 0.75rem;
        min-height: 140px;
    }
    
    .product-info {
        padding: 0.75rem;
    }
    
    .quick-view-popup {
        width: 220px;
    }
}
</style>
