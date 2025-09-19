<template>
    <div class="p-3">
        <h5 class="mb-2">
            Разом
        </h5>
        <ul class="list-group">
            <li class="list-group-item border-0 p-0 d-flex align-items-center justify-content-between">
                <span>
                    {{ cart.length }} товарів на суму
                </span>
                <b>
                    {{ totalPrice }} ₴
                </b>
            </li>
            <li class="list-group-item border-0 p-0 d-flex align-items-center justify-content-between mt-2">
                <span>
                    Вартість<br>доставки
                </span>
                <b>
                    за тарифами<br>перевізника
                </b>
            </li>
            <li class="list-group-item border-0 p-0 d-flex align-items-center justify-content-between mt-4">
                <span>
                    До сплати
                </span>
                <h3>
                    {{ totalPrice }} ₴
                </h3>
            </li>
        </ul>
        <button class="btn btn-success w-100 py-3" id="submit_cart_ch_form">
            Оформити замовлення
        </button>
        <input type="hidden" id="total_price_stream" :value="totalPrice">
    </div>
    <div class="p-3 overflow-auto flex-grow-1">
        <h5 class="mb-2">Ваш кошик</h5>
        <div v-if="cart.length">
            <ul class="list-group">
                <li class="list-group-item p-3 mb-2 d-flex align-items-center border-0 cursor-pointer"
                    v-for="item in cart" :key="item.id">
                    <div>
                        <img v-if="item.image" style="width: 50px; height: 50px; object-fit: contain;" :src="item.image"
                            alt="">
                        <div v-else class="d-flex bg-light align-items-center flex-column justify-content-center"
                            style="width: 50px; height: 50px;">
                            <small class="text-secondary"><i class="fas fa-image"></i></small>
                        </div>
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
        </div>
        <div v-else class="text-muted text-center my-5 py-3 card border-dark bg-light w-50 m-auto fs-5">
            <span>Кошик порожній <i class="fa-regular fa-face-sad-cry"></i></span>
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
            const total = this.cart.reduce((sum, item) => {
                return sum + (item.price * item.quantity);
            }, 0);

            // Убираем лишние нули в дробной части:
            return Number(total.toFixed(2)); // округление до 2 знаков без "нудей"
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
        }
    }
};
</script>