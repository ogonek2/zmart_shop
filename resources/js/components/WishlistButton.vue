<template>
  <a href="/wishlist" class="zmart-icon-btn" aria-label="Обране">
    <i class="far fa-heart"></i>
    <span v-if="wishlistCount > 0" class="zmart-icon-btn__badge">{{ wishlistCount }}</span>
  </a>
</template>

<script>
export default {
  name: 'WishlistButton',
  data() {
    return { wishlistCount: 0 };
  },
  mounted() {
    this.updateWishlistCount();
    window.addEventListener('wishlist-updated', this.updateWishlistCount);
  },
  unmounted() {
    window.removeEventListener('wishlist-updated', this.updateWishlistCount);
  },
  methods: {
    updateWishlistCount() {
      try {
        this.wishlistCount = (JSON.parse(localStorage.getItem('wishlist')) || []).length;
      } catch (e) {
        this.wishlistCount = 0;
      }
    },
  },
};
</script>
