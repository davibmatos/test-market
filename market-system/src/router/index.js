import { createRouter, createWebHistory } from 'vue-router';
import ProductForm from '../views/ProductForm.vue';
import SaleScreen from '../views/SaleScreen.vue';
import Products from '../views/MyProducts.vue';
import ProductsType from '../views/MyProductsType.vue';
import Home from '../views/Home.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/add-product',
    name: 'AddProduct',
    component: ProductForm
  },
  {
    path: '/sales',
    name: 'Sales',
    component: SaleScreen
  },
  {
    path: '/products',
    name: 'Products',
    component: Products
  },
  {
    path: '/products-type',
    name: 'ProductsType',
    component: ProductsType
  }        
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
