<template>
  <div class="zco" :class="'zco--' + variant">
    <template v-if="variant === 'items'">
      <div v-if="cart.length" class="zco__items">
        <article v-for="item in cart" :key="item.id" class="zco__item">
          <a :href="itemUrl(item)" class="zco__img">
            <img v-if="item.image" :src="item.image" :alt="item.name" loading="lazy">
            <i v-else class="fas fa-image"></i>
          </a>
          <div class="zco__meta">
            <a :href="itemUrl(item)" class="zco__name">{{ item.name }}</a>
            <div class="zco__line">
              <span class="zco__unit">{{ formatPrice(getItemPrice(item)) }} ₴</span>
              <span v-if="isWholesaleActive(item)" class="zco__opt">Опт</span>
            </div>
            <div class="zco__controls">
              <div class="zco__qty">
                <button type="button" @click="decrease(item.id)" aria-label="Менше">−</button>
                <span>{{ item.quantity }}</span>
                <button type="button" @click="increase(item.id)" aria-label="Більше">+</button>
              </div>
              <strong class="zco__sum">{{ formatPrice(getItemPrice(item) * item.quantity) }} ₴</strong>
              <button type="button" class="zco__remove" @click="remove(item.id)" aria-label="Видалити">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </div>
        </article>
      </div>
      <div v-else class="zco__empty">
        <p>Кошик порожній</p>
        <a href="/catalog">До каталогу</a>
      </div>
    </template>

    <template v-else>
      <div v-if="cart.length" class="zco__aside">
        <div class="zco__row">
          <span>{{ itemsCount }} товар(ів) на суму</span>
          <b>{{ formatPrice(totalPrice) }} ₴</b>
        </div>
        <div class="zco__row">
          <span>Доставка</span>
          <b>За тарифами перевізника</b>
        </div>
        <div class="zco__total">
          <span>Разом</span>
          <strong>{{ formatPrice(totalPrice) }} ₴</strong>
        </div>
        <p v-if="isBelowMinimum" class="zco__note is-warn">
          Мінімум 1000 ₴. Додайте ще на {{ formatPrice(amountToReachMinimum) }} ₴.
        </p>
        <p v-else class="zco__note is-ok">Мінімальна сума виконана.</p>
      </div>
      <div v-else class="zco__empty">
        <p>Кошик порожній</p>
        <a href="/catalog">До каталогу</a>
      </div>
    </template>

    <input v-if="variant === 'aside'" type="hidden" id="total_price_stream" :value="totalPrice">
  </div>
</template>

<script>
const MIN_ORDER_TOTAL = 1000;

export default {
  name: 'CartList',
  props: {
    variant: {
      type: String,
      default: 'items',
    },
  },
  data() {
    return { cart: [] };
  },
  computed: {
    totalPrice() {
      const total = this.cart.reduce((sum, item) => sum + this.getItemPrice(item) * item.quantity, 0);
      return Number(total.toFixed(2));
    },
    itemsCount() {
      return this.cart.reduce((sum, item) => sum + (item.quantity || 0), 0);
    },
    isBelowMinimum() {
      return this.totalPrice < MIN_ORDER_TOTAL;
    },
    amountToReachMinimum() {
      return Math.max(0, Math.ceil(MIN_ORDER_TOTAL - this.totalPrice));
    },
  },
  mounted() {
    this.loadCart();
    window.addEventListener('cart-updated', this.loadCart);
  },
  unmounted() {
    window.removeEventListener('cart-updated', this.loadCart);
  },
  methods: {
    loadCart() {
      try {
        this.cart = JSON.parse(localStorage.getItem('cart') || '[]');
      } catch (e) {
        this.cart = [];
      }
    },
    saveCart() {
      localStorage.setItem('cart', JSON.stringify(this.cart));
      window.dispatchEvent(new Event('cart-updated'));
    },
    itemUrl(item) {
      return item.url ? `/catalog/${item.url}` : '/catalog';
    },
    increase(id) {
      const item = this.cart.find((i) => i.id == id);
      if (item) {
        item.quantity += 1;
        this.saveCart();
      }
    },
    decrease(id) {
      const item = this.cart.find((i) => i.id == id);
      if (item && item.quantity > 1) {
        item.quantity -= 1;
        this.saveCart();
      }
    },
    remove(id) {
      this.cart = this.cart.filter((i) => i.id != id);
      this.saveCart();
    },
    getItemPrice(item) {
      if (item.isWholesale && item.wholesalePrice && item.wholesaleMinQuantity) {
        if (item.quantity >= item.wholesaleMinQuantity) {
          return parseFloat(item.wholesalePrice) || 0;
        }
      }
      let price = item.price;
      if (typeof price === 'string') {
        price = parseFloat(price.replace(/[^\d.,]/g, '').replace(',', '.'));
      }
      return Number.isFinite(price) ? price : 0;
    },
    isWholesaleActive(item) {
      return !!(
        item.isWholesale &&
        item.wholesalePrice &&
        item.wholesaleMinQuantity &&
        item.quantity >= item.wholesaleMinQuantity
      );
    },
    formatPrice(price) {
      return Math.round(price || 0).toLocaleString('uk-UA');
    },
  },
};
</script>
