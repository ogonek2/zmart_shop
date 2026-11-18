<template>
  <div class="zh-toast-host" aria-live="polite">
    <transition name="zh-toast">
      <div v-if="visible" class="zh-toast" :class="`zh-toast--${type}`">
        <div class="zh-toast__row">
          <span class="zh-toast__thumb" aria-hidden="true">
            <img v-if="product?.image" :src="product.image" :alt="product.name || ''">
            <i v-else class="fas fa-check"></i>
          </span>

          <div class="zh-toast__text">
            <strong>{{ title || 'Додано в кошик' }}</strong>
            <span>{{ product?.name || message }}</span>
          </div>

          <strong v-if="product?.price != null" class="zh-toast__price">
            {{ formatPrice(product.price) }} ₴
          </strong>

          <button type="button" class="zh-toast__close" @click="hide" aria-label="Закрити">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'ToastNotification',
  data() {
    return {
      visible: false,
      title: '',
      message: '',
      type: 'success',
      product: null,
      timeout: null,
    };
  },
  mounted() {
    window.addEventListener('show-toast', this.showToast);
  },
  unmounted() {
    window.removeEventListener('show-toast', this.showToast);
    if (this.timeout) clearTimeout(this.timeout);
  },
  methods: {
    showToast(event) {
      const {
        title = '',
        message = '',
        type = 'success',
        product = null,
        duration = 3200,
      } = event.detail || {};

      this.title = title;
      this.message = message;
      this.type = type;
      this.product = product;
      this.visible = true;

      if (this.timeout) clearTimeout(this.timeout);
      this.timeout = setTimeout(() => this.hide(), duration);
    },
    hide() {
      this.visible = false;
      this.product = null;
    },
    formatPrice(price) {
      const value = typeof price === 'string'
        ? parseFloat(price.replace(/[^\d.,]/g, '').replace(',', '.'))
        : parseFloat(price);
      return Math.round(Number.isFinite(value) ? value : 0).toLocaleString('uk-UA');
    },
  },
};
</script>
