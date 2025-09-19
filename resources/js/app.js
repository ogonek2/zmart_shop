// Импортируем Vue
import { createApp } from 'vue';

// Импортируем компоненты
import YourComponent from './components/YourComponent.vue';
import ProductList from './components/ProductList.vue';
import CartButton from './components/CartButton.vue';
import OpenCartButton from './components/OpenCartButton.vue';
import MiniCart from './components/MiniCart.vue';
import WishlistButton from './components/WishlistButton.vue';
import WishlistPanel from './components/WishlistPanel.vue';
import ToastNotification from './components/ToastNotification.vue';
import CatalogProductList from './components/CatalogProductList.vue';
import CartList from './components/CartList.vue';
import Search from './components/Search.vue';
import ProductCard from './components/ProductCard.vue';

// Ждем загрузки DOM
document.addEventListener('DOMContentLoaded', function() {
    try {
        // Создаем Vue приложение
        const app = createApp({});

        // Регистрируем компоненты глобально
        app.component('your-component', YourComponent);
        app.component('product-list', ProductList);
        app.component('cart-button', CartButton);
        app.component('open-cart-button', OpenCartButton);
        app.component('mini-cart', MiniCart);
        app.component('wishlist-button', WishlistButton);
        app.component('wishlist-panel', WishlistPanel);
        app.component('toast-notification', ToastNotification);
        app.component('catalog-product-list', CatalogProductList);
        app.component('cart-list', CartList);
        app.component('search', Search);
        app.component('product-card', ProductCard);

        // Монтируем приложение
        app.mount('#app');
        
        console.log('Vue приложение успешно инициализировано');
    } catch (error) {
        console.error('Ошибка инициализации Vue приложения:', error);
    }
});
