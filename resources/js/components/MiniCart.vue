<template>
  <div class="zcart" :hidden="!visible">
    <div class="zcart__backdrop" @click="toggle"></div>

    <div class="zcart__modal" role="dialog" aria-modal="true" aria-label="Кошик">
      <div class="zcart__head">
        <h3>Кошик</h3>
        <button type="button" class="zcart__icon" @click="toggle" aria-label="Закрити">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <template v-if="cart.length">
        <div class="zcart__toolbar">
          <label class="zcart__check">
            <input type="checkbox" :checked="allSelected" @change="toggleAll">
            <span>Вибрано {{ selectedIds.length }} з {{ cart.length }}</span>
          </label>
          <button
            type="button"
            class="zcart__icon"
            :disabled="!selectedIds.length"
            @click="removeSelected"
            aria-label="Видалити вибрані"
          >
            <i class="fas fa-trash"></i>
          </button>
        </div>

        <div class="zcart__list">
          <article v-for="item in cart" :key="item.id" class="zcart__item">
            <label class="zcart__check zcart__check--solo">
              <input
                type="checkbox"
                :checked="selectedIds.includes(String(item.id))"
                @change="toggleSelect(item.id)"
              >
            </label>

            <div class="zcart__img">
              <img v-if="item.image" :src="item.image" :alt="item.name">
              <i v-else class="fas fa-image"></i>
            </div>

            <div class="zcart__main">
              <h4>{{ item.name }}</h4>
              <p v-if="item.articule" class="zcart__art">Артикул: {{ item.articule }}</p>
              <p v-if="isWholesaleActive(item)" class="zcart__badge">Оптова ціна</p>
            </div>

            <div class="zcart__side">
              <div class="zcart__qty" role="group" aria-label="Кількість">
                <button type="button" @click="decrease(item.id)" aria-label="Менше">−</button>
                <input
                  type="number"
                  min="1"
                  :value="item.quantity"
                  @change="setQty(item.id, $event)"
                >
                <button type="button" @click="increase(item.id)" aria-label="Більше">+</button>
              </div>

              <div class="zcart__price">
                <strong>{{ formatPrice(lineTotal(item)) }} ₴</strong>
                <small>{{ formatPrice(getItemPrice(item)) }} ₴ / шт</small>
              </div>

              <button type="button" class="zcart__icon" @click="remove(item.id)" aria-label="Видалити">
                <i class="fas fa-trash"></i>
              </button>
            </div>
          </article>
        </div>

        <div class="zcart__foot">
          <button type="button" class="zcart__link" @click="toggle">Продовжити покупки</button>

          <div class="zcart__checkout">
            <div class="zcart__sum">
              <span>Разом</span>
              <strong>{{ formatPrice(selectedTotal) }} ₴</strong>
            </div>
            <p v-if="isBelowMinimum" class="zcart__hint">
              Мінімум 1000 ₴. Ще {{ formatPrice(amountToReachMinimum) }} ₴
            </p>
            <button
              type="button"
              class="zcart__btn"
              :disabled="isBelowMinimum || !selectedIds.length"
              @click="goToCheckout"
            >
              Оформити замовлення
            </button>
          </div>
        </div>
      </template>

      <div v-else class="zcart__empty">
        <i class="fas fa-shopping-bag"></i>
        <p>Кошик порожній</p>
        <button type="button" class="zcart__btn" @click="toggle">Продовжити покупки</button>
      </div>
    </div>
  </div>
</template>

<script>
const MIN_ORDER_TOTAL = 1000;

export default {
  name: 'MiniCart',
  data() {
    return {
      cart: [],
      visible: false,
      selectedIds: [],
    };
  },
  computed: {
    allSelected() {
      return this.cart.length > 0 && this.selectedIds.length === this.cart.length;
    },
    selectedItems() {
      return this.cart.filter((item) => this.selectedIds.includes(String(item.id)));
    },
    selectedTotal() {
      return Number(this.selectedItems.reduce((sum, item) => {
        return sum + this.lineTotal(item);
      }, 0).toFixed(2));
    },
    isBelowMinimum() {
      return this.selectedTotal < MIN_ORDER_TOTAL;
    },
    amountToReachMinimum() {
      return Math.max(0, Math.ceil(MIN_ORDER_TOTAL - this.selectedTotal));
    },
  },
  mounted() {
    this.loadCart();
    window.addEventListener('cart-updated', this.loadCart);
    window.addEventListener('toggle-mini-cart', this.toggle);
  },
  unmounted() {
    window.removeEventListener('cart-updated', this.loadCart);
    window.removeEventListener('toggle-mini-cart', this.toggle);
  },
  methods: {
    loadCart() {
      try {
        this.cart = JSON.parse(localStorage.getItem('cart') || '[]');
      } catch (e) {
        this.cart = [];
      }
      const ids = this.cart.map((item) => String(item.id));
      this.selectedIds = this.selectedIds.filter((id) => ids.includes(id));
      if (!this.selectedIds.length) this.selectedIds = [...ids];
    },
    saveCart() {
      localStorage.setItem('cart', JSON.stringify(this.cart));
      window.dispatchEvent(new Event('cart-updated'));
    },
    toggle() {
      this.visible = !this.visible;
      document.body.style.overflow = this.visible ? 'hidden' : '';
      if (this.visible) this.loadCart();
    },
    toggleSelect(id) {
      const key = String(id);
      if (this.selectedIds.includes(key)) {
        this.selectedIds = this.selectedIds.filter((row) => row !== key);
      } else {
        this.selectedIds = [...this.selectedIds, key];
      }
    },
    toggleAll(event) {
      this.selectedIds = event.target.checked
        ? this.cart.map((item) => String(item.id))
        : [];
    },
    increase(id) {
      const item = this.cart.find((row) => row.id == id);
      if (item) {
        item.quantity = (item.quantity || 1) + 1;
        this.saveCart();
      }
    },
    decrease(id) {
      const item = this.cart.find((row) => row.id == id);
      if (item && item.quantity > 1) {
        item.quantity -= 1;
        this.saveCart();
      }
    },
    setQty(id, event) {
      const item = this.cart.find((row) => row.id == id);
      if (!item) return;
      const value = parseInt(event.target.value, 10);
      item.quantity = Number.isFinite(value) && value > 0 ? value : 1;
      event.target.value = item.quantity;
      this.saveCart();
    },
    remove(id) {
      this.cart = this.cart.filter((row) => row.id != id);
      this.selectedIds = this.selectedIds.filter((row) => row !== String(id));
      this.saveCart();
    },
    removeSelected() {
      if (!this.selectedIds.length) return;
      this.cart = this.cart.filter((row) => !this.selectedIds.includes(String(row.id)));
      this.selectedIds = [];
      this.saveCart();
    },
    getItemPrice(item) {
      if (item.isWholesale && item.wholesalePrice && item.wholesaleMinQuantity
        && item.quantity >= item.wholesaleMinQuantity) {
        return parseFloat(item.wholesalePrice) || 0;
      }
      let price = item.price;
      if (typeof price === 'string') {
        price = parseFloat(price.replace(/[^\d.,]/g, '').replace(',', '.'));
      }
      return Number.isFinite(price) ? price : 0;
    },
    isWholesaleActive(item) {
      return !!(item.isWholesale && item.wholesalePrice && item.wholesaleMinQuantity
        && item.quantity >= item.wholesaleMinQuantity);
    },
    lineTotal(item) {
      return this.getItemPrice(item) * (parseInt(item.quantity, 10) || 1);
    },
    formatPrice(price) {
      return Math.round(price || 0).toLocaleString('uk-UA');
    },
    goToCheckout() {
      if (this.isBelowMinimum || !this.selectedIds.length) return;
      window.location.href = '/checkout';
    },
  },
};
</script>
