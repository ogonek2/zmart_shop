<template>
    <div v-if="visible" class="wishlist-overlay">
        <div class="wishlist-panel bg-white shadow-lg">
            <div class="d-flex justify-content-between align-items-center border-bottom p-3">
                <h5 class="mb-0">
                    <i class="fas fa-heart text-danger me-2"></i>
                    Избранное
                </h5>
                <button class="btn btn-sm btn-outline-secondary" @click="toggle">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-3 overflow-auto flex-grow-1">
                <div v-if="wishlist.length">
                    <ul class="list-group mb-3 ls-gr-max-height-scroll">
                        <li class="list-group-item p-3 mb-2 d-flex align-items-center cursor-pointer"
                            v-for="item in wishlist" :key="item.id">
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
                                <small class="text-success fw-bold">{{ item.price }}</small>
                            </div>
                            <div class="btn-group btn-group-sm ms-auto d-flex align-items-center">
                                <button class="btn btn-primary" @click="addToCart(item)">
                                    <i class="fas fa-shopping-cart me-1"></i>
                                    В корзину
                                </button>
                                <button class="btn btn-outline-danger ms-2" @click="removeFromWishlist(item.id)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </li>
                    </ul>
                    <div class="text-center">
                        <button class="btn btn-outline-primary" @click="clearWishlist">
                            <i class="fas fa-trash me-2"></i>
                            Очистить избранное
                        </button>
                    </div>
                </div>
                <div v-else class="text-muted text-center my-5 py-3">
                    <i class="fas fa-heart fa-3x text-muted mb-3"></i>
                    <h6>Избранное пусто</h6>
                    <p class="small">Добавляйте товары в избранное, чтобы не потерять их</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "WishlistPanel",
    data() {
        return {
            wishlist: [],
            visible: false
        };
    },
    mounted() {
        this.loadWishlist();
        window.addEventListener('wishlist-updated', this.loadWishlist);
        window.addEventListener('toggle-wishlist', this.toggle);
    },
    unmounted() {
        window.removeEventListener('wishlist-updated', this.loadWishlist);
        window.removeEventListener('toggle-wishlist', this.toggle);
    },
    methods: {
        toggle() {
            this.visible = !this.visible;
            if (this.visible) this.loadWishlist();
        },
        loadWishlist() {
            this.wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
            
            // Проверяем и исправляем старый формат избранного (когда сохранялись только ID)
            this.fixOldWishlistFormat();
            
            console.log('Загружено избранное:', this.wishlist);
        },
        fixOldWishlistFormat() {
            // Проверяем, есть ли элементы старого формата (только числа)
            const hasOldFormat = this.wishlist.some(item => typeof item === 'number');
            
            if (hasOldFormat) {
                console.log('Обнаружен старый формат избранного, очищаем...');
                // Очищаем старое избранное, так как в нем нет полных данных
                this.wishlist = [];
                localStorage.setItem('wishlist', JSON.stringify(this.wishlist));
                
                // Показываем уведомление
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Избранное обновлено',
                        message: 'Старый формат избранного очищен. Добавьте товары заново.',
                        type: 'info',
                        duration: 5000
                    }
                }));
            }
        },
        addToCart(item) {
            try {
                // Проверяем, что у нас есть все необходимые данные
                if (!item || !item.id || !item.name || !item.price) {
                    console.error('Неполные данные товара:', item);
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            title: 'Ошибка!',
                            message: 'Неполные данные товара для добавления в корзину',
                            type: 'error',
                            duration: 4000
                        }
                    }));
                    return;
                }

                console.log('Добавляем в корзину товар:', item);
                
                let cart = JSON.parse(localStorage.getItem('cart')) || [];
                let existingItem = cart.find(cartItem => cartItem.id === item.id);
                
                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push({
                        id: item.id,
                        name: item.name,
                        price: item.price,
                        image: item.image || '',
                        quantity: 1
                    });
                }
                
                localStorage.setItem('cart', JSON.stringify(cart));
                window.dispatchEvent(new Event('cart-updated'));
                
                // Показываем уведомление через тостер
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Товар добавлен в корзину!',
                        message: 'Товар успешно добавлен в вашу корзину',
                        type: 'success',
                        product: {
                            id: item.id,
                            name: item.name,
                            price: item.price,
                            image: item.image || ''
                        },
                        duration: 4000
                    }
                }));
                
                // Убираем из избранного
                this.removeFromWishlist(item.id);
            } catch (error) {
                console.error('Ошибка добавления в корзину:', error);
                
                // Показываем уведомление об ошибке через тостер
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Ошибка!',
                        message: 'Не удалось добавить товар в корзину',
                        type: 'error',
                        duration: 4000
                    }
                }));
            }
        },
        removeFromWishlist(id) {
            if (!id) {
                console.error('ID товара не указан для удаления из избранного');
                return;
            }
            
            const itemToRemove = this.wishlist.find(item => item.id === id);
            if (itemToRemove) {
                console.log('Удаляем из избранного товар:', itemToRemove);
            }
            
            this.wishlist = this.wishlist.filter(item => item.id !== id);
            localStorage.setItem('wishlist', JSON.stringify(this.wishlist));
            window.dispatchEvent(new Event('wishlist-updated'));
            console.log('Удален товар из избранного:', id);
        },
        clearWishlist() {
            if (confirm('Вы уверены, что хотите очистить избранное?')) {
                this.wishlist = [];
                localStorage.setItem('wishlist', JSON.stringify(this.wishlist));
                window.dispatchEvent(new Event('wishlist-updated'));
                
                // Показываем уведомление через тостер
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Избранное очищено',
                        message: 'Все товары удалены из избранного',
                        type: 'info',
                        duration: 3000
                    }
                }));
            }
        }
    }
};
</script>

<style scoped>
.wishlist-overlay {
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

.wishlist-panel {
    width: 100%;
    max-width: 600px;
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
</style>
