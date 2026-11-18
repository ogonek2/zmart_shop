<template>
    <button @click="toggleCart" class="zmart-icon-btn" type="button" aria-label="Кошик">
        <i class="fas fa-shopping-bag"></i>
        <span v-if="cartCount > 0" class="zmart-icon-btn__badge">{{ cartCount }}</span>
    </button>
</template>

<script>
export default {
    name: "OpenCartButton",
    data() {
        return { cartCount: 0 };
    },
    mounted() {
        this.updateCartCount();
        window.addEventListener('cart-updated', this.updateCartCount);
    },
    unmounted() {
        window.removeEventListener('cart-updated', this.updateCartCount);
    },
    methods: {
        updateCartCount() {
            try {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                this.cartCount = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
            } catch (e) {
                this.cartCount = 0;
            }
        },
        toggleCart() {
            window.dispatchEvent(new Event('toggle-mini-cart'));
        },
    },
};
</script>
