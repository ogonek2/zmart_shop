<template>
    <div class="search-wrapper d-flex align-items-center rounded-pill bg-light position-relative" style="height: 40px;"
        @click.stop>
        <input v-model="query" @input="searchProducts" @focus="showDropdown = true" type="text"
            class="form-control border-0 bg-transparent shadow-none" placeholder="Поиск" style="font-size: 16px;" />
        <button class="btn btn-dark rounded-pill d-flex align-items-center justify-content-center ms-2"
            style="width: 80px; height: 40px;">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>

        <!-- Подсказки с карточками -->
        <div v-if="showDropdown && suggestions.length" class="position-absolute bg-white shadow rounded mt-2 p-2"
            style="top: 100%; left: 0; width: 100%; min-width: 340px; z-index: 1000;">
            <div v-for="product in suggestions" :key="product.id" class="d-flex align-items-center mb-2 pb-2 gap-2">
                <div style="cursor: pointer;" @click="selectProduct(product)">
                    <img v-if="product.image_path" style="width: 50px; height: 50px; object-fit: contain;"
                        :src="product.image_path" alt="">
                    <div v-else class="d-flex bg-light align-items-center flex-column justify-content-center"
                        style="width: 50px; height: 50px;">
                        <small class="text-secondary"><i class="fas fa-image"></i></small>
                    </div>
                </div>
                <div class="flex-grow-1" style="cursor: pointer;" @click="selectProduct(product)">
                    <div class="d-flex gap-2 aligm-items-center"><div class="fw-bold truncated-text">{{ product.name }}</div> <b class="bg-danger p-1 text-white rounded-2" style="font-size: 12px; height: fit-content;" v-if="product.discount > 0">-{{ product.discount }}%</b></div>
                    <span style="font-size: 14px" v-if="product.discount > 0">{{ product.price - (product.price *
                        product.discount / 100) }} ₴</span>
                    <span style="font-size: 14px" v-else>{{ product.price }} ₴</span>
                    <small v-if="product.discount > 0" class="text-danger" style="text-decoration: line-through; font-size: 12px; margin-left: 5px;">{{ product.price }} ₴</small>
                </div>
                <div class="ml-auto align-self-end">
                    <CartButton :id="product.id" :name="product.name" :price="product.discount > 0
                        ? (product.price - (product.price * product.discount / 100))
                        : product.price" :image="product.image_path" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import CartButton from './CartButton.vue';


export default {
    name: "Search",
    components: {
        CartButton
    },
    data() {
        return {
            query: '',
            suggestions: [],
            showDropdown: false,
        };
    },
    methods: {
        searchProducts() {
            if (this.query.length < 2) {
                this.suggestions = [];
                return;
            }

            axios
                .get('/api/products/search?q=' + this.query)
                .then((response) => {
                    this.suggestions = response.data;
                });
        },
        selectProduct(product) {
            window.location.href = '/catalog/' + product.url;
        },
        closeDropdown() {
            this.showDropdown = false;
        },
        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.closeDropdown();
            }
        },
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeDestroy() {
        document.removeEventListener('click', this.handleClickOutside);
    },
};
</script>
