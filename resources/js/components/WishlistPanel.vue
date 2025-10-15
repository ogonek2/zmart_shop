<template>
    <!-- Overlay -->
    <div v-if="visible" class="fixed inset-0 bg-black bg-opacity-50 z-50 transition-opacity" @click="toggle"></div>
    
    <!-- Wishlist Panel -->
    <div :class="panelClass" class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 transform transition-transform duration-300 flex flex-col">
        <!-- Header -->
        <div class="bg-gradient-to-r from-pink-500 to-rose-500 text-white p-6 flex items-center justify-between">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-heart mr-3"></i>
                Обране
            </h3>
            <button @click="toggle" class="text-white hover:text-yellow-300 transition-colors">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Wishlist Items -->
        <div class="flex-1 overflow-y-auto p-6">
            <!-- Items List -->
            <div v-if="wishlistProducts.length > 0" class="space-y-4">
                <div v-for="product in wishlistProducts" :key="product.id" 
                     class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all">
                    <div class="flex gap-4">
                        <!-- Image -->
                        <div class="flex-shrink-0">
                            <a :href="`/catalog/${product.url}`">
                                <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg overflow-hidden">
                                    <img v-if="product.image_path" 
                                         :src="product.image_path" 
                                         :alt="product.name"
                                         class="w-full h-full object-cover" />
                                    <div v-else class="w-full h-full flex items-center justify-center">
                                        <i class="fas fa-image text-2xl text-gray-400"></i>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Info -->
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-900 text-sm mb-2 line-clamp-2">
                                <a :href="`/catalog/${product.url}`" class="hover:text-emerald-600 transition-colors">
                                    {{ product.name }}
                                </a>
                            </h4>
                            
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-lg font-bold text-emerald-600">
                                    {{ formatPrice(product.price) }} ₴
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 mt-4 pt-4 border-t border-gray-200">
                        <a :href="`/catalog/${product.url}`" 
                           class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 text-white py-2 rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all text-center text-sm">
                            <i class="fas fa-eye mr-1"></i>Переглянути
                        </a>
                        <button @click="removeFromWishlist(product.id)" 
                                class="px-4 py-2 bg-red-100 text-red-600 rounded-xl hover:bg-red-200 transition-colors">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-4">
                    <i class="fas fa-heart text-4xl text-gray-400"></i>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Обране порожнє</h4>
                <p class="text-gray-600 mb-6">Додайте товари, які вам сподобались</p>
                <button @click="toggle" 
                        class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white px-6 py-3 rounded-xl font-bold hover:from-emerald-600 hover:to-teal-600 transition-all">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Почати покупки
                </button>
            </div>
        </div>

        <!-- Footer -->
        <div v-if="wishlistProducts.length > 0" class="border-t border-gray-200 p-6 bg-gray-50">
            <button @click="toggle" 
                    class="w-full border-2 border-gray-300 text-gray-700 py-3 rounded-xl font-medium hover:bg-gray-50 transition-all">
                Продовжити покупки
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "WishlistPanel",
    data() {
        return {
            wishlist: [],
            wishlistProducts: [],
            visible: false
        };
    },
    computed: {
        panelClass() {
            return this.visible ? 'translate-x-0' : 'translate-x-full';
        }
    },
    mounted() {
        this.loadWishlist();
        window.addEventListener('wishlist-updated', this.loadWishlist);
        window.addEventListener('toggle-wishlist-panel', this.toggle);
    },
    unmounted() {
        window.removeEventListener('wishlist-updated', this.loadWishlist);
        window.removeEventListener('toggle-wishlist-panel', this.toggle);
    },
    methods: {
        loadWishlist() {
            try {
                const saved = localStorage.getItem('wishlist');
                this.wishlist = saved ? JSON.parse(saved) : [];
                this.loadWishlistProducts();
            } catch (e) {
                console.error('Error loading wishlist:', e);
                this.wishlist = [];
                this.wishlistProducts = [];
            }
        },
        async loadWishlistProducts() {
            if (this.wishlist.length === 0) {
                this.wishlistProducts = [];
                return;
            }

            try {
                // Here you would normally fetch product details from API
                // For now, we'll just store the IDs
                this.wishlistProducts = [];
            } catch (e) {
                console.error('Error loading wishlist products:', e);
            }
        },
        toggle() {
            this.visible = !this.visible;
            document.body.style.overflow = this.visible ? 'hidden' : '';
        },
        removeFromWishlist(productId) {
            const index = this.wishlist.indexOf(productId);
            if (index > -1) {
                this.wishlist.splice(index, 1);
                localStorage.setItem('wishlist', JSON.stringify(this.wishlist));
                window.dispatchEvent(new Event('wishlist-updated'));
                this.loadWishlistProducts();
                
                if (this.$toast) {
                    this.$toast.info('Видалено з обраного');
                }
            }
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
</style>