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
.product-list-section {
  margin: 2rem 0;
}

.section-title {
  color: #1f2937;
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 1.5rem;
}

/* Современная сетка товаров - компактнее */
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

/* Современная карточка товара - компактная и минималистичная */
.product-card {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid #f1f5f9;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  height: 100%;
  display: flex;
  flex-direction: column;
  position: relative;
}

.product-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
  border-color: #e2e8f0;
}

/* Изображение - компактнее */
.product-image-container {
  position: relative;
  overflow: hidden;
  background: #f8fafc;
}

.product-image {
  width: 100%;
  aspect-ratio: 1 / 1;
  object-fit: cover;
  transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.product-card:hover .product-image {
  transform: scale(1.08);
}

.no-image-placeholder {
  width: 100%;
  aspect-ratio: 1 / 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: #f1f5f9;
  color: #94a3b8;
}

/* Компактные бейджи */
.discount-badge {
  position: absolute;
  top: 0.75rem;
  right: 0.75rem;
  background: #ef4444;
  color: white;
  padding: 0.35rem 0.6rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.8rem;
  z-index: 2;
  box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
}

.availability-badge {
  position: absolute;
  top: 0.75rem;
  left: 0.75rem;
  background: #f59e0b;
  color: white;
  padding: 0.35rem 0.6rem;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.8rem;
  z-index: 2;
  box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}

/* Компактная кнопка избранного */
.wishlist-btn {
  position: absolute;
  top: 0.75rem;
  left: 0.75rem;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(8px);
  border: none;
  border-radius: 50%;
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #64748b;
  cursor: pointer;
  transition: all 0.3s ease;
  z-index: 2;
}

.product-card:has(.availability-badge) .wishlist-btn {
  left: 7rem;
}

.wishlist-btn:hover {
  background: white;
  color: #ef4444;
  transform: scale(1.15);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Компактная информация о товаре */
.product-info {
  padding: 1rem;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.product-title {
  font-weight: 600;
  color: #1e293b;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  font-size: 0.9rem;
  min-height: 2.6em;
}

.product-title a {
  color: inherit;
  text-decoration: none;
  transition: color 0.2s;
}

.product-title a:hover {
  color: #2563eb;
}

/* Убираем рейтинг для компактности */
.product-rating {
  display: none;
}

/* Компактная цена */
.product-price {
  display: flex;
  align-items: baseline;
  gap: 0.5rem;
  margin-top: auto;
}

.price-current {
  font-size: 1.15rem;
  font-weight: 700;
  color: #10b981;
}

.price-old {
  font-size: 0.85rem;
  color: #94a3b8;
  text-decoration: line-through;
}

.product-actions {
  margin-top: 0.75rem;
}

/* Состояния */
.loading-state {
  background: white;
  border-radius: 12px;
  border: 1px solid #f1f5f9;
  padding: 2rem;
}

.no-products {
  background: white;
  border-radius: 12px;
  border: 1px solid #f1f5f9;
  padding: 2rem;
}

/* Современная пагинация */
.pagination-wrapper {
  margin-top: 2rem;
}

.pagination {
  gap: 0.5rem;
}

.pagination .page-link {
  border: 1px solid #e2e8f0;
  color: #475569;
  padding: 0.6rem 1rem;
  border-radius: 8px;
  transition: all 0.2s ease;
  font-weight: 500;
  background: white;
}

.pagination .page-link:hover:not(:disabled) {
  background: #2563eb;
  color: white;
  border-color: #2563eb;
}

.pagination .page-item.active .page-link {
  background: #2563eb;
  color: white;
  border-color: #2563eb;
}

.pagination .page-item.disabled .page-link {
  color: #cbd5e1;
  cursor: not-allowed;
  background: #f8fafc;
  border-color: #f1f5f9;
}

/* Адаптивность */
@media (max-width: 992px) {
  .products-grid {
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
  }
}

@media (max-width: 768px) {
  .products-grid {
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 0.75rem;
  }
  
  .section-title {
    font-size: 1.25rem;
  }
  
  .product-info {
    padding: 0.85rem;
  }
  
  .product-title {
    font-size: 0.85rem;
  }
  
  .price-current {
    font-size: 1.05rem;
  }
}

@media (max-width: 576px) {
  .products-grid {
    grid-template-columns: repeat(auto-fill, minmax(145px, 1fr));
    gap: 0.6rem;
  }
  
  .product-info {
    padding: 0.75rem;
  }
  
  .product-title {
    font-size: 0.8rem;
    min-height: 2.4em;
  }
  
  .price-current {
    font-size: 1rem;
  }
  
  .price-old {
    font-size: 0.75rem;
  }
  
  .discount-badge,
  .availability-badge {
    padding: 0.3rem 0.5rem;
    font-size: 0.7rem;
  }
  
  .wishlist-btn {
    width: 30px;
    height: 30px;
    font-size: 0.85rem;
  }
  
  .pagination .page-link {
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
  }
}

@media (max-width: 480px) {
  .products-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}
</style>
