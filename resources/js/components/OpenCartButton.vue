<template>
    <button @click="toggleCart" 
            class="relative p-2 text-gray-700 hover:text-emerald-600 transition-colors">
        <i class="fas fa-shopping-cart text-xl"></i>
        <span v-if="cartCount > 0" 
              class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
            {{ cartCount }}
        </span>
    </button>
</template>

<script>
export default {
    name: "OpenCartButton",
    data() {
        return {
            cartCount: 0
        };
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
                console.error('Error updating cart count:', e);
                this.cartCount = 0;
            }
        },
        toggleCart() {
            window.dispatchEvent(new Event('toggle-mini-cart'));
        }
    }
};
</script>