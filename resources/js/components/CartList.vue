<template>
    <div>
        <!-- Cart Items List -->
        <div v-if="cart.length > 0" class="space-y-3 max-h-96 overflow-y-auto pr-2">
            <div v-for="item in cart" :key="item.id" 
                 class="flex gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200">
                <!-- Image -->
                <div class="flex-shrink-0">
                    <div class="w-16 h-16 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg overflow-hidden">
                        <img v-if="item.image" 
                             :src="item.image" 
                             :alt="item.name"
                             class="w-full h-full object-cover">
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="flex-1 min-w-0">
                    <h4 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-2">{{ item.name }}</h4>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-sm font-bold text-emerald-600">{{ formatPrice(getItemPrice(item)) }} ₴</span>
                        <span v-if="isWholesaleActive(item)" class="text-xs bg-teal-100 text-teal-700 px-2 py-0.5 rounded-full">
                            <i class="fas fa-tags mr-1"></i>Опт
                        </span>
                    </div>

                    <!-- Quantity Controls -->
                    <div class="flex items-center gap-2">
                        <button @click="decrease(item.id)" 
                                class="w-7 h-7 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 flex items-center justify-center transition-colors">
                            <i class="fas fa-minus text-xs text-gray-600"></i>
                        </button>
                        <span class="text-sm font-semibold text-gray-900 min-w-[1.5rem] text-center">{{ item.quantity }}</span>
                        <button @click="increase(item.id)" 
                                class="w-7 h-7 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 flex items-center justify-center transition-colors">
                            <i class="fas fa-plus text-xs text-gray-600"></i>
                        </button>
                        <button @click="remove(item.id)" 
                                class="ml-auto text-red-500 hover:text-red-700 transition-colors">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-cart text-3xl text-gray-400"></i>
            </div>
            <h4 class="font-bold text-gray-900 mb-2">Кошик порожній</h4>
            <p class="text-gray-600 mb-4">Додайте товари для оформлення замовлення</p>
            <a href="{{ route('catalog') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all">
                <i class="fas fa-shopping-bag mr-2"></i>
                Почати покупки
            </a>
        </div>

        <!-- Hidden field for total price -->
        <input type="hidden" id="total_price_stream" :value="totalPrice">
    </div>
</template>

<script>
export default {
    name: "CartList",
    data() {
        return {
            cart: []
        };
    },
    computed: {
        totalPrice() {
            const total = this.cart.reduce((sum, item) => {
                const price = this.getItemPrice(item);
                return sum + (price * item.quantity);
            }, 0);
            return Number(total.toFixed(2));
        }
    },
    mounted() {
        this.loadCart();
        window.addEventListener('cart-updated', this.loadCart);
    },
    unmounted() {
        window.removeEventListener('cart-updated', this.loadCart);
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
        increase(id) {
            const item = this.cart.find(i => i.id == id);
            if (item) {
                item.quantity++;
                this.saveCart();
            }
        },
        decrease(id) {
            const item = this.cart.find(i => i.id == id);
            if (item && item.quantity > 1) {
                item.quantity--;
                this.saveCart();
            }
        },
        remove(id) {
            this.cart = this.cart.filter(i => i.id != id);
            this.saveCart();
        },
        getItemPrice(item) {
            // Проверяем оптовую цену
            if (item.isWholesale && item.wholesalePrice && item.wholesaleMinQuantity) {
                if (item.quantity >= item.wholesaleMinQuantity) {
                    return parseFloat(item.wholesalePrice);
                }
            }
            
            // Обычная цена
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

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f3f4f6;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>