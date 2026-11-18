<template>
    <div>
        <div class="sort-panel mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <label for="sort-select" class="fw-semibold text-muted">Сортувати:</label>
                    <select v-model="selectedSort" id="sort-select" class="form-select sort-select">
                        <option value="">За замовчуванням</option>
                        <option value="price_asc">Від дешевих до дорогих</option>
                        <option value="price_desc">Від дорогих до дешевих</option>
                        <option value="discount">За розміром знижки</option>
                        <option value="name_asc">За назвою (А-Я)</option>
                        <option value="name_desc">За назвою (Я-А)</option>
                    </select>
                </div>

                <div class="view-toggle">
                    <button class="view-btn" :class="{ active: view === 'grid' }" @click="setView('grid')" title="Сітка">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-btn" :class="{ active: view === 'list' }" @click="setView('list')" title="Список">
                        <i class="fas fa-list"></i>
                    </button>
                    <button class="view-btn" :class="{ active: view === 'compact' }" @click="setView('compact')" title="Компактно">
                        <i class="fas fa-th-large"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="pcard-grid products-container" :class="viewClass">
            <article v-for="product in sortedProducts" :key="product.id" class="pcard">
                <div class="pcard__media">
                    <button
                        type="button"
                        class="pcard__wish"
                        title="Додати в обране"
                        @click="toggleWishlist(product.id)"
                    >
                        <i :class="isInWishlist(product.id) ? 'fas fa-heart' : 'far fa-heart'"></i>
                    </button>

                    <a :href="`/catalog/${product.url}`" class="pcard__image">
                        <img
                            v-if="product.image_path"
                            :src="product.image_path"
                            :alt="product.name"
                            loading="lazy"
                        />
                        <div v-else class="pcard__image-empty">
                            <i class="fas fa-image"></i>
                        </div>
                    </a>
                </div>

                <div class="pcard__body">
                    <h3 class="pcard__title">
                        <a :href="`/catalog/${product.url}`">{{ product.name }}</a>
                    </h3>

                    <div class="pcard__stock" :class="isInStock(product) ? 'is-in' : 'is-out'">
                        <span class="pcard__stock-dot"></span>
                        {{ isInStock(product) ? 'В наявності' : 'Немає в наявності' }}
                    </div>

                    <div class="pcard__price">
                        <strong>{{ formatPrice(finalPrice(product)) }} грн</strong>
                        <span>/ шт</span>
                    </div>

                    <div
                        v-if="product.is_wholesale && product.wholesale_price"
                        class="pcard__opt"
                    >
                        <i class="fas fa-box"></i>
                        <div>
                            <b>{{ formatPrice(product.wholesale_price) }} грн</b>
                            <small>опт від {{ product.wholesale_min_quantity || 1 }} шт</small>
                        </div>
                    </div>

                    <div class="pcard__qty">
                        <button type="button" class="pcard__qty-btn" @click="changeQty(product.id, -1)">−</button>
                        <label class="pcard__qty-field">
                            <input
                                type="number"
                                min="1"
                                step="1"
                                :value="getQty(product.id)"
                                @input="onQtyInput(product.id, $event)"
                            />
                            <span>шт</span>
                        </label>
                        <button type="button" class="pcard__qty-btn" @click="changeQty(product.id, 1)">+</button>
                    </div>

                    <button
                        type="button"
                        class="pcard__cart"
                        :disabled="!isInStock(product)"
                        @click="addToCart(product)"
                    >
                        <i class="fas fa-shopping-cart"></i>
                        Додати до кошика
                    </button>

                    <div v-if="boxQty(product)" class="pcard__box">
                        <i class="fas fa-cubes"></i>
                        У ящику: {{ boxQty(product) }} шт
                    </div>
                </div>
            </article>
        </div>

        <div v-if="sortedProducts.length === 0" class="no-products text-center py-5">
            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Товари не знайдено</h5>
            <p class="text-muted">Спробуйте змінити параметри пошуку або фільтри</p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        products: Array,
        pagination: Object,
    },
    data() {
        return {
            selectedSort: '',
            view: localStorage.getItem('productView') || 'grid',
            wishlist: JSON.parse(localStorage.getItem('wishlist') || '[]'),
            quantities: {},
        };
    },
    computed: {
        sortedProducts() {
            let sorted = [...this.products];

            switch (this.selectedSort) {
                case 'price_asc':
                    sorted.sort((a, b) => this.finalPrice(a) - this.finalPrice(b));
                    break;
                case 'price_desc':
                    sorted.sort((a, b) => this.finalPrice(b) - this.finalPrice(a));
                    break;
                case 'discount':
                    sorted = sorted.filter((p) => p.discount > 0).sort((a, b) => b.discount - a.discount);
                    break;
                case 'name_asc':
                    sorted.sort((a, b) => a.name.localeCompare(b.name));
                    break;
                case 'name_desc':
                    sorted.sort((a, b) => b.name.localeCompare(a.name));
                    break;
            }

            return sorted;
        },
        viewClass() {
            return {
                'grid-view': this.view === 'grid',
                'list-view': this.view === 'list',
                'compact-view': this.view === 'compact',
            };
        },
    },
    mounted() {
        window.addEventListener('wishlist-updated', this.syncWishlist);
    },
    unmounted() {
        window.removeEventListener('wishlist-updated', this.syncWishlist);
    },
    methods: {
        syncWishlist() {
            this.wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
        },
        finalPrice(product) {
            return product.discount > 0
                ? product.price - (product.price * product.discount) / 100
                : product.price;
        },
        formatPrice(price) {
            return Math.round(parseFloat(price) || 0).toLocaleString('uk-UA');
        },
        setView(viewType) {
            this.view = viewType;
            localStorage.setItem('productView', viewType);
        },
        isInStock(product) {
            const value = product.availability;
            return value === 'in_stock' || value === 1 || value === '1' || value === true || value == null || value === '';
        },
        getQty(id) {
            return this.quantities[id] || 1;
        },
        changeQty(id, delta) {
            this.quantities = { ...this.quantities, [id]: Math.max(1, this.getQty(id) + delta) };
        },
        onQtyInput(id, event) {
            const raw = parseInt(event.target.value, 10);
            this.quantities = { ...this.quantities, [id]: Number.isFinite(raw) && raw > 0 ? raw : 1 };
        },
        boxQty(product) {
            if (product.box_qty) return product.box_qty;
            if (Array.isArray(product.packages) && product.packages[0]?.value) {
                const value = parseInt(product.packages[0].value, 10);
                return Number.isFinite(value) ? value : null;
            }
            return null;
        },
        toggleWishlist(productId) {
            const index = this.wishlist.findIndex((item) => item.id === productId);
            if (index > -1) {
                this.wishlist.splice(index, 1);
                window.dispatchEvent(new CustomEvent('show-toast', {
                    detail: {
                        title: 'Прибрано з обраного',
                        message: 'Товар прибрано з вашого обраного',
                        type: 'info',
                        duration: 3000,
                    },
                }));
            } else {
                const product = this.products.find((p) => p.id === productId);
                if (product) {
                    this.wishlist.push({
                        id: productId,
                        name: product.name,
                        price: this.finalPrice(product),
                        image: product.image_path || '',
                        discount: product.discount || 0,
                    });
                    window.dispatchEvent(new CustomEvent('show-toast', {
                        detail: {
                            title: 'Додано в обране!',
                            message: 'Товар додано до вашого обраного',
                            type: 'success',
                            duration: 3000,
                        },
                    }));
                }
            }
            localStorage.setItem('wishlist', JSON.stringify(this.wishlist));
            window.dispatchEvent(new Event('wishlist-updated'));
        },
        isInWishlist(productId) {
            return this.wishlist.some((item) => item.id === productId);
        },
        addToCart(product) {
            if (!this.isInStock(product)) return;
            const qty = this.getQty(product.id);
            if (typeof window.addToCart === 'function') {
                window.addToCart(
                    product.id,
                    product.name,
                    this.finalPrice(product),
                    product.image_path || '',
                    product.articule || 'Не вказано',
                    product.availability || 'in_stock',
                    qty,
                    !!product.is_wholesale,
                    product.wholesale_price || 0,
                    product.wholesale_min_quantity || 0
                );
            }
        },
    },
};
</script>

<style scoped>
.sort-panel {
    background: white;
    border-radius: 8px;
    padding: 1rem 1.25rem;
    box-shadow: 0 1px 2px rgb(0 0 0 / 0.05);
    border: 1px solid #e5e5e5;
}

.sort-select {
    border: 1px solid #e5e5e5;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    min-width: 200px;
}

.view-toggle {
    display: flex;
    gap: 0.5rem;
}

.view-btn {
    padding: 0.65rem;
    border: 1px solid #e5e5e5;
    background: white;
    color: #64748b;
    border-radius: 6px;
    cursor: pointer;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.view-btn:hover,
.view-btn.active {
    border-color: #0A1F3D;
    color: #0A1F3D;
    background: rgba(10, 31, 61, 0.06);
}

.list-view {
    grid-template-columns: 1fr !important;
}

.list-view .pcard {
    display: grid;
    grid-template-columns: 180px 1fr;
}

.list-view .pcard__media {
    border-bottom: 0;
    border-right: 1px solid #EEEEEE;
}

.compact-view {
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)) !important;
}

@media (max-width: 768px) {
    .pcard-grid,
    .compact-view {
        grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        gap: 0 !important;
    }
}

.no-products {
    background: white;
    border-radius: 8px;
    border: 1px solid #e5e5e5;
}

@media (max-width: 768px) {
    .list-view .pcard {
        grid-template-columns: 1fr;
    }

    .list-view .pcard__media {
        border-right: 0;
        border-bottom: 1px solid #EEEEEE;
    }
}
</style>
