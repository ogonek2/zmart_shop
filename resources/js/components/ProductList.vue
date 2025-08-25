<template>
  <div class="row m-0 mt-4">
    <h4 class="mb-3 h4 font-weight-bolder p-0"><b>Інші товари</b></h4>

    <div class="row p-0 m-0">
      <div v-for="product in products" :key="product.id"
        class="card border-0 rounded-0 col-6 col-md-2 p-2 position-relative d-flex flex-column bg-white border-end border-bottom card-product-item">
        <a class="p-3" :href="'catalog/' + product.url" :title="product.name" rel="nofollow"
          style="text-decoration: none;">
          <img v-if="product.image_path" :alt="product.name" loading="lazy" fetchpriority="auto"
            :src="product.image_path" style="width: 100%; aspect-ratio: 1 / 1; inset: 0px; object-fit: contain;" />
          <div v-else class="d-flex bg-light align-items-center flex-column justify-content-center"
            style="width: 100%; aspect-ratio: 1 / 1; inset: 0px;">
            <small class="text-secondary"><i class="fas fa-image"></i></small>
          </div>
        </a>

        <div class="card-body p-0 d-flex flex-column justify-content-between">
          <a :href="'catalog/' + product.url" class="nav-link truncated-text" style="font-size: 14px;">{{ product.name
          }}</a>
        </div>

        <div class="card-footer p-0 mt-4 border-0 d-flex align-items-end justify-content-between bg-white">
          <b style="font-size: 14px" class="d-flex flex-column">
            <small v-if="product.discount > 0" class="old-price-dr">{{ product.price }} ₴</small>
            <span v-if="product.discount > 0">{{ product.price - (product.price * product.discount / 100) }} ₴</span>
            <span v-else>{{ product.price }} ₴</span>
          </b>
          <CartButton :id="product.id" :name="product.name" :price="product.discount > 0
            ? (product.price - (product.price * product.discount / 100))
            : product.price" :image="product.image_path" :articule="product.articule" />
        </div>

        <div v-if="product.discount > 0" class="card-label-duration">
          {{ product.discount }}%
        </div>
      </div>
    </div>

    <div v-if="loading" class="w-100 text-center my-3">Завантаження...</div>

    <!-- Пагинация -->
    <div class="w-100 d-flex justify-content-center mt-4">
      <nav v-if="totalPages > 1">
        <ul class="pagination flex-wrap justify-content-center">

          <!-- Назад -->
          <li class="page-item" :class="{ disabled: page === 1 }">
            <button class="page-link" @click="changePage(page - 1)">Назад</button>
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

          <!-- Вперед -->
          <li class="page-item" :class="{ disabled: page === totalPages }">
            <button class="page-link" @click="changePage(page + 1)">Вперед</button>
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
        console.error("Помилка завантаження товарів", error);
      } finally {
        this.loading = false;
      }
    },
    changePage(newPage) {
      if (newPage >= 1 && newPage <= this.totalPages && newPage !== this.page) {
        this.page = newPage;
        this.loadProducts();
        window.scrollTo({ top: 0, behavior: "smooth" }); // вверх при смене страницы
      }
    },
  },
  mounted() {
    this.loadProducts();
  }

};
</script>
