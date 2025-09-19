<template>
    <div class="search-component position-relative">
        <!-- Поле поиска -->
        <div class="search-input-wrapper">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" 
                       v-model="query" 
                       @input="searchProducts" 
                       @focus="showDropdown = true"
                       @keyup.enter="goToSearchResults"
                       class="form-control border-start-0 search-input" 
                       placeholder="Поиск товаров..."
                       autocomplete="off">
                <button v-if="query.length > 0" 
                        @click="clearSearch" 
                        class="btn btn-outline-secondary border-start-0"
                        type="button">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Выпадающий список результатов -->
        <div v-if="showDropdown && suggestions.length > 0" 
             class="search-dropdown">
            <div class="dropdown-header">
                <h6 class="mb-0 text-muted">
                    <i class="fas fa-search me-2"></i>Результаты поиска
                </h6>
            </div>
            
            <div class="search-results">
                <div v-for="product in suggestions" :key="product.id" 
                     class="search-result-item" @click="selectProduct(product)">
                    
                    <!-- Изображение товара -->
                    <div class="product-image-container">
                        <img v-if="product.image_path" 
                             :src="product.image_path" 
                             :alt="product.name"
                             class="product-image" />
                        <div v-else class="no-image-placeholder">
                            <i class="fas fa-image text-muted"></i>
                        </div>
                    </div>

                    <!-- Информация о товаре -->
                    <div class="product-info">
                        <div class="product-header">
                            <h6 class="product-name mb-1">{{ product.name }}</h6>
                            <div v-if="product.discount > 0" class="discount-badge">
                                -{{ product.discount }}%
                            </div>
                        </div>
                        
                        <div class="product-price">
                            <span class="price-current">
                                {{ formatPrice(finalPrice(product)) }} ₴
                            </span>
                            <span v-if="product.discount > 0" class="price-old">
                                {{ formatPrice(product.price) }} ₴
                            </span>
                        </div>
                    </div>

                    <!-- Кнопка корзины -->
                    <div class="product-actions">
                        <cart-button :id="product.id" 
                                   :name="product.name" 
                                   :price="finalPrice(product)"
                                   :image="product.image_path"
                                   :articule="product.articule || 'Не указан'"
                                   :availability="product.availability || 1" />
                    </div>
                </div>
            </div>

            <!-- Кнопка "Показать все результаты" -->
            <div class="dropdown-footer">
                <button @click="goToSearchResults" class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-search me-2"></i>Показать все результаты
                </button>
            </div>
        </div>

        <!-- Сообщение об отсутствии результатов -->
        <div v-if="showDropdown && query.length >= 2 && suggestions.length === 0" 
             class="search-dropdown no-results">
            <div class="text-center py-4">
                <i class="fas fa-search fa-2x text-muted mb-3"></i>
                <h6 class="text-muted">Ничего не найдено</h6>
                <p class="text-muted small mb-0">Попробуйте изменить запрос</p>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import CartButton from './CartButton.vue';

export default {
    name: "Search",
    components: {
        CartButton
    },
    data() {
        return {
            query: '',
            suggestions: [],
            showDropdown: false,
            searchTimeout: null,
            isLoading: false,
        };
    },
    methods: {
        searchProducts() {
            if (this.query.length < 2) {
                this.suggestions = [];
                this.showDropdown = false;
                return;
            }

            // Очищаем предыдущий таймаут
            if (this.searchTimeout) {
                clearTimeout(this.searchTimeout);
            }

            // Устанавливаем новый таймаут для debounce
            this.searchTimeout = setTimeout(() => {
                this.performSearch();
            }, 300);
        },
        
        performSearch() {
            this.isLoading = true;
            
            axios
                .get('/api/products/search?q=' + encodeURIComponent(this.query))
                .then((response) => {
                    this.suggestions = response.data;
                    this.showDropdown = this.suggestions.length > 0 || this.query.length >= 2;
                    this.isLoading = false;
                })
                .catch((error) => {
                    console.error('Ошибка поиска:', error);
                    this.suggestions = [];
                    this.showDropdown = false;
                    this.isLoading = false;
                });
        },
        selectProduct(product) {
            window.location.href = '/catalog/' + product.url;
        },
        clearSearch() {
            this.query = '';
            this.suggestions = [];
            this.showDropdown = false;
        },
        closeDropdown() {
            this.showDropdown = false;
        },
        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.closeDropdown();
            }
        },
        goToSearchResults() {
            if (this.query.trim().length > 0) {
                window.location.href = '/search?q=' + encodeURIComponent(this.query.trim());
            }
        },
        finalPrice(product) {
            return product.discount > 0 
                ? product.price - (product.price * product.discount) / 100 
                : product.price;
        },
        formatPrice(price) {
            return new Intl.NumberFormat('uk-UA').format(price);
        },
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
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

.search-component {
    width: 100%;
}

.search-input-wrapper {
    position: relative;
}

.search-input {
    border-radius: 0 8px 8px 0;
    border: 2px solid var(--border-color);
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
    outline: none;
}

.input-group-text {
    border: 2px solid var(--border-color);
    border-radius: 8px 0 0 8px;
    border-right: none;
    background: white;
    color: var(--gray-color);
}

.input-group-text i {
    font-size: 1rem;
}

.btn-outline-secondary {
    border: 2px solid var(--border-color);
    border-left: none;
    border-radius: 0 8px 8px 0;
    color: var(--gray-color);
    transition: all 0.3s ease;
}

.btn-outline-secondary:hover {
    background: var(--gray-color);
    border-color: var(--gray-color);
    color: white;
}

/* Выпадающий список */
.search-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border-radius: 16px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border-color);
    z-index: 1000;
    margin-top: 0.5rem;
    overflow: hidden;
    max-height: 500px;
    overflow-y: auto;
}

.dropdown-header {
    background: var(--light-color);
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.dropdown-header h6 {
    font-size: 0.875rem;
    font-weight: 600;
}

/* Результаты поиска */
.search-results {
    max-height: 400px;
    overflow-y: auto;
}

.search-result-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-result-item:last-child {
    border-bottom: none;
}

.search-result-item:hover {
    background: var(--light-color);
}

/* Изображение товара */
.product-image-container {
    flex-shrink: 0;
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid var(--border-color);
}

.product-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light-color);
    color: var(--gray-color);
}

/* Информация о товаре */
.product-info {
    flex: 1;
    min-width: 0;
}

.product-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.product-name {
    font-weight: 600;
    color: var(--dark-color);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: 0.9rem;
    margin: 0;
}

.discount-badge {
    background: var(--danger-color);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    flex-shrink: 0;
}

.product-price {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.price-current {
    font-size: 1rem;
    font-weight: 700;
    color: var(--success-color);
}

.price-old {
    font-size: 0.875rem;
    color: var(--gray-color);
    text-decoration: line-through;
}

/* Кнопки действий */
.product-actions {
    flex-shrink: 0;
}

/* Футер выпадающего списка */
.dropdown-footer {
    padding: 1rem 1.5rem;
    background: var(--light-color);
    border-top: 1px solid var(--border-color);
}

.dropdown-footer .btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.75rem;
}

/* Сообщение об отсутствии результатов */
.no-results {
    padding: 0;
}

.no-results .text-center {
    padding: 2rem 1.5rem;
}

/* Скроллбар для выпадающего списка */
.search-results::-webkit-scrollbar {
    width: 6px;
}

.search-results::-webkit-scrollbar-track {
    background: var(--light-color);
}

.search-results::-webkit-scrollbar-thumb {
    background: var(--gray-color);
    border-radius: 3px;
}

.search-results::-webkit-scrollbar-thumb:hover {
    background: var(--primary-color);
}

/* Адаптивность */
@media (max-width: 768px) {
    .search-dropdown {
        position: fixed;
        top: auto;
        bottom: 0;
        left: 0;
        right: 0;
        margin: 0;
        border-radius: 16px 16px 0 0;
        max-height: 70vh;
    }
    
    .search-result-item {
        padding: 1rem;
    }
    
    .dropdown-header,
    .dropdown-footer {
        padding: 1rem;
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
