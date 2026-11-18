<template>
    <article class="pcard" :class="cardClass">
        <div class="pcard__media">
            <button
                type="button"
                class="pcard__wish"
                title="Додати в обране"
                @click="toggleWishlist(product.id)"
            >
                <i :class="isInWishlist(product.id) ? 'fas fa-heart' : 'far fa-heart'"></i>
            </button>

            <a :href="`/catalog/${product.url}`" class="pcard__image" :title="product.name">
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

            <div class="pcard__stock" :class="isInStock ? 'is-in' : 'is-out'">
                <span class="pcard__stock-dot"></span>
                {{ isInStock ? 'В наявності' : 'Немає в наявності' }}
            </div>

            <div class="pcard__price">
                <strong>{{ formatPrice(finalPrice(product)) }} грн</strong>
                <span>/ шт</span>
            </div>

            <div v-if="product.is_wholesale && product.wholesale_price" class="pcard__opt">
                <i class="fas fa-box"></i>
                <div>
                    <b>{{ formatPrice(product.wholesale_price) }} грн</b>
                    <small>опт від {{ product.wholesale_min_quantity || 1 }} шт</small>
                </div>
            </div>

            <div class="pcard__qty">
                <button type="button" class="pcard__qty-btn" @click="changeQty(-1)">−</button>
                <label class="pcard__qty-field">
                    <input type="number" min="1" step="1" :value="quantity" @input="onQtyInput" />
                    <span>шт</span>
                </label>
                <button type="button" class="pcard__qty-btn" @click="changeQty(1)">+</button>
            </div>

            <button type="button" class="pcard__cart" :disabled="!isInStock" @click="addToCart">
                <i class="fas fa-shopping-cart"></i>
                Додати до кошика
            </button>

            <div v-if="boxQty" class="pcard__box">
                <i class="fas fa-cubes"></i>
                У ящику: {{ boxQty }} шт
            </div>
        </div>
    </article>
</template>

<script>
export default {
    name: 'ProductCard',
    data() {
        return {
            quantity: 1,
            product: {
                id: null,
                name: '',
                price: 0,
                discount: 0,
                image_path: '',
                url: '',
                articule: '',
                availability: 1,
                is_wholesale: false,
                wholesale_price: 0,
                wholesale_min_quantity: 0,
                box_qty: null,
            },
            variant: 'default',
        };
    },
    computed: {
        cardClass() {
            return {
                [`variant-${this.variant}`]: true,
                'has-discount': this.product.discount > 0,
                'out-of-stock': !this.isInStock,
            };
        },
        isInStock() {
            const value = this.product.availability;
            return value === 'in_stock' || value === 1 || value === '1' || value === true || value == null || value === '';
        },
        boxQty() {
            if (this.product.box_qty) return this.product.box_qty;
            return null;
        },
    },
    mounted() {
        this.product.id = parseInt(this.$el.getAttribute('id'), 10) || null;
        this.product.name = this.$el.getAttribute('name') || '';
        this.product.price = parseFloat(this.$el.getAttribute('price')) || 0;
        this.product.discount = parseFloat(this.$el.getAttribute('discount')) || 0;
        this.product.image_path = this.$el.getAttribute('image_path') || '';
        this.product.url = this.$el.getAttribute('url') || '';
        this.product.articule = this.$el.getAttribute('articule') || '';
        this.product.availability = this.$el.getAttribute('availability') || 1;
        this.product.is_wholesale = this.$el.getAttribute('is_wholesale') === '1' || this.$el.getAttribute('is_wholesale') === 'true';
        this.product.wholesale_price = parseFloat(this.$el.getAttribute('wholesale_price')) || 0;
        this.product.wholesale_min_quantity = parseInt(this.$el.getAttribute('wholesale_min_quantity'), 10) || 0;
        this.product.box_qty = parseInt(this.$el.getAttribute('box_qty'), 10) || null;
        this.variant = this.$el.getAttribute('variant') || 'default';
    },
    methods: {
        finalPrice(product) {
            if (product.discount > 0) {
                return product.price * (1 - product.discount / 100);
            }
            return product.price;
        },
        formatPrice(price) {
            return Math.round(parseFloat(price) || 0).toLocaleString('uk-UA');
        },
        changeQty(delta) {
            this.quantity = Math.max(1, this.quantity + delta);
        },
        onQtyInput(event) {
            const raw = parseInt(event.target.value, 10);
            this.quantity = Number.isFinite(raw) && raw > 0 ? raw : 1;
        },
        toggleWishlist(productId) {
            let wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
            const existingIndex = wishlist.findIndex((item) => item.id === productId);

            if (existingIndex > -1) {
                wishlist.splice(existingIndex, 1);
            } else {
                wishlist.push({
                    id: productId,
                    name: this.product.name,
                    price: this.product.price,
                    image: this.product.image_path,
                });
            }

            localStorage.setItem('wishlist', JSON.stringify(wishlist));
            window.dispatchEvent(new Event('wishlist-updated'));
        },
        isInWishlist(productId) {
            const wishlist = JSON.parse(localStorage.getItem('wishlist')) || [];
            return wishlist.some((item) => item.id === productId);
        },
        addToCart() {
            if (!this.isInStock) return;
            if (typeof window.addToCart === 'function') {
                window.addToCart(
                    this.product.id,
                    this.product.name,
                    this.finalPrice(this.product),
                    this.product.image_path || '',
                    this.product.articule || 'Не вказано',
                    this.product.availability || 'in_stock',
                    this.quantity,
                    !!this.product.is_wholesale,
                    this.product.wholesale_price || 0,
                    this.product.wholesale_min_quantity || 0
                );
            }
        },
    },
};
</script>
