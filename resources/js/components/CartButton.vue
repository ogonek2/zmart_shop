<template>
    <button class="btn" :class="inCart ? 'btn-dark text-white' : 'border border-secondary'" @click="toggleCart">
        <i class="fa fa-shopping-basket" aria-hidden="true"></i>
    </button>
</template>

<script>
export default {
    name: "CartButton",
    props: {
        id: {
            type: [String, Number],
            required: true
        },
        name: {
            type: [String],
            required: true
        },
        image: {
            type: [String],
            required: true
        },
        price: {
            type: [String],
            required: true
        },
    },
    data() {
        return {
            inCart: false
        };
    },
    mounted() {
        this.checkCart();
        window.addEventListener('cart-updated', this.checkCart);
    },
    unmounted() {
        window.removeEventListener('cart-updated', this.checkCart);
    },
    methods: {
        checkCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            this.inCart = cart.some(item => item.id == this.id);
        },
        toggleCart() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];

            const index = cart.findIndex(item => item.id == this.id);

            if (index >= 0) {
                // Удаляем
                cart.splice(index, 1);
                this.inCart = false;
            } else {
                // Добавляем
                cart.push({ 
                    id: this.id,
                    name: this.name,
                    price: this.price,
                    image: this.image,
                    quantity: 1 
                });
                this.inCart = true;
            }

            localStorage.setItem('cart', JSON.stringify(cart));
            window.dispatchEvent(new Event('cart-updated'));
        }
    }
};
</script>