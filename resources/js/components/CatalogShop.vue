<template>
  <div class="cshop">
    <div class="cshop__layout">
      <aside class="cshop__sidebar" :class="{ 'is-open': filtersOpen }">
        <div class="cshop__sidebar-head">
          <h2>Фільтри</h2>
          <button type="button" class="cshop__icon-btn" @click="filtersOpen = false" aria-label="Закрити">
            <i class="fas fa-times"></i>
          </button>
        </div>

        <div class="cshop__sidebar-body">
          <!-- Загальний каталог: кнопка Категорії → мегаменю -->
          <div class="cshop__block" v-if="!category">
            <button type="button" class="cshop__cats-btn" @click="openMega">
              <i class="fas fa-th-large"></i>
              <span>Категорії</span>
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>

          <!-- Сторінка категорії: підкатегорії галочками -->
          <div class="cshop__block" v-else>
            <button type="button" class="cshop__block-toggle" @click="sections.subs = !sections.subs">
              <h3>Підкатегорії</h3>
              <i class="fas" :class="sections.subs ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
            <div v-show="sections.subs" class="cshop__checks cshop__checks--tree">
              <label class="cshop__check cshop__check--all">
                <input
                  type="checkbox"
                  :checked="subcatsAll"
                  @change="toggleAllSubcats"
                >
                <span>Всі</span>
              </label>
              <template v-if="resolvedSubTree.length">
                <subcat-node
                  v-for="node in resolvedSubTree"
                  :key="node.id"
                  :node="node"
                  :selected="draft.subcats"
                  :depth="0"
                  @toggle="toggleSubcat"
                />
              </template>
              <p v-else class="cshop__hint">Немає підкатегорій</p>
            </div>
          </div>

          <div class="cshop__block">
            <button type="button" class="cshop__block-toggle" @click="sections.price = !sections.price">
              <h3>Ціна</h3>
              <i class="fas" :class="sections.price ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
            <div v-show="sections.price" class="cshop__price-row">
              <input v-model="draft.price_min" type="number" min="0" placeholder="від" inputmode="numeric">
              <input v-model="draft.price_max" type="number" min="0" placeholder="до" inputmode="numeric">
              <button type="button" class="cshop__price-ok" @click="applyFilters">OK</button>
            </div>
          </div>

          <div class="cshop__block">
            <button type="button" class="cshop__block-toggle" @click="sections.params = !sections.params">
              <h3>Фільтри</h3>
              <i class="fas" :class="sections.params ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
            <div v-show="sections.params" class="cshop__checks">
              <label class="cshop__check">
                <input v-model="draft.availability" type="checkbox" @change="applyFilters">
                <span>В наявності</span>
              </label>
              <label class="cshop__check">
                <input v-model="draft.discount" type="checkbox" @change="applyFilters">
                <span>Зі знижкою</span>
              </label>
              <label class="cshop__check">
                <input v-model="draft.wholesale" type="checkbox" @change="applyFilters">
                <span>Опт</span>
              </label>
            </div>
          </div>

          <div
            v-for="facet in liveFacets"
            :key="facet.key"
            class="cshop__block"
          >
            <button
              type="button"
              class="cshop__block-toggle"
              @click="toggleFacetSection(facet.key)"
            >
              <h3>{{ facet.label }}</h3>
              <i class="fas" :class="isFacetOpen(facet.key) ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
            <div v-show="isFacetOpen(facet.key)" class="cshop__checks">
              <label
                v-for="opt in facet.options"
                :key="`${facet.key}-${opt.value}`"
                class="cshop__check"
              >
                <input
                  type="checkbox"
                  :checked="isAttrChecked(facet.key, opt.value)"
                  @change="toggleAttr(facet.key, opt.value)"
                >
                <span class="cshop__check-label">
                  <span>{{ opt.value }}</span>
                  <em v-if="opt.count">{{ opt.count }}</em>
                </span>
              </label>
            </div>
          </div>
        </div>

        <div class="cshop__sidebar-foot">
          <button type="button" class="cshop__btn cshop__btn--primary" @click="applyFilters">
            Застосувати
          </button>
          <button type="button" class="cshop__btn cshop__btn--ghost" @click="resetFilters">
            Скинути
          </button>
        </div>
      </aside>

      <div class="cshop__backdrop" v-if="filtersOpen" @click="filtersOpen = false"></div>

      <section class="cshop__main">
        <div class="cshop__toolbar">
          <div class="cshop__toolbar-left">
            <button type="button" class="cshop__btn cshop__btn--filter" @click="filtersOpen = true">
              <i class="fas fa-sliders-h"></i>
              Фільтри
              <span v-if="activeFilterCount" class="cshop__badge">{{ activeFilterCount }}</span>
            </button>
            <button
              v-if="!category"
              type="button"
              class="cshop__btn cshop__btn--filter cshop__btn--cats"
              @click="openMega"
            >
              <i class="fas fa-th-large"></i>
              Категорії
            </button>
            <p class="cshop__count">
              Знайдено <strong>{{ pagination.total || 0 }}</strong> товарів
            </p>
          </div>

          <label class="cshop__sort">
            <span>Сортування</span>
            <select v-model="filters.sort" @change="onSortChange">
              <option value="default">За замовчуванням</option>
              <option value="price_asc">Ціна: зростаюча</option>
              <option value="price_desc">Ціна: спадна</option>
              <option value="name_asc">Назва: А-Я</option>
              <option value="name_desc">Назва: Я-А</option>
              <option value="newest">Новинки</option>
            </select>
          </label>
        </div>

        <div v-if="loading" class="cshop__state">
          <div class="cshop__spinner"></div>
          <p>Завантаження товарів...</p>
        </div>

        <div v-else-if="!products.length" class="cshop__state">
          <i class="fas fa-box-open"></i>
          <h3>Товари не знайдено</h3>
          <p>Спробуйте змінити фільтри або категорію</p>
          <button type="button" class="cshop__btn cshop__btn--primary" @click="resetFilters">Скинути фільтри</button>
        </div>

        <div v-else class="pcard-grid cshop__grid">
          <article v-for="product in products" :key="product.id" class="pcard">
            <div class="pcard__media">
              <button type="button" class="pcard__wish" @click="toggleWishlist(product.id)" aria-label="Обране">
                <i :class="isInWishlist(product.id) ? 'fas fa-heart' : 'far fa-heart'"></i>
              </button>
              <a :href="`/catalog/${product.url}`" class="pcard__image">
                <img v-if="product.image_path" :src="product.image_path" :alt="product.name" loading="lazy">
                <div v-else class="pcard__image-empty"><i class="fas fa-image"></i></div>
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
              <div v-if="product.is_wholesale && product.wholesale_price" class="pcard__opt">
                <i class="fas fa-box"></i>
                <div>
                  <b>{{ formatPrice(product.wholesale_price) }} грн</b>
                  <small>опт від {{ product.wholesale_min_quantity || 1 }} шт</small>
                </div>
              </div>
              <div class="pcard__qty">
                <button type="button" class="pcard__qty-btn" @click="changeQty(product.id, -1)">−</button>
                <label class="pcard__qty-field">
                  <input type="number" min="1" step="1" :value="getQty(product.id)" @input="onQtyInput(product.id, $event)">
                  <span>шт</span>
                </label>
                <button type="button" class="pcard__qty-btn" @click="changeQty(product.id, 1)">+</button>
              </div>
              <button type="button" class="pcard__cart" :disabled="!isInStock(product)" @click="addToCart(product)">
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

        <nav v-if="pagination.last_page > 1" class="cshop__pager" aria-label="Пагінація">
          <button type="button" class="cshop__page" :disabled="pagination.current_page <= 1" @click="goPage(pagination.current_page - 1)">
            <i class="fas fa-chevron-left"></i>
          </button>
          <button
            v-for="page in visiblePages"
            :key="`p-${page}`"
            type="button"
            class="cshop__page"
            :class="{ 'is-active': page === pagination.current_page, 'is-gap': page === '…' }"
            :disabled="page === '…'"
            @click="page !== '…' && goPage(page)"
          >
            {{ page }}
          </button>
          <button type="button" class="cshop__page" :disabled="pagination.current_page >= pagination.last_page" @click="goPage(pagination.current_page + 1)">
            <i class="fas fa-chevron-right"></i>
          </button>
          <p class="cshop__pager-info">
            {{ pagination.from || 0 }}–{{ pagination.to || 0 }} з {{ pagination.total || 0 }}
          </p>
        </nav>
      </section>
    </div>
  </div>
</template>

<script>
const SubcatNode = {
  name: 'SubcatNode',
  props: {
    node: { type: Object, required: true },
    selected: { type: Array, default: () => [] },
    depth: { type: Number, default: 0 },
  },
  emits: ['toggle'],
  computed: {
    checked() {
      return this.selected.includes(this.node.id);
    },
    hasKids() {
      return (this.node.children || []).length > 0;
    },
  },
  data() {
    return { open: this.depth < 1 };
  },
};
SubcatNode.components = { SubcatNode };
SubcatNode.template = `
    <div class="cshop__subnode" :style="{ '--depth': depth }">
      <div class="cshop__subrow">
        <label class="cshop__check">
          <input
            type="checkbox"
            :checked="checked"
            @change="$emit('toggle', node.id)"
          >
          <span>{{ node.name }}</span>
        </label>
        <button
          v-if="hasKids"
          type="button"
          class="cshop__tree-toggle"
          :class="{ 'is-open': open }"
          @click.prevent="open = !open"
          :aria-label="open ? 'Згорнути' : 'Розгорнути'"
        >
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
      <div v-if="hasKids && open" class="cshop__tree-children">
        <subcat-node
          v-for="child in node.children"
          :key="child.id"
          :node="child"
          :selected="selected"
          :depth="depth + 1"
          @toggle="$emit('toggle', $event)"
        />
      </div>
    </div>
  `;

export default {
  name: 'CatalogShop',
  components: { SubcatNode },
  props: {
    endpoint: { type: String, default: '/catalog' },
    categories: { type: Array, default: () => [] },
    subTree: { type: Array, default: () => [] },
    subcategories: { type: Array, default: () => [] },
    facets: { type: Array, default: () => [] },
    category: { type: Object, default: null },
    initialProducts: { type: Array, default: () => [] },
    initialPagination: { type: Object, default: () => ({}) },
    initialFilters: { type: Object, default: () => ({}) },
  },
  data() {
    const attrs = { ...(this.initialFilters.attrs || {}) };
    const subcats = Array.isArray(this.initialFilters.subcats)
      ? this.initialFilters.subcats.map((id) => Number(id)).filter(Boolean)
      : [];
    const filters = {
      price_min: this.initialFilters.price_min || '',
      price_max: this.initialFilters.price_max || '',
      availability: !!this.initialFilters.availability,
      discount: !!this.initialFilters.discount,
      wholesale: !!this.initialFilters.wholesale,
      sort: this.initialFilters.sort || 'default',
      page: this.initialPagination.current_page || 1,
      subcats,
      attrs,
    };
    return {
      products: this.initialProducts || [],
      pagination: this.initialPagination || { current_page: 1, last_page: 1, total: 0 },
      liveFacets: this.facets || [],
      filters,
      draft: {
        price_min: filters.price_min,
        price_max: filters.price_max,
        availability: filters.availability,
        discount: filters.discount,
        wholesale: filters.wholesale,
        subcats: [...subcats],
        attrs: JSON.parse(JSON.stringify(attrs)),
      },
      loading: false,
      filtersOpen: false,
      quantities: {},
      wishlist: JSON.parse(localStorage.getItem('wishlist') || '[]'),
      sections: {
        subs: true,
        price: true,
        params: true,
      },
      facetOpen: {},
    };
  },
  computed: {
    resolvedSubTree() {
      if ((this.subTree || []).length) return this.subTree;
      return this.subcategories || [];
    },
    subcatsAll() {
      return !this.draft.subcats.length;
    },
    activeFilterCount() {
      let count = 0;
      if (this.filters.price_min !== '' && this.filters.price_min != null) count += 1;
      if (this.filters.price_max !== '' && this.filters.price_max != null) count += 1;
      if (this.filters.availability) count += 1;
      if (this.filters.discount) count += 1;
      if (this.filters.wholesale) count += 1;
      if ((this.filters.subcats || []).length) count += 1;
      Object.values(this.filters.attrs || {}).forEach((arr) => {
        if (Array.isArray(arr) && arr.length) count += 1;
      });
      return count;
    },
    visiblePages() {
      const current = this.pagination.current_page || 1;
      const last = this.pagination.last_page || 1;
      if (last <= 7) {
        return Array.from({ length: last }, (_, i) => i + 1);
      }
      const pages = new Set([1, last, current, current - 1, current + 1, current - 2, current + 2]);
      const sorted = [...pages].filter((p) => p >= 1 && p <= last).sort((a, b) => a - b);
      const result = [];
      sorted.forEach((page, idx) => {
        if (idx > 0 && page - sorted[idx - 1] > 1) result.push('…');
        result.push(page);
      });
      return result;
    },
  },
  mounted() {
    window.addEventListener('wishlist-updated', this.syncWishlist);
    window.addEventListener('popstate', this.onPopState);
  },
  unmounted() {
    window.removeEventListener('wishlist-updated', this.syncWishlist);
    window.removeEventListener('popstate', this.onPopState);
  },
  methods: {
    openMega() {
      window.dispatchEvent(new CustomEvent('open-catalog-mega'));
    },
    isFacetOpen(key) {
      return this.facetOpen[key] !== false;
    },
    toggleFacetSection(key) {
      this.facetOpen = {
        ...this.facetOpen,
        [key]: !this.isFacetOpen(key),
      };
    },
    isAttrChecked(key, value) {
      return (this.draft.attrs[key] || []).includes(value);
    },
    toggleAttr(key, value) {
      const current = [...(this.draft.attrs[key] || [])];
      const idx = current.indexOf(value);
      if (idx >= 0) current.splice(idx, 1);
      else current.push(value);
      this.draft.attrs = { ...this.draft.attrs, [key]: current };
      this.applyFilters();
    },
    toggleAllSubcats() {
      this.draft.subcats = [];
      this.applyFilters();
    },
    toggleSubcat(id) {
      const set = new Set(this.draft.subcats);
      if (set.has(id)) set.delete(id);
      else set.add(id);
      this.draft.subcats = [...set];
      this.applyFilters();
    },
    syncWishlist() {
      this.wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    },
    isInWishlist(id) {
      return this.wishlist.some((item) => item.id == id);
    },
    toggleWishlist(id) {
      if (typeof window.toggleWishlist === 'function') window.toggleWishlist(id);
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
      this.quantities = { ...this.quantities, [id]: Math.max(1, this.getQty(id) + delta) };
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
      if (!this.isInStock(product) || typeof window.addToCart !== 'function') return;
      window.addToCart(
        product.id,
        product.name,
        this.finalPrice(product),
        product.image_path || '',
        product.articule || 'Не вказано',
        product.availability || 'in_stock',
        this.getQty(product.id),
        !!product.is_wholesale,
        product.wholesale_price || 0,
        product.wholesale_min_quantity || 0
      );
    },
    buildQuery(page = this.filters.page) {
      const params = new URLSearchParams();
      if (this.filters.price_min !== '' && this.filters.price_min != null) params.set('price_min', this.filters.price_min);
      if (this.filters.price_max !== '' && this.filters.price_max != null) params.set('price_max', this.filters.price_max);
      if (this.filters.availability) params.set('availability', '1');
      if (this.filters.discount) params.set('discount', '1');
      if (this.filters.wholesale) params.set('wholesale', '1');
      if (this.filters.sort && this.filters.sort !== 'default') params.set('sort', this.filters.sort);
      (this.filters.subcats || []).forEach((id) => params.append('subcats[]', String(id)));
      Object.entries(this.filters.attrs || {}).forEach(([key, values]) => {
        (values || []).forEach((value) => params.append(`attrs[${key}][]`, value));
      });
      if (page > 1) params.set('page', String(page));
      return params;
    },
    async fetchProducts(page = 1, pushUrl = true) {
      this.loading = true;
      this.filters.page = page;
      try {
        const params = this.buildQuery(page);
        const url = `${this.endpoint}${params.toString() ? `?${params}` : ''}`;
        const response = await fetch(url, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
          },
        });
        if (!response.ok) throw new Error('Catalog request failed');
        const data = await response.json();
        this.products = data.products || [];
        this.pagination = data.pagination || this.pagination;
        if (Array.isArray(data.facets)) this.liveFacets = data.facets;
        if (pushUrl) {
          const next = `${window.location.pathname}${params.toString() ? `?${params}` : ''}`;
          window.history.pushState({ catalog: true }, '', next);
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
      } catch (error) {
        console.error(error);
      } finally {
        this.loading = false;
        this.filtersOpen = false;
      }
    },
    applyFilters() {
      this.filters.price_min = this.draft.price_min;
      this.filters.price_max = this.draft.price_max;
      this.filters.availability = this.draft.availability;
      this.filters.discount = this.draft.discount;
      this.filters.wholesale = this.draft.wholesale;
      this.filters.subcats = [...this.draft.subcats];
      this.filters.attrs = JSON.parse(JSON.stringify(this.draft.attrs));
      this.fetchProducts(1);
    },
    resetFilters() {
      this.draft = {
        price_min: '',
        price_max: '',
        availability: false,
        discount: false,
        wholesale: false,
        subcats: [],
        attrs: {},
      };
      this.filters = {
        ...this.filters,
        ...this.draft,
        attrs: {},
        subcats: [],
        sort: 'default',
        page: 1,
      };
      this.fetchProducts(1);
    },
    onSortChange() {
      this.fetchProducts(1);
    },
    goPage(page) {
      if (page < 1 || page > this.pagination.last_page || page === this.pagination.current_page) return;
      this.fetchProducts(page);
    },
    onPopState() {
      const params = new URLSearchParams(window.location.search);
      this.filters.price_min = params.get('price_min') || '';
      this.filters.price_max = params.get('price_max') || '';
      this.filters.availability = params.has('availability');
      this.filters.discount = params.has('discount');
      this.filters.wholesale = params.has('wholesale');
      this.filters.sort = params.get('sort') || 'default';
      this.filters.subcats = params.getAll('subcats[]').map((id) => Number(id)).filter(Boolean);
      const attrs = {};
      params.forEach((value, key) => {
        const match = key.match(/^attrs\[(.+)\]\[\]$/);
        if (match) {
          if (!attrs[match[1]]) attrs[match[1]] = [];
          attrs[match[1]].push(value);
        }
      });
      this.filters.attrs = attrs;
      this.draft = {
        price_min: this.filters.price_min,
        price_max: this.filters.price_max,
        availability: this.filters.availability,
        discount: this.filters.discount,
        wholesale: this.filters.wholesale,
        subcats: [...this.filters.subcats],
        attrs: JSON.parse(JSON.stringify(attrs)),
      };
      this.fetchProducts(parseInt(params.get('page') || '1', 10), false);
    },
  },
};
</script>
