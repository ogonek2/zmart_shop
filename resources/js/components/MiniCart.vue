<template>
    <div v-if="visible" class="mini-cart-overlay">
        <div class="mini-cart-panel bg-white shadow-lg">
            <div class="d-flex justify-content-between align-items-center border-bottom p-3">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart text-primary me-2"></i>
                    Ваша корзина
                </h5>
                <button class="btn btn-sm btn-outline-secondary" @click="toggle">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-3 overflow-auto flex-grow-1">
                <div v-if="cart.length">
                    <ul class="list-group mb-3 ls-gr-max-height-scroll">
                        <li class="list-group-item p-3 mb-2 d-flex align-items-center cursor-pointer"
                            v-for="item in cart" :key="item.id">
                            <div>
                                <img v-if="item.image" style="width: 60px; height: 60px; object-fit: contain;"
                                    :src="item.image" alt="">
                                <div v-else
                                    class="d-flex bg-light align-items-center flex-column justify-content-center"
                                    style="width: 60px; height: 60px;">
                                    <small class="text-secondary"><i class="fas fa-image"></i></small>
                                </div>
                            </div>
                            <div class="ps-3 flex-grow-1">
                                <strong>{{ item.name }}</strong><br>
                                <small class="text-success fw-bold">{{ formatPrice(item.price) }} ₴</small>
                            </div>
                            <div class="btn-group btn-group-sm ms-auto d-flex align-items-center">
                                <button class="btn btn-outline-secondary" @click="decrease(item.id)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="px-3 fw-bold">{{ item.quantity }}</span>
                                <button class="btn btn-outline-secondary" @click="increase(item.id)">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button class="btn btn-outline-danger ms-2" @click="remove(item.id)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </li>
                    </ul>
                    <div class="card p-3 d-flex align-items-center flex-row ms-auto border-primary bg-primary-subtle"
                        style="width: fit-content; border-radius: 12px;">
                        <div>
                            <span class="fs-4 fw-bold">{{ formatPrice(totalPrice) }} ₴</span>
                        </div>
                        <button class="btn btn-primary ms-3 fs-6" @click="goToCheckout">
                            <i class="fas fa-credit-card me-2"></i>
                            Оформить заказ
                        </button>
                    </div>
                </div>
                <div v-else class="text-muted text-center my-5 py-3">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h6>Корзина пуста</h6>
                    <p class="small">Добавляйте товары в корзину для оформления заказа</p>
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
            const total = this.cart.reduce((sum, item) => {
                let price = item.price;
                
                // Если цена - строка, очищаем её
                if (typeof price === 'string') {
                    price = parseFloat(price.replace(/[^\d.,]/g, '').replace(',', '.'));
                }
                
                // Проверяем, что цена - валидное число
                if (isNaN(price) || typeof price !== 'number') {
                    price = 0;
                }
                
                const quantity = parseInt(item.quantity) || 1;
                return sum + (price * quantity);
            }, 0);

            return Number(total.toFixed(2));
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
            const cartData = localStorage.getItem('cart');
            this.cart = JSON.parse(cartData) || [];
            this.updateCartCounter();
        },
        saveCart() {
            // Очищаем цены от лишних символов перед сохранением
            this.cart.forEach(item => {
                if (typeof item.price === 'string') {
                    // Убираем все символы кроме цифр и точки, затем конвертируем в число
                    const cleanPrice = parseFloat(item.price.replace(/[^\d.,]/g, '').replace(',', '.'));
                    item.price = isNaN(cleanPrice) ? 0 : cleanPrice;
                }
                
                // Убеждаемся, что количество - число
                if (typeof item.quantity === 'string') {
                    item.quantity = parseInt(item.quantity) || 1;
                }
            });
            
            localStorage.setItem('cart', JSON.stringify(this.cart));
            window.dispatchEvent(new Event('cart-updated'));
        },
        increase(id) {
            const item = this.cart.find(i => i.id == id);
            if (item) {
                item.quantity += 1;
                this.saveCart();
                this.updateCartCounter();
            }
        },
        decrease(id) {
            const item = this.cart.find(i => i.id == id);
            if (item) {
                item.quantity--;
                if (item.quantity <= 0) {
                    item.quantity = 1;
                } else {
                    this.saveCart();
                    this.updateCartCounter();
                }
            }
        },
        remove(id) {
            this.cart = this.cart.filter(i => i.id != id);
            this.saveCart();
            this.updateCartCounter();
        },
        goToCheckout() {
            window.location.href = '/checkout';
        },
        formatPrice(price) {
            // Форматируем цену для отображения
            if (typeof price === 'string') {
                const cleanPrice = parseFloat(price.replace(/[^\d.,]/g, '').replace(',', '.'));
                return isNaN(cleanPrice) ? '0.00' : cleanPrice.toFixed(2);
            }
            if (typeof price === 'number') {
                return price.toFixed(2);
            }
            return '0.00';
        },
        updateCartCounter() {
            // Обновляем счетчик в кнопке корзины
            const cartCount = this.cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
            window.dispatchEvent(new CustomEvent('cart-counter-updated', {
                detail: { count: cartCount }
            }));
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
    border-radius: 12px;
}

.ls-gr-max-height-scroll {
    max-height: 500px;
    min-height: 200px;
    overflow-y: auto;
}

.list-group-item {
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.list-group-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.card {
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
</style>
