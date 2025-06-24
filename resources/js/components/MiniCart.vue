<template>
    <div v-if="visible" class="mini-cart-overlay">
        <div class="mini-cart-panel bg-white shadow-lg">
            <div class="d-flex justify-content-between align-items-center border-bottom p-3">
                <h5 class="mb-0">Ваш кошик</h5>
                <button class="btn btn-sm btn-outline-secondary" @click="toggle">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <div class="p-3 overflow-auto flex-grow-1">
                <div v-if="cart.length">
                    <ul class="list-group mb-3 ls-gr-max-height-scroll">
                        <li class="list-group-item p-3 mb-2 d-flex align-items-center cursor-pointer"
                            v-for="item in cart" :key="item.id">
                            <div>
                                <img style="width: 50px; height: 50px; object-fit: contain;" :src="item.image" alt="">
                            </div>
                            <div class="ps-3">
                                <strong>{{ item.name }}</strong><br>
                                <small>Ціна: {{ item.price }} ₴</small>
                            </div>
                            <div class="btn-group btn-group-sm ms-auto d-flex align-items-center">
                                <button class="btn btn-outline-secondary" @click="decrease(item.id)">−</button>
                                <small class="px-3">{{ item.quantity }}</small>
                                <button class="btn btn-outline-secondary" @click="increase(item.id)">+</button>
                                <div class="dropdown ms-4 p-0">
                                    <button class="btn text-primary" type="button" id="MenuMoreCartItem"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu p-0" aria-labelledby="MenuMoreCartItem">
                                        <li class="btn text-danger" @click="remove(item.id)"><i class="fa fa-trash"></i>
                                            Видалити</li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="card p-3 d-flex align-items-center flex-row ms-auto border-primary bg-primary-subtle" style="width: fit-content;">
                        <div>
                            <span class="fs-4">{{ totalPrice }} ₴</span>
                        </div>
                        <button class="btn btn-primary ms-3 fs-6" @click="goToCheckout">Оформити замовлення</button>
                    </div>
                </div>
                <div v-else class="text-muted text-center my-5 py-3 card border-dark bg-light w-50 m-auto fs-5">
                    <span>Кошик порожній <i class="fa-regular fa-face-sad-cry"></i></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "MiniCart",
    data() {
        return {
            cart: [],
            visible: false
        };
    },
    computed: {
        totalPrice() {
            // Предполагаем, что в item есть поле price — если нет, нужно подгружать или передавать в корзину
            return this.cart.reduce((sum, item) => {
                return sum + (item.price * item.quantity);
            }, 0);
        }
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
        toggle() {
            this.visible = !this.visible;
            if (this.visible) this.loadCart();
        },
        loadCart() {
            this.cart = JSON.parse(localStorage.getItem('cart')) || [];
        },
        saveCart() {
            localStorage.setItem('cart', JSON.stringify(this.cart));
            window.dispatchEvent(new Event('cart-updated'));
        },
        increase(id) {
            const item = this.cart.find(i => i.id == id);
            if (item) {
                item.quantity += 1;
                this.saveCart();
            }
        },
        decrease(id) {
            const item = this.cart.find(i => i.id == id);
            if (item) {
                item.quantity--;
                if (item.quantity <= 0) {
                    item.quantity = 1
                } else {
                    this.saveCart();
                }
            }
        },
        remove(id) {
            this.cart = this.cart.filter(i => i.id != id);
            this.saveCart();
        },
        goToCheckout() {
            window.location.href = '/checkout';
        }
    }
};
</script>

<style scoped>
.mini-cart-overlay {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1055;
    /* выше чем navbar, модалки и т.д. */
    display: flex;
    justify-content: center;
    align-items: center;
}

.mini-cart-panel {
    width: 100%;
    max-width: 870px;
    height: fit-content;
    max-height: 100%;
    display: flex;
    flex-direction: column;
    background-color: white;
}

.ls-gr-max-height-scroll {
    max-height: 500px;
    min-height: 200px;
    overflow-y: auto;
}
</style>
