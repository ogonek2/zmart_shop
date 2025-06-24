<template>
    <button class="btn text-white-50" @click="toggleCart">
        <i class="fa fa-shopping-basket" aria-hidden="true"></i> Кошик
    </button>
</template>

<script>
export default {
    name: "OpenCartButton",
    methods: {
        toggleCart() {
            // Отправляем глобальное событие
            window.dispatchEvent(new Event('toggle-mini-cart'));
        }
    }
};
</script>
