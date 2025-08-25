<template>
    <div>
        <!-- Панель сортировки -->
        <div class="d-flex justify-between align-items-center justify-content-end flex-wrap mb-3 mt-4">
            <div class="d-flex gap-2">
                <select v-model="selectedSort" class="form-select form-select-sm border-secondary w-auto ">
                    <option value="">Сортувати</option>
                    <option value="price_asc">Від дешевих</option>
                    <option value="price_desc">Від дорогих</option>
                    <option value="discount">Зі знижкою</option>
                </select>
                <div class="btn-group btn-group-sm">
                    <button class="btn btn-outline-secondary" @click="setView('grid')">
                        <i class="fa fa-th-large"></i>
                    </button>
                    <button class="btn btn-outline-secondary" @click="setView('list')">
                        <i class="fa fa-list"></i>
                    </button>
                    <button class="btn btn-outline-secondary" @click="setView('row')">
                        <i class="fa fa-grip-horizontal"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Список товаров -->
        <div class="row p-0 m-0">
            <div v-for="product in sortedProducts" :key="product.id"
                class="card border-0 rounded-0 col-6 col-md-2 p-2 position-relative bg-white border-end border-bottom card-product-item"
                :class="viewClass">
                <a :href="`/catalog/${product.url}`" class="product-link">
                    <img v-if="product.image_path" :src="product.image_path" :alt="product.name" loading="lazy" class="product-image" />

                    <div v-else class="d-flex bg-light align-items-center flex-column justify-content-center"
                        style="width: 100%; aspect-ratio: 1 / 1; inset: 0px;">
                        <small class="text-secondary"><i class="fas fa-image"></i></small>
                    </div>
                </a>

                <div class="card-body d-flex flex-column justify-content-between p-0 w-50">
                    <a :href="`/catalog/${product.url}`" class="nav-link truncated-text" style="font-size: 14px;">
                        {{ product.name }}
                    </a>

                    <!-- Добавляем описание -->
                    <p v-if="view === 'list'" class="product-description truncated-text w-100" style="width: 100%;" v-html="product.description"></p>
                </div>

                <div class="card-footer p-0 mt-4 border-0 d-flex align-items-end justify-content-between bg-white"
                    :class="{ 'mt-0 flex-column-reverse h-100 py-2': view === 'list' }">
                    <div>
                        <div v-if="product.discount > 0" class="d-flex flex-column">
                            <span style="font-size: 14px">{{ finalPrice(product) }} ₴</span>
                            <small class="old-price-dr">{{ product.price }} ₴</small>
                        </div>
                        <span v-else class="h5">{{ product.price }} ₴</span>
                    </div>

                    <cart-button :id="product.id" :name="product.name" :price="finalPrice(product)"
                        :image="product.image_path" :articule="product.articule" />
                </div>

                <div v-if="product.discount > 0" class="card-label-duration">-{{ product.discount }}%</div>
            </div>
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
        };
    },
    computed: {
        sortedProducts() {
            let sorted = [...this.products];
            if (this.selectedSort === 'price_asc') {
                sorted.sort((a, b) => a.price - b.price);
            } else if (this.selectedSort === 'price_desc') {
                sorted.sort((a, b) => b.price - a.price);
            } else if (this.selectedSort === 'discount') {
                sorted = sorted.filter((p) => p.discount > 0).sort((a, b) => b.discount - a.discount);
            }
            return sorted;
        },
        viewClass() {
            return {
                'grid-view': this.view === 'grid',
                'list-view': this.view === 'list',
                'row-view': this.view === 'row',
            };
        },
    },
    methods: {
        finalPrice(product) {
            return product.discount > 0 ? product.price - (product.price * product.discount) / 100 : product.price;
        },
        setView(viewType) {
            this.view = viewType;
            localStorage.setItem('productView', viewType);
        },
    },
};
</script>

<style scoped>
.grid-view {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
}

.list-view {
    width: 100% !important;
    flex-direction: row !important;
    align-items: center;
    padding: 0.5rem;
    gap: 1rem;
}

.row-view {
    width: 50%;
}

.card-product-item {
    display: flex;
}

.product-link {
    flex-shrink: 0;
}

.product-image {
    width: 100%;
    aspect-ratio: 1 / 1;
    object-fit: contain;
}

.list-view .product-image {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 4px;
}

.list-view .card-body {
    flex: 1;
}

.product-description {
    font-size: 13px;
    margin-top: 4px;
    color: #666;
}
</style>
