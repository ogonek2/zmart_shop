<template>
    <button @click="toggleWishlist" 
            class="relative p-2 text-gray-700 hover:text-emerald-600 transition-colors">
        <i class="fas fa-heart text-xl"></i>
        <span v-if="wishlistCount > 0" 
              class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-pink-500 to-rose-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
            {{ wishlistCount }}
        </span>
    </button>
</template>

<script>
export default {
    name: "WishlistButton",
    data() {
        return {
            wishlistCount: 0
        };
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
                const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
                this.wishlistCount = wishlist.length;
            } catch (e) {
                console.error('Error updating wishlist count:', e);
                this.wishlistCount = 0;
            }
        },
        toggleWishlist() {
            window.dispatchEvent(new Event('toggle-wishlist-panel'));
        }
    }
};
</script>