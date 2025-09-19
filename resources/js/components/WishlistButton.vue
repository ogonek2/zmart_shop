<template>
    <button class="btn btn-outline-danger position-relative" @click="toggleWishlist">
        <i class="fas fa-heart me-2"></i>
        <span class="d-none d-md-inline">Избранное</span>
        <span v-if="wishlistCount > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
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
        toggleWishlist() {
            // Отправляем глобальное событие
            window.dispatchEvent(new Event('toggle-wishlist'));
        },
        updateWishlistCount() {
            try {
                const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
                this.wishlistCount = wishlist.length;
            } catch (error) {
                console.error('Ошибка обновления счетчика избранного:', error);
                this.wishlistCount = 0;
            }
        }
    }
};
</script>

<style scoped>
.btn {
    border-radius: 8px;
    padding: 8px 16px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.badge {
    font-size: 0.7rem;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
