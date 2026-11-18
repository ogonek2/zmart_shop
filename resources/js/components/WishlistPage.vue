<template>
  <div class="wpage__layout">
    <aside class="wpage__side" aria-label="Категорії в обраному">
      <div class="wpage__side-card">
        <div class="wpage__side-title">Категорії</div>

        <button
          type="button"
          class="wpage__side-item"
          :class="{ 'is-active': activeCategoryId === null }"
          @click="activeCategoryId = null"
        >
          <i class="fas fa-heart"></i>
          <span>Усе обране</span>
          <em>{{ products.length }}</em>
        </button>

        <button
          v-for="cat in sidebarCategories"
          :key="cat.id"
          type="button"
          class="wpage__side-item"
          :class="{ 'is-active': activeCategoryId === cat.id }"
          @click="activeCategoryId = cat.id"
        >
          <i class="fas fa-th-large"></i>
          <span>{{ cat.name }}</span>
          <em>{{ cat.count }}</em>
        </button>

        <p v-if="!loading && !sidebarCategories.length && products.length" class="wpage__side-hint">
          Категорії з’являться після завантаження товарів
        </p>
      </div>
    </aside>

    <section class="wpage__main">
      <header class="wpage__head">
        <div class="wpage__head-left">
          <h1>Обране</h1>
          <span v-if="filteredProducts.length" class="wpage__count">{{ filteredProducts.length }}</span>
        </div>
        <div class="wpage__head-actions">
          <select v-model="sort" class="wpage__sort" aria-label="Сортування">
            <option value="added">За датою додавання</option>
            <option value="price_asc">Спочатку дешевші</option>
            <option value="price_desc">Спочатку дорожчі</option>
            <option value="name">За назвою</option>
          </select>
          <button
            v-if="filteredProducts.length"
            type="button"
            class="wpage__buy-all"
            @click="buyAll"
          >
            Купити все
          </button>
        </div>
      </header>

      <div v-if="loading" class="wpage__empty"><p>Завантаження...</p></div>

      <div v-else-if="filteredProducts.length" class="wpage__grid">
        <article v-for="product in filteredProducts" :key="product.id" class="wpage__cell">
          <button
            type="button"
            class="wpage__remove"
            @click="remove(product.id)"
            aria-label="Видалити з обраного"
          >
            <i class="fas fa-times"></i>
          </button>

          <span v-if="discountOf(product) > 0" class="wpage__sale">
            -{{ discountOf(product) }}%
          </span>

          <a :href="`/catalog/${product.url}`" class="wpage__img">
            <img v-if="product.image_path" :src="product.image_path" :alt="product.name" loading="lazy">
            <i v-else class="fas fa-image"></i>
          </a>

          <div class="wpage__meta">
            <h2 class="wpage__title">
              <a :href="`/catalog/${product.url}`">{{ product.name }}</a>
            </h2>

            <div class="wpage__stock" :class="isInStock(product) ? 'is-in' : 'is-out'">
              {{ isInStock(product) ? 'В наявності' : 'Немає в наявності' }}
            </div>

            <div class="wpage__bid">
              <div class="wpage__prices">
                <strong>{{ formatPrice(finalPrice(product)) }} ₴</strong>
                <s v-if="discountOf(product) > 0">{{ formatPrice(product.price) }} ₴</s>
              </div>
              <button
                type="button"
                class="wpage__cart"
                :disabled="!isInStock(product)"
                @click="addToCart(product)"
                aria-label="Додати в кошик"
              >
                <i class="fas fa-shopping-cart"></i>
              </button>
            </div>
          </div>
        </article>
      </div>

      <div v-else class="wpage__empty">
        <i class="fas fa-heart"></i>
        <p>{{ products.length ? 'У цій категорії немає товарів' : 'Обране порожнє' }}</p>
        <a href="/catalog" class="wpage__buy-all">До каталогу</a>
      </div>
    </section>
  </div>
</template>

<script>
function collectDescendantIds(node, acc = []) {
  if (!node) return acc;
  acc.push(Number(node.id));
  (node.children || []).forEach((child) => collectDescendantIds(child, acc));
  return acc;
}

export default {
  name: 'WishlistPage',
  data() {
    return {
      ids: [],
      products: [],
      categories: (window.__WISHLIST_BOOT__ && window.__WISHLIST_BOOT__.categories) || [],
      activeCategoryId: null,
      sort: 'added',
      loading: false,
    };
  },
  computed: {
    categoryIdSets() {
      const map = {};
      this.categories.forEach((cat) => {
        map[cat.id] = new Set(collectDescendantIds(cat));
      });
      return map;
    },
    sidebarCategories() {
      return this.categories
        .map((cat) => {
          const idSet = this.categoryIdSets[cat.id] || new Set([cat.id]);
          const count = this.products.filter((product) =>
            (product.category_ids || []).some((id) => idSet.has(Number(id)))
          ).length;
          return { id: cat.id, name: cat.name, count };
        })
        .filter((cat) => cat.count > 0);
    },
    filteredProducts() {
      let list = this.products.slice();

      if (this.activeCategoryId != null) {
        const idSet = this.categoryIdSets[this.activeCategoryId] || new Set([this.activeCategoryId]);
        list = list.filter((product) =>
          (product.category_ids || []).some((id) => idSet.has(Number(id)))
        );
      }

      if (this.sort === 'price_asc') {
        list.sort((a, b) => this.finalPrice(a) - this.finalPrice(b));
      } else if (this.sort === 'price_desc') {
        list.sort((a, b) => this.finalPrice(b) - this.finalPrice(a));
      } else if (this.sort === 'name') {
        list.sort((a, b) => String(a.name || '').localeCompare(String(b.name || ''), 'uk'));
      } else {
        list.sort((a, b) => this.ids.indexOf(Number(a.id)) - this.ids.indexOf(Number(b.id)));
      }

      return list;
    },
  },
  mounted() {
    this.loadIds();
    this.fetchProducts();
    window.addEventListener('wishlist-updated', this.onWishlistUpdated);
  },
  unmounted() {
    window.removeEventListener('wishlist-updated', this.onWishlistUpdated);
  },
  methods: {
    onWishlistUpdated() {
      this.loadIds();
      this.fetchProducts();
    },
    loadIds() {
      try {
        const raw = JSON.parse(localStorage.getItem('wishlist') || '[]');
        this.ids = raw.map((item) => Number(item.id ?? item)).filter(Boolean);
      } catch (e) {
        this.ids = [];
      }
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
    remove(productId) {
      if (typeof window.toggleWishlist === 'function') {
        window.toggleWishlist(productId);
      }
      this.products = this.products.filter((p) => Number(p.id) !== Number(productId));
      this.loadIds();
      if (this.activeCategoryId != null) {
        const still = this.sidebarCategories.some((c) => c.id === this.activeCategoryId);
        if (!still) this.activeCategoryId = null;
      }
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
      this.filteredProducts.forEach((product) => {
        if (this.isInStock(product)) this.addToCart(product);
      });
    },
  },
};
</script>
