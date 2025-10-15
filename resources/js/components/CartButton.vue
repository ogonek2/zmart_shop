<template>
    <div>
        <!-- Cart Button -->
        <button class="cart-btn" 
                :class="[cartButtonClass, compactMode ? 'compact' : '']" 
                @click="toggleCart"
                :disabled="finalAvailability === 2">
            <i class="fas" :class="[cartIconClass, compactMode ? '' : 'mr-2']"></i>
            <span v-if="!compactMode">
                <span v-if="finalAvailability === 2">Немає в наявності</span>
                <span v-else-if="inCart">В кошику ({{ cartItemQuantity }})</span>
                <span v-else>Додати в кошик</span>
            </span>
        </button>
    </div>
</template>

<script>
export default {
    name: "CartButton",
    props: {
        id: [String, Number],
        name: String,
        image: String,
        price: [String, Number],
        articule: String,
        availability: {
            type: Number,
            default: 1
        },
        isWholesale: {
            type: Boolean,
            default: false
        },
        wholesalePrice: {
            type: [String, Number],
            default: null
        },
        wholesaleMinQuantity: {
            type: [String, Number],
            default: null
        }
    },
    data() {
        return {
            inCart: false,
            cartItemQuantity: 0,
            productData: {
                id: null,
                name: '',
                price: '',
                image: '',
                articule: '',
                availability: 1,
                isWholesale: false,
                wholesalePrice: null,
                wholesaleMinQuantity: null
            }
        };
    },
    computed: {
        compactMode() {
            // Проверяем, есть ли класс 'compact' у родительского элемента
            return this.$el && this.$el.classList && this.$el.classList.contains('compact');
        },
        finalId() {
            return this.productData.id || this.id;
        },
        finalName() {
            return this.productData.name || this.name;
        },
        finalPrice() {
            return this.productData.price || this.price;
        },
        finalImage() {
            return this.productData.image || this.image;
        },
        finalArticule() {
            return this.productData.articule || this.articule;
        },
        finalAvailability() {
            return this.productData.availability || this.availability;
        },
        finalIsWholesale() {
            return this.productData.isWholesale || this.isWholesale;
        },
        finalWholesalePrice() {
            return this.productData.wholesalePrice || this.wholesalePrice;
        },
        finalWholesaleMinQuantity() {
            return this.productData.wholesaleMinQuantity || this.wholesaleMinQuantity;
        },
        cartButtonClass() {
            if (this.finalAvailability === 2) {
                return 'cart-btn-disabled';
            }
            return this.inCart ? 'cart-btn-active' : 'cart-btn-default';
        },
        cartIconClass() {
            if (this.finalAvailability === 2) {
                return 'fa-times-circle';
            }
            return this.inCart ? 'fa-check' : 'fa-shopping-cart';
        }
    },
    mounted() {
        this.readDataAttributes();
        this.checkCart();
        window.addEventListener('cart-updated', this.checkCart);
    },
    unmounted() {
        window.removeEventListener('cart-updated', this.checkCart);
    },
    methods: {
        readDataAttributes() {
            const element = this.$el;
            if (element) {
                this.productData = {
                    id: parseInt(element.dataset.productId) || this.id,
                    name: element.dataset.productName || this.name,
                    price: element.dataset.productPrice || this.price,
                    image: element.dataset.productImage || this.image,
                    articule: element.dataset.productArticule || this.articule,
                    availability: parseInt(element.dataset.productAvailability) || this.availability || 1,
                    isWholesale: element.dataset.productIsWholesale === 'true' || this.isWholesale,
                    wholesalePrice: element.dataset.productWholesalePrice || this.wholesalePrice,
                    wholesaleMinQuantity: parseInt(element.dataset.productWholesaleMinQuantity) || this.wholesaleMinQuantity
                };
            }
        },
        checkCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const item = cart.find(item => item.id == this.finalId);
            
            if (item) {
                this.inCart = true;
                this.cartItemQuantity = item.quantity;
            } else {
                this.inCart = false;
                this.cartItemQuantity = 0;
            }
        },
        toggleCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const index = cart.findIndex(item => item.id == this.finalId);
            
            if (index > -1) {
                cart.splice(index, 1);
                this.inCart = false;
                this.cartItemQuantity = 0;
                
                if (this.$toast) {
                    this.$toast.info('Видалено з кошика');
                }
            } else {
                const cartItem = {
                    id: this.finalId,
                    name: this.finalName,
                    price: this.finalPrice,
                    image: this.finalImage,
                    articule: this.finalArticule || 'Не вказано',
                    quantity: 1
                };
                
                if (this.finalIsWholesale && this.finalWholesalePrice && this.finalWholesaleMinQuantity) {
                    cartItem.isWholesale = true;
                    cartItem.wholesalePrice = parseFloat(this.finalWholesalePrice);
                    cartItem.wholesaleMinQuantity = parseInt(this.finalWholesaleMinQuantity);
                }
                
                cart.push(cartItem);
                this.inCart = true;
                this.cartItemQuantity = 1;
                
                if (this.$toast) {
                    this.$toast.success('Додано в кошик');
                }
            }
            
            localStorage.setItem('cart', JSON.stringify(cart));
            window.dispatchEvent(new Event('cart-updated'));
        }
    },
};
</script>

<style scoped>
/* Полный режим (по умолчанию) - для страницы товара */
.cart-btn {
    @apply w-full py-4 px-6 rounded-xl flex items-center justify-center transition-all duration-200 font-bold text-base;
}

.cart-btn-default {
    @apply bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white shadow-lg hover:shadow-xl;
}

.cart-btn-active {
    @apply bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-lg;
}

.cart-btn-disabled {
    @apply bg-gray-300 text-gray-500 cursor-not-allowed shadow-none;
}

.cart-btn:not(.cart-btn-disabled):hover {
    @apply transform -translate-y-0.5;
}

/* Компактный режим - для карточек товаров */
.compact .cart-btn {
    @apply w-10 h-10 p-0 text-sm;
}

.compact .cart-btn span {
    @apply hidden;
}

.compact .cart-btn i {
    @apply m-0;
}

.compact .cart-btn:not(.cart-btn-disabled):hover {
    @apply scale-110;
}
</style>