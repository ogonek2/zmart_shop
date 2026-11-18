<template>
  <div class="product-list-section">
    <div v-if="!loading && products.length > 0" class="pcard-grid">
      <article v-for="product in products" :key="product.id" class="pcard">
        <div class="pcard__media">
          <button
            type="button"
            class="pcard__wish"
            @click="toggleWishlist(product.id)"
            aria-label="Обране"
          >
            <i :class="isInWishlist(product.id) ? 'fas fa-heart' : 'far fa-heart'"></i>
          </button>

          <a :href="`/catalog/${product.url}`" class="pcard__image">
            <img
              v-if="product.image_path"
              :src="product.image_path"
              :alt="product.name"
              loading="lazy"
            />
            <div v-else class="pcard__image-empty">
              <i class="fas fa-image"></i>
            </div>
          </a>
        </div>

        <div class="pcard__body">
          <h3 class="pcard__title">
            <a :href="`/catalog/${product.url}`">{{ product.name }}</a>
          </h3>

          <div class="pcard__stock" :class="isInStock(product) ? 'is-in' : 'is-out'">
            <span class="pcard__stock-dot"></span>
            {{ isInStock(product) ? 'В наявності' : 'Немає в наявності' }}
          </div>

          <div class="pcard__price">
            <strong>{{ formatPrice(finalPrice(product)) }} грн</strong>
            <span>/ шт</span>
          </div>

          <div
            v-if="product.is_wholesale && product.wholesale_price"
            class="pcard__opt"
          >
            <i class="fas fa-box"></i>
            <div>
              <b>{{ formatPrice(product.wholesale_price) }} грн</b>
              <small>опт від {{ product.wholesale_min_quantity || 1 }} шт</small>
            </div>
          </div>

          <div class="pcard__qty">
            <button type="button" class="pcard__qty-btn" @click="changeQty(product.id, -1)">−</button>
            <label class="pcard__qty-field">
              <input
                type="number"
                min="1"
                step="1"
                :value="getQty(product.id)"
                @input="onQtyInput(product.id, $event)"
              />
              <span>шт</span>
            </label>
            <button type="button" class="pcard__qty-btn" @click="changeQty(product.id, 1)">+</button>
          </div>

          <button
            type="button"
            class="pcard__cart"
            :disabled="!isInStock(product)"
            @click="addToCart(product)"
          >
            <i class="fas fa-shopping-cart"></i>
            Додати до кошика
          </button>

          <div v-if="boxQty(product)" class="pcard__box">
            <i class="fas fa-cubes"></i>
            У ящику: {{ boxQty(product) }} шт
          </div>
        </div>
      </article>
    </div>

    <div v-if="loading" class="text-center py-12">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-4 bg-brand-yellow-soft">
        <div class="w-8 h-8 border-4 border-brand-yellow border-t-transparent rounded-full animate-spin"></div>
      </div>
      <h6 class="text-gray-600 font-medium">Завантаження товарів...</h6>
    </div>

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
export default {
  name: 'ProductList',
  props: {
    products: {
      type: Array,
      default: () => [],
    },
    pagination: {
      type: Object,
      default: null,
    },
  },
  data() {
    return {
      loading: false,
      quantities: {},
      wishlist: JSON.parse(localStorage.getItem('wishlist') || '[]'),
    };
  },
  mounted() {
    window.addEventListener('wishlist-updated', this.syncWishlist);
  },
  unmounted() {
    window.removeEventListener('wishlist-updated', this.syncWishlist);
  },
  methods: {
    syncWishlist() {
      this.wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    },
    isInWishlist(id) {
      return this.wishlist.some((item) => item.id == id);
    },
    toggleWishlist(id) {
      if (typeof window.toggleWishlist === 'function') {
        window.toggleWishlist(id);
      }
      this.syncWishlist();
    },
    isInStock(product) {
      const value = product.availability;
      return value === 'in_stock' || value === 1 || value === '1' || value === true || value == null || value === '';
    },
    finalPrice(product) {
      const price = parseFloat(product.price) || 0;
      const discount = parseFloat(product.discount) || 0;
      return discount > 0 ? price - (price * discount) / 100 : price;
    },
    formatPrice(price) {
      return Math.round(parseFloat(price) || 0).toLocaleString('uk-UA');
    },
    getQty(id) {
      return this.quantities[id] || 1;
    },
    changeQty(id, delta) {
      const next = Math.max(1, this.getQty(id) + delta);
      this.quantities = { ...this.quantities, [id]: next };
    },
    onQtyInput(id, event) {
      const raw = parseInt(event.target.value, 10);
      this.quantities = { ...this.quantities, [id]: Number.isFinite(raw) && raw > 0 ? raw : 1 };
    },
    boxQty(product) {
      if (product.box_qty) return product.box_qty;
      if (Array.isArray(product.packages) && product.packages[0]?.value) {
        const value = parseInt(product.packages[0].value, 10);
        return Number.isFinite(value) ? value : null;
      }
      return null;
    },
    addToCart(product) {
      if (!this.isInStock(product)) return;
      const qty = this.getQty(product.id);
      if (typeof window.addToCart === 'function') {
        window.addToCart(
          product.id,
          product.name,
          this.finalPrice(product),
          product.image_path || '',
          product.articule || 'Не вказано',
          product.availability || 'in_stock',
          qty,
          !!product.is_wholesale,
          product.wholesale_price || 0,
          product.wholesale_min_quantity || 0
        );
      }
    },
  },
};
</script>

<style scoped>
.pcard-grid {
  display: grid;
  grid-template-columns: repeat(5, minmax(0, 1fr));
  gap: 10px;
}

@media (max-width: 1200px) {
  .pcard-grid {
    grid-template-columns: repeat(4, minmax(0, 1fr));
  }
}

@media (max-width: 992px) {
  .pcard-grid {
    grid-template-columns: repeat(3, minmax(0, 1fr));
  }
}

@media (max-width: 768px) {
  .pcard-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 0;
  }
}
</style>
