import { createApp } from 'vue';
import YourComponent from './components/YourComponent.vue';
import ProductList from './components/ProductList.vue';

const app = createApp({});

// Регистрируем компоненты
app.component('your-component', YourComponent);
app.component('product-list', ProductList);


app.mount('#app');
