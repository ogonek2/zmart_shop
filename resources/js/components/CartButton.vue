<template>
    <div>
        <!-- Кнопка добавления в корзину -->
        <button class="btn cart-add-btn position-relative" 
                :class="inCart ? 'btn-success' : 'btn-outline-primary'" 
                @click="toggleCart"
                :disabled="availability === 2">
            <i class="fas fa-shopping-cart me-2"></i>
            {{ availability === 2 ? 'Нет в наличии' : (inCart ? `В корзине (${cartItemQuantity})` : 'В корзину') }}
        </button>
    </div>
</template>

<script>
export default {
    name: "CartButton",
    props: {
        // Оставляем props для обратной совместимости, но приоритет у data-атрибутов
        id: [String, Number],
        name: String,
        image: String,
        price: String,
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
            // Данные из data-атрибутов
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
        // Приоритет data-атрибутам, fallback на props
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
            // Читаем данные из data-атрибутов
            const element = this.$el;
            if (element) {
                this.productData = {
                    id: element.dataset.productId || element.dataset.productId,
                    name: element.dataset.productName || '',
                    price: element.dataset.productPrice || '',
                    image: element.dataset.productImage || '',
                    articule: element.dataset.productArticule || '',
                    availability: parseInt(element.dataset.productAvailability) || 1,
                    isWholesale: element.dataset.productIsWholesale === 'true' || element.dataset.productIsWholesale === '1',
                    wholesalePrice: element.dataset.productWholesalePrice || null,
                    wholesaleMinQuantity: parseInt(element.dataset.productWholesaleMinQuantity) || null
                };
                
                console.log('CartButton - Прочитаны data-атрибуты:', {
                    element: element,
                    dataset: element.dataset,
                    productData: this.productData
                });
            }
        },
        checkCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItem = cart.find(item => item.id == this.finalId);
            this.inCart = !!cartItem;
            this.cartItemQuantity = cartItem ? cartItem.quantity : 0;
        },
        toggleCart() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItem = cart.find(item => item.id == this.finalId);

            if (cartItem) {
                // Убираем товар из корзины
                cart = cart.filter(item => item.id != this.finalId);
                this.inCart = false;
                this.cartItemQuantity = 0;
                
                // Показываем уведомление через тостер
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Товар удален из корзины',
                        message: 'Товар успешно удален из вашей корзины',
                        type: 'info',
                        duration: 3000
                    }
                }));
            } else {
                // Добавляем товар в корзину
                const cartItem = {
                    id: this.finalId,
                    name: this.finalName,
                    price: this.finalPrice,
                    image: this.finalImage,
                    articule: this.finalArticule || 'Не указан',
                    quantity: 1
                };
                
                // Добавляем оптовые данные, если товар оптовый
                console.log('CartButton - Оптовые данные:', {
                    finalIsWholesale: this.finalIsWholesale,
                    finalWholesalePrice: this.finalWholesalePrice,
                    finalWholesaleMinQuantity: this.finalWholesaleMinQuantity
                });
                
                if (this.finalIsWholesale && this.finalWholesalePrice && this.finalWholesaleMinQuantity) {
                    cartItem.isWholesale = true;
                    cartItem.wholesalePrice = parseFloat(this.finalWholesalePrice);
                    cartItem.wholesaleMinQuantity = parseInt(this.finalWholesaleMinQuantity);
                    console.log('CartButton - Добавлены оптовые данные:', cartItem);
                }
                
                cart.push(cartItem);
                this.inCart = true;
                this.cartItemQuantity = 1;
                
                // Показываем уведомление через тостер
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Товар добавлен в корзину!',
                        message: 'Товар успешно добавлен в вашу корзину',
                        type: 'success',
                        product: {
                            id: this.finalId,
                            name: this.finalName,
                            price: this.finalPrice,
                            image: this.finalImage,
                            articule: this.finalArticule || 'Не указан'
                        },
                        duration: 4000
                    }
                }));
            }

            // Сохраняем корзину
            localStorage.setItem('cart', JSON.stringify(cart));
            
            // Отправляем событие обновления корзины
            window.dispatchEvent(new Event('cart-updated'));
        }
    }
};
</script>

<style scoped>
.cart-add-btn {
    width: 100%;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.cart-add-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.cart-add-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.cart-add-btn:disabled:hover {
    transform: none;
    box-shadow: none;
}

/* CSS переменные для совместимости */
:root {
    --primary-color: #2563eb;
    --success-color: #10b981;
    --danger-color: #ef4444;
    --dark-color: #1e293b;
}
</style>
