<template>
    <button class="btn btn-outline-primary position-relative" @click="toggleCart">
        <i class="fas fa-shopping-cart me-2"></i>
        <span class="d-none d-md-inline">Корзина</span>
        <span v-if="cartCount > 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
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
        window.addEventListener('cart-counter-updated', this.updateCartCount);
    },
    unmounted() {
        window.removeEventListener('cart-updated', this.updateCartCount);
        window.removeEventListener('cart-counter-updated', this.updateCartCount);
    },
    methods: {
        toggleCart() {
            // Отправляем глобальное событие
            window.dispatchEvent(new Event('toggle-mini-cart'));
        },
        updateCartCount() {
            try {
                const cart = JSON.parse(localStorage.getItem('cart')) || [];
                this.cartCount = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
            } catch (error) {
                console.error('Ошибка обновления счетчика корзины:', error);
                this.cartCount = 0;
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
