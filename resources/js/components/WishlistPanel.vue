<template>
  <div class="zwish" :hidden="!visible">
    <div class="zwish__backdrop" @click="toggle"></div>

    <div class="zwish__modal" role="dialog" aria-modal="true" aria-label="Обране">
      <div class="zwish__head">
        <div class="zwish__head-left">
          <h3>Обране</h3>
          <span v-if="products.length" class="zwish__count">{{ products.length }}</span>
        </div>
        <div class="zwish__head-actions">
          <button
            v-if="products.length"
            type="button"
            class="zwish__buy-all"
            @click="buyAll"
          >
            Купити все
          </button>
          <button type="button" class="zwish__icon" @click="toggle" aria-label="Закрити">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>

      <div class="zwish__body">
        <div v-if="loading" class="zwish__empty"><p>Завантаження...</p></div>

        <div v-else-if="products.length" class="zwish__grid">
          <article v-for="product in products" :key="product.id" class="zwish__cell">
            <button
              type="button"
              class="zwish__remove"
              @click="remove(product.id)"
              aria-label="Видалити з обраного"
            >
              <i class="fas fa-times"></i>
            </button>

            <span v-if="discountOf(product) > 0" class="zwish__sale">
              -{{ discountOf(product) }}%
            </span>

            <a :href="`/catalog/${product.url}`" class="zwish__img" @click="toggle">
              <img v-if="product.image_path" :src="product.image_path" :alt="product.name" loading="lazy">
              <i v-else class="fas fa-image"></i>
            </a>

            <h4 class="zwish__title">
              <a :href="`/catalog/${product.url}`" @click="toggle">{{ product.name }}</a>
            </h4>

            <div class="zwish__stock" :class="isInStock(product) ? 'is-in' : 'is-out'">
              {{ isInStock(product) ? 'В наявності' : 'Немає в наявності' }}
            </div>

            <div class="zwish__bid">
              <div class="zwish__prices">
                <strong>{{ formatPrice(finalPrice(product)) }} ₴</strong>
                <s v-if="discountOf(product) > 0">{{ formatPrice(product.price) }} ₴</s>
              </div>
              <button
                type="button"
                class="zwish__cart"
                :disabled="!isInStock(product)"
                @click="addToCart(product)"
                aria-label="Додати в кошик"
              >
                <i class="fas fa-shopping-cart"></i>
              </button>
            </div>
          </article>
        </div>

        <div v-else class="zwish__empty">
          <i class="fas fa-heart"></i>
          <p>Обране порожнє</p>
          <button type="button" class="zwish__buy-all" @click="toggle">До каталогу</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'WishlistPanel',
  data() {
    return {
      ids: [],
      products: [],
      visible: false,
      loading: false,
    };
  },
  mounted() {
    this.loadIds();
    window.addEventListener('wishlist-updated', this.loadIds);
    window.addEventListener('toggle-wishlist-panel', this.toggle);
  },
  unmounted() {
    window.removeEventListener('wishlist-updated', this.loadIds);
    window.removeEventListener('toggle-wishlist-panel', this.toggle);
  },
  methods: {
    loadIds() {
      try {
        const raw = JSON.parse(localStorage.getItem('wishlist') || '[]');
        this.ids = raw.map((item) => Number(item.id ?? item)).filter(Boolean);
      } catch (e) {
        this.ids = [];
      }
      if (this.visible) this.fetchProducts();
    },
    async fetchProducts() {
      if (!this.ids.length) {
        this.products = [];
        return;
      }
      this.loading = true;
      try {
        const response = await fetch(`/catalog/search?ids=${this.ids.join(',')}`);
        if (response.ok) {
          const data = await response.json();
          this.products = Array.isArray(data) ? data : (data.products || []);
        } else {
          this.products = [];
        }
      } catch (e) {
        this.products = [];
      } finally {
        this.loading = false;
      }
    },
    async toggle() {
      this.visible = !this.visible;
      document.body.style.overflow = this.visible ? 'hidden' : '';
      if (this.visible) await this.fetchProducts();
    },
    remove(productId) {
      if (typeof window.toggleWishlist === 'function') {
        window.toggleWishlist(productId);
      }
      this.products = this.products.filter((p) => Number(p.id) !== Number(productId));
      this.loadIds();
    },
    discountOf(product) {
      const value = parseFloat(product.discount) || 0;
      return value > 0 ? Math.round(value) : 0;
    },
    finalPrice(product) {
      const price = parseFloat(product.price) || 0;
      const discount = this.discountOf(product);
      return discount > 0 ? price - (price * discount) / 100 : price;
    },
    isInStock(product) {
      const value = product.availability;
      return value === 'in_stock' || value === 1 || value === '1' || value === true || value == null || value === '';
    },
    formatPrice(price) {
      return Math.round(parseFloat(price) || 0).toLocaleString('uk-UA');
    },
    addToCart(product) {
      if (!this.isInStock(product) || typeof window.addToCart !== 'function') return;
      window.addToCart(
        product.id,
        product.name,
        this.finalPrice(product),
        product.image_path || '',
        product.articule || 'Не вказано',
        product.availability || 'in_stock',
        1,
        false,
        0,
        0
      );
    },
    buyAll() {
      this.products.forEach((product) => {
        if (this.isInStock(product)) this.addToCart(product);
      });
    },
  },
};
</script>
