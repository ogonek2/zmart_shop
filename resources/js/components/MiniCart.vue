<template>
    <!-- Overlay -->
    <div v-if="visible" class="fixed inset-0 bg-black bg-opacity-50 z-50 transition-opacity" @click="toggle"></div>
    
    <!-- Mini Cart Panel -->
    <div :class="panelClass" class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 transform transition-transform duration-300 flex flex-col">
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white p-6 flex items-center justify-between">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-shopping-cart mr-3"></i>
                Кошик
            </h3>
            <button @click="toggle" class="text-white hover:text-yellow-300 transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="flex-1 overflow-y-auto p-6">
            <!-- Items List -->
            <div v-if="cart.length > 0" class="space-y-4">
                <div v-for="item in cart" :key="item.id" 
                     class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all">
                    <div class="flex gap-4">
                        <!-- Image -->
                        <div class="flex-shrink-0">
                            <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg overflow-hidden">
                                <img v-if="item.image" 
                                     :src="item.image" 
                                     :alt="item.name"
                                     class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-2xl text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-900 text-sm mb-2 line-clamp-2">{{ item.name }}</h4>
                            
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-lg font-bold text-emerald-600">
                                    {{ formatPrice(getItemPrice(item)) }} ₴
                                </span>
                            </div>
                            
                            <!-- Wholesale Badge -->
                            <div v-if="isWholesaleActive(item)" 
                                 class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">
                                <i class="fas fa-boxes mr-1"></i>Оптова ціна
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Controls -->
                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
                        <div class="flex items-center gap-3">
                            <button @click="decrease(item.id)" 
                                    class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition-colors">
                                <i class="fas fa-minus text-gray-600"></i>
                            </button>
                            <span class="font-bold text-gray-900 min-w-[2rem] text-center">{{ item.quantity }}</span>
                            <button @click="increase(item.id)" 
                                    class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition-colors">
                                <i class="fas fa-plus text-gray-600"></i>
                            </button>
                        </div>
                        
                        <button @click="remove(item.id)" 
                                class="text-red-500 hover:text-red-700 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Кошик порожній</h4>
                <p class="text-gray-600 mb-6">Додайте товари для оформлення замовлення</p>
                <button @click="toggle" 
                        class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-6 py-3 rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Почати покупки
                </button>
            </div>
        </div>

        <!-- Footer with Total and Checkout -->
        <div v-if="cart.length > 0" class="border-t border-gray-200 p-6 bg-gray-50">
            <div class="flex items-center justify-between mb-4">
                <span class="text-gray-600 font-medium">Всього:</span>
                <span class="text-3xl font-bold text-gray-900">{{ formatPrice(totalPrice) }} ₴</span>
            </div>
            
            <button @click="goToCheckout" 
                    class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 text-white py-4 rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all shadow-lg">
                <i class="fas fa-credit-card mr-2"></i>
                Оформити замовлення
            </button>
            
            <button @click="toggle" 
                    class="w-full mt-3 border-2 border-gray-300 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-50 transition-all">
                Продовжити покупки
            </button>
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
        panelClass() {
            return this.visible ? 'translate-x-0' : 'translate-x-full';
        },
        totalPrice() {
            const total = this.cart.reduce((sum, item) => {
                const price = this.getItemPrice(item);
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
        loadCart() {
            try {
                const saved = localStorage.getItem('cart');
                this.cart = saved ? JSON.parse(saved) : [];
            } catch (e) {
                console.error('Error loading cart:', e);
                this.cart = [];
            }
        },
        saveCart() {
            localStorage.setItem('cart', JSON.stringify(this.cart));
            window.dispatchEvent(new Event('cart-updated'));
        },
        toggle() {
            this.visible = !this.visible;
            document.body.style.overflow = this.visible ? 'hidden' : '';
        },
        increase(itemId) {
            const item = this.cart.find(item => item.id == itemId);
            if (item) {
                item.quantity++;
                this.saveCart();
            }
        },
        decrease(itemId) {
            const item = this.cart.find(item => item.id == itemId);
            if (item && item.quantity > 1) {
                item.quantity--;
                this.saveCart();
            }
        },
        remove(itemId) {
            const index = this.cart.findIndex(item => item.id == itemId);
            if (index > -1) {
                this.cart.splice(index, 1);
                this.saveCart();
                
                if (this.$toast) {
                    this.$toast.info('Видалено з кошика');
                }
            }
        },
        getItemPrice(item) {
            if (item.isWholesale && item.wholesalePrice && item.wholesaleMinQuantity) {
                if (item.quantity >= item.wholesaleMinQuantity) {
                    return parseFloat(item.wholesalePrice);
                }
            }
            
            let price = item.price;
            if (typeof price === 'string') {
                price = parseFloat(price.replace(/[^\d.,]/g, '').replace(',', '.'));
            }
            if (isNaN(price) || typeof price !== 'number') {
                price = 0;
            }
            return price;
        },
        isWholesaleActive(item) {
            return item.isWholesale && 
                   item.wholesalePrice && 
                   item.wholesaleMinQuantity && 
                   item.quantity >= item.wholesaleMinQuantity;
        },
        formatPrice(price) {
            return Math.round(price).toLocaleString('uk-UA');
        },
        goToCheckout() {
            window.location.href = '/checkout';
        }
    }
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>