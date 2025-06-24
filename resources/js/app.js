import { createApp } from 'vue';
import YourComponent from './components/YourComponent.vue';
import ProductList from './components/ProductList.vue';
import CartButton from './components/CartButton.vue';
import OpenCartButton from './components/OpenCartButton.vue';
import MiniCart from './components/MiniCart.vue';
import CatalogProductList from './components/CatalogProductList.vue';

const app = createApp({});

// Регистрируем компоненты
app.component('your-component', YourComponent);
app.component('product-list', ProductList);
app.component('cart-button', CartButton);
app.component('open-cart-button', OpenCartButton);
app.component('mini-cart', MiniCart);
app.component('catalog-product-list', CatalogProductList);


app.mount('#app');
