<template>
  <div class="product-list-section">
    <!-- Products Grid -->
    <div v-if="!loading && products.length > 0" class="products-grid">
      <div v-for="product in products" :key="product.id" class="product-card group">
        <!-- Image Container -->
        <div class="relative overflow-hidden">
          <!-- Product Image -->
          <div class="aspect-square bg-gradient-to-br from-gray-100 to-gray-200">
            <a :href="`/catalog/${product.url}`" class="block h-full">
              <img v-if="product.image_path" 
                   :src="product.image_path" 
                   :alt="product.name" 
                   loading="lazy" 
                   class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" />
              <div v-else class="w-full h-full flex items-center justify-center">
                <i class="fas fa-image text-4xl text-gray-400"></i>
              </div>
            </a>
          </div>
          
          <!-- Discount Badge -->
          <div v-if="product.discount > 0" class="absolute top-3 left-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
              <i class="fas fa-tag mr-1"></i>-{{ product.discount }}%
            </span>
          </div>
          
          <!-- Wholesale Badge -->
          <div v-if="product.is_wholesale && product.wholesale_price" class="absolute top-3 right-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
              <i class="fas fa-boxes mr-1"></i>Опт
            </span>
          </div>
          
          <!-- Availability Badge -->
          <div v-if="product.availability === 2" class="absolute top-12 left-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
              <i class="fas fa-times-circle mr-1"></i>Немає
            </span>
          </div>
          
          <!-- Wishlist Button -->
          <button @click="toggleWishlist(product.id)" 
                  class="absolute top-3 right-3 w-10 h-10 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-sm transition-all hover:scale-110 z-10"
                  :class="{ 'wishlist-active': isInWishlist(product.id) }">
            <i :class="isInWishlist(product.id) ? 'fas fa-heart text-red-500' : 'far fa-heart text-gray-400'"></i>
          </button>
        </div>
        
        <!-- Product Info -->
        <div class="p-4">
          <h4 class="font-bold text-gray-900 mb-2 line-clamp-2 text-sm">
            <a :href="`/catalog/${product.url}`" class="hover:text-emerald-600 transition-colors">
              {{ product.name }}
            </a>
          </h4>
          
          <!-- Price -->
          <div class="flex items-center gap-2 mb-3">
            <span class="text-lg font-bold text-emerald-600">
              {{ formatPrice(finalPrice(product)) }} ₴
            </span>
            <span v-if="product.discount > 0" class="text-sm text-gray-500 line-through">
              {{ formatPrice(product.price) }} ₴
            </span>
          </div>
          
          <!-- Wholesale Price -->
          <div v-if="product.is_wholesale && product.wholesale_price && product.wholesale_min_quantity" 
               class="flex items-center text-xs text-teal-600 mb-3">
            <i class="fas fa-boxes mr-1"></i>
            Опт від {{ product.wholesale_min_quantity }} шт: {{ formatPrice(product.wholesale_price) }} ₴
          </div>
          
          <!-- Actions -->
          <div class="flex gap-2">
            <a :href="`/catalog/${product.url}`" 
               class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-bold py-2 px-4 rounded-xl transition-all duration-200 text-center text-sm">
              <i class="fas fa-eye mr-1"></i>Деталі
            </a>
            <CartButton class="compact w-10 flex-shrink-0"
                       :id="product.id" 
                       :name="product.name" 
                       :price="finalPrice(product)"
                       :image="product.image_path || ''"
                       :articule="product.articule || 'Не вказано'"
                       :availability="product.availability || 1"
                       :is-wholesale="product.is_wholesale || false"
                       :wholesale-price="product.wholesale_price || 0"
                       :wholesale-min-quantity="product.wholesale_min_quantity || 0" />
          </div>
        </div>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-2xl mb-4">
        <div class="w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin"></div>
      </div>
      <h6 class="text-gray-600 font-medium">Завантаження товарів...</h6>
    </div>

    <!-- No Products -->
    <div v-if="!loading && products.length === 0" class="text-center py-12">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-2xl mb-4">
        <i class="fas fa-box-open text-3xl text-gray-400"></i>
      </div>
      <h5 class="text-gray-900 font-bold mb-2">Товари не знайдено</h5>
      <p class="text-gray-600">Спробуйте змінити параметри пошуку</p>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import CartButton from './CartButton.vue';

export default {
  name: "ProductList",
  components: {
    CartButton,
  },
  props: {
    products: {
      type: Array,
      default: () => []
    },
    pagination: {
      type: Object,
      default: () => ({})
    }
  },
  data() {
    return {
      loading: false,
      wishlist: [],
    };
  },
  mounted() {
    this.loadWishlist();
  },
  methods: {
    formatPrice(price) {
      return Math.round(price).toLocaleString('uk-UA');
    },
    finalPrice(product) {
      if (product.discount > 0) {
        return product.price * (1 - product.discount / 100);
      }
      return product.price;
    },
    loadWishlist() {
      try {
        const saved = localStorage.getItem('wishlist');
        this.wishlist = saved ? JSON.parse(saved) : [];
      } catch (e) {
        console.error('Error loading wishlist:', e);
        this.wishlist = [];
      }
    },
    toggleWishlist(productId) {
      const index = this.wishlist.indexOf(productId);
      if (index > -1) {
        this.wishlist.splice(index, 1);
        this.$toast.info('Видалено з обраного');
      } else {
        this.wishlist.push(productId);
        this.$toast.success('Додано в обране');
      }
      localStorage.setItem('wishlist', JSON.stringify(this.wishlist));
    },
    isInWishlist(productId) {
      return this.wishlist.includes(productId);
    },
  },
};
</script>

<style scoped>
.product-list-section {
  @apply w-full;
}

.products-grid {
  @apply grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6;
}

.product-card {
  @apply bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.wishlist-active {
  @apply bg-red-50 border-2 border-red-500;
}

@media (max-width: 768px) {
  .products-grid {
    @apply grid-cols-2 gap-4;
  }
}

@media (max-width: 480px) {
  .products-grid {
    @apply gap-3;
  }
}
</style>