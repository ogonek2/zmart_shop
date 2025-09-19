<template>
  <div class="product-list-section">
    <h4 class="section-title mb-4">
      <i class="fas fa-th-large me-2 text-primary"></i>
      <b>Другие товары</b>
    </h4>

    <!-- Сетка товаров -->
    <div class="products-grid">
      <div v-for="product in products" :key="product.id" class="product-card">
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
          <a :href="`catalog/${product.url}`" class="product-link" :title="product.name">
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
            <a :href="`catalog/${product.url}`" class="text-decoration-none">
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
            <CartButton :id="product.id" 
                       :name="product.name" 
                       :price="finalPrice(product)"
                       :image="product.image_path"
                       :articule="product.articule || 'Не указан'"
                       :availability="product.availability" />
          </div>
        </div>
      </div> 
    </div>

    <!-- Состояние загрузки -->
    <div v-if="loading" class="loading-state text-center py-5">
      <div class="spinner-border text-primary mb-3" role="status">
        <span class="visually-hidden">Загрузка...</span>
      </div>
      <h6 class="text-muted">Загрузка товаров...</h6>
    </div>

    <!-- Сообщение об отсутствии товаров -->
    <div v-if="!loading && products.length === 0" class="no-products text-center py-5">
      <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
      <h5 class="text-muted">Товары не найдены</h5>
      <p class="text-muted">Попробуйте изменить параметры поиска</p>
    </div>

    <!-- Пагинация -->
    <div v-if="totalPages > 1" class="pagination-wrapper">
      <nav>
        <ul class="pagination flex-wrap justify-content-center">
          <!-- Кнопка "Назад" -->
          <li class="page-item" :class="{ disabled: page === 1 }">
            <button class="page-link" @click="changePage(page - 1)" :disabled="page === 1">
              <i class="fas fa-chevron-left me-1"></i>Назад
            </button>
          </li>

          <!-- Первая страница -->
          <li v-if="pages[0] > 1" class="page-item">
            <button class="page-link" @click="changePage(1)">1</button>
          </li>

          <!-- Многоточие слева -->
          <li v-if="pages[0] > 2" class="page-item disabled">
            <span class="page-link">…</span>
          </li>

          <!-- Основные страницы -->
          <li v-for="p in pages" :key="p" class="page-item" :class="{ active: p === page }">
            <button class="page-link" @click="changePage(p)">{{ p }}</button>
          </li>

          <!-- Многоточие справа -->
          <li v-if="pages[pages.length - 1] < totalPages - 1" class="page-item disabled">
            <span class="page-link">…</span>
          </li>

          <!-- Последняя страница -->
          <li v-if="pages[pages.length - 1] < totalPages" class="page-item">
            <button class="page-link" @click="changePage(totalPages)">{{ totalPages }}</button>
          </li>

          <!-- Кнопка "Вперед" -->
          <li class="page-item" :class="{ disabled: page === totalPages }">
            <button class="page-link" @click="changePage(page + 1)" :disabled="page === totalPages">
              Вперед<i class="fas fa-chevron-right ms-1"></i>
            </button>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import CartButton from './CartButton.vue';

export default {
  name: "ProductList",
  components: {
    CartButton
  },
  data() {
    return {
      products: [],
      page: 1,
      totalPages: 1,
      loading: false,
      wishlist: JSON.parse(localStorage.getItem('wishlist') || '[]'),
    };
  },
  computed: {
    pages() {
      const range = [];
      const delta = 1; // количество страниц вокруг текущей
      const start = Math.max(2, this.page - delta);
      const end = Math.min(this.totalPages - 1, this.page + delta);

      for (let i = start; i <= end; i++) {
        range.push(i);
      }

      return range;
    },
  },
  methods: {
    async loadProducts() {
      this.loading = true;
      try {
        const response = await axios.get(`/api/products?page=${this.page}`);
        this.products = response.data.data;
        this.totalPages = response.data.last_page || 1;
      } catch (error) {
        console.error("Ошибка загрузки товаров", error);
      } finally {
        this.loading = false;
      }
    },
    changePage(newPage) {
      if (newPage >= 1 && newPage <= this.totalPages && newPage !== this.page) {
        this.page = newPage;
        this.loadProducts();
        window.scrollTo({ top: 0, behavior: "smooth" });
      }
    },
    finalPrice(product) {
      return product.discount > 0 
        ? product.price - (product.price * product.discount / 100) 
        : product.price;
    },
    formatPrice(price) {
      return new Intl.NumberFormat('uk-UA').format(price);
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
  mounted() {
    this.loadProducts();
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

.product-list-section {
  margin: 2rem 0;
}

.section-title {
  color: var(--dark-color);
  font-size: 1.5rem;
  font-weight: 600;
  border-bottom: 2px solid var(--primary-color);
  padding-bottom: 0.5rem;
  display: inline-block;
}

/* Сетка товаров */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
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
  background: linear-gradient(135deg, var(--warning-color), #f59e0b);
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

/* Состояния */
.loading-state {
  background: white;
  border-radius: 16px;
  box-shadow: var(--shadow-sm);
}

.no-products {
  background: white;
  border-radius: 16px;
  box-shadow: var(--shadow-sm);
}

/* Пагинация */
.pagination-wrapper {
  margin-top: 2rem;
}

.pagination {
  gap: 0.25rem;
}

.pagination .page-link {
  border: none;
  color: var(--primary-color);
  padding: 0.75rem 1rem;
  border-radius: 8px;
  transition: all 0.3s ease;
  font-weight: 500;
}

.pagination .page-link:hover {
  background: var(--primary-color);
  color: white;
  transform: translateY(-1px);
}

.pagination .page-item.active .page-link {
  background: var(--primary-color);
  color: white;
}

.pagination .page-item.disabled .page-link {
  color: var(--gray-color);
  cursor: not-allowed;
}

/* Адаптивность */
@media (max-width: 768px) {
      .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 0.75rem;
    }
  
  .section-title {
    font-size: 1.25rem;
  }
}

@media (max-width: 576px) {
      .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    }
  
  .pagination .page-link {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
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
