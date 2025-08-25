<template>
    <div>
        <!-- Кнопка добавления -->
        <button class="btn position-relative" :class="inCart ? 'btn-dark text-white' : 'border border-secondary'" @click="toggleCart">
            <i class="fa fa-shopping-basket" aria-hidden="true"></i>
        </button>

        <!-- Всплывающее окно -->
        <div v-if="showPopup" class="cart-popup">
            <header class="d-flex justify-content-between align-items-center w-100">
                <strong>Ви поклали у кошик</strong>
                <button class="close-btn" @click="closePopup">
                    <i class="fa fa-times"></i>
                </button>
            </header>
            <div class="popup-content py-4">
                <img :src="image" alt="image" />
                <div>
                    <span class="truncated-text">{{ name }}</span>
                    <strong>{{ price }} грн</strong>
                </div>
            </div>
        </div>

        <!-- Фиксированная кнопка корзини -->
        <div class="cart-open-button" v-if="cartHasItems" @click="openCart">
            <div class="position-absolute bg-danger text-white rounded-5"
                 style="width: 40px; height: 40px; display: flex; flex-direction: column;
                 justify-content: center; align-items: center; top: -15px; left: -15px;">
                {{ cartCount }}
            </div>
            <i class="fa fa-shopping-cart cart-icon" aria-hidden="true"></i>
        </div>
    </div>
</template>

<script>
export default {
    name: "CartButton",
    props: {
        id: [String, Number],
        articule: String,
        name: String,
        image: String,
        price: String
    },
    data() {
        return {
            inCart: false,
            showPopup: false,
            cartHasItems: false,
            cartCount: 0,
            popupTimeout: null,
        };
    },
    mounted() {
        this.checkCart();
        window.addEventListener('cart-updated', this.checkCart);
    },
    unmounted() {
        window.removeEventListener('cart-updated', this.checkCart);
    },
    methods: {
        checkCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            this.inCart = cart.some(item => item.id == this.id);
            this.cartHasItems = cart.length > 0;
            this.cartCount = cart.length;
        },
        openCart() {
            window.dispatchEvent(new Event('toggle-mini-cart'));
        },
        toggleCart() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            const index = cart.findIndex(item => item.id == this.id);

            if (index >= 0) {
                cart.splice(index, 1);
                this.inCart = false;
            } else {
                cart.push({
                    id: this.id,
                    articule: this.articule,
                    name: this.name,
                    price: this.price,
                    image: this.image,
                    quantity: 1
                });
                this.inCart = true;
                this.showCartPopup(); // Показываем всплывающее окно
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            window.dispatchEvent(new Event('cart-updated'));
        },
        showCartPopup() {
            this.showPopup = true;

            // Автоматическое скрытие через 5 сек
            clearTimeout(this.popupTimeout);
            this.popupTimeout = setTimeout(() => {
                this.showPopup = false;
            }, 5000);
        },
        closePopup() {
            this.showPopup = false;
            clearTimeout(this.popupTimeout);
        }
    }
};
</script>

<style scoped>
.cart-popup {
    position: fixed;
    bottom: 0;
    right: 0;
    background: #fff;
    border: 1px solid #ccc;
    padding: 12px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
    width: 100%;
    max-width: 480px;
    z-index: 1000;
}

.popup-content {
    display: flex;
    gap: 10px;
    align-items: center;
}

.popup-content img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
}

.close-btn {
    margin-left: auto;
    background: transparent;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #999;
}

.cart-open-button {
    position: fixed;
    right: 20px;
    bottom: 20px;
    background: #ffffff;
    color: #000000;
    padding: 10px 16px;
    border-radius: 115px;
    font-weight: bold;
    cursor: pointer;
    width: 80px;
    height: 80px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    border: 1px solid #ccc;
}
.cart-open-button .cart-icon {
    font-size: 25px;
}
</style>
