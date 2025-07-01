<template>
  <div class="row m-0 mt-4">
    <h4 class="mb-3 h4 font-weight-bolder p-0"><b>Інші товари</b></h4>
    <div class="row p-0 m-0">
      <div v-for="product in products" :key="product.id"
        class="card border-0 rounded-0 col-6 col-md-2 p-2 position-relative d-flex flex-column bg-white border-end border-bottom card-product-item">
        <a class="p-3" :href="'catalog/' + product.url" :title="product.name" rel="nofollow" style="text-decoration: none;">
          <img v-if="product.image_path" :alt="product.name" loading="lazy" fetchpriority="auto" :src="product.image_path"
            style="width: 100%; aspect-ratio: 1 / 1; inset: 0px; object-fit: contain;" />
          <div v-else class="d-flex bg-light align-items-center flex-column justify-content-center" style="width: 100%; aspect-ratio: 1 / 1; inset: 0px;">
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
            <span style="font-size: 14px" v-if="product.discount > 0">{{ product.price - (product.price *
              product.discount / 100) }} ₴</span>
            <span style="font-size: 14px" v-else>{{ product.price }} ₴</span>
          </b>
          <!-- <button class="btn border border-secondary">
            <i class="fa fa-shopping-basket" aria-hidden="true"></i>
          </button> -->
          <CartButton :id="product.id" :name="product.name" :price="product.discount > 0
            ? (product.price - (product.price * product.discount / 100))
            : product.price" :image="product.image_path" />
        </div>
        <div v-if="product.discount > 0" class="card-label-duration">
          {{ product.discount }}%
        </div>
      </div>
    </div>
    <div v-if="loading" class="w-100 text-center my-3">
      Завантаження...
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
      loading: false,
      hasMore: true,
    };
  },
  mounted() {
    this.loadProducts();
    window.addEventListener("scroll", this.onScroll);
  },
  unmounted() {
    window.removeEventListener("scroll", this.onScroll);
  },
  methods: {
    async loadProducts() {
      if (this.loading || !this.hasMore) return;
      this.loading = true;

      try {
        const response = await axios.get(`/api/products?page=${this.page}`);
        if (response.data && response.data.length > 0) {
          this.products.push(...response.data);
          this.page += 1;
        } else {
          this.hasMore = false;
        }
      } catch (error) {
        console.error("Помилка завантаження товарів", error);
      } finally {
        this.loading = false;
      }
    },
    onScroll() {
      const scrollPosition = window.innerHeight + window.scrollY;
      const bottomPosition = document.documentElement.offsetHeight - 700;

      if (scrollPosition >= bottomPosition) {
        this.loadProducts();
      }
    },
  },
};
</script>