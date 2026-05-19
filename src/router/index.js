import { createRouter, createWebHistory } from 'vue-router'

import Home from '../views/Home.vue'
import About from '../views/About.vue'
import Admin from '../views/Admin.vue'
import Review from '../views/Review.vue'
import OrderHistory from '../views/OrderHistory.vue'
import MyPage from '../views/MyPage.vue'
import Cart from '../views/Cart.vue'
import Checkout from '../views/Checkout.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import ProductList from '../views/ProductList.vue'
import ProductDetail from '../views/ProductDetail.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/home', component: Home },
  { path: '/about', component: About },
  { path: '/admin', component: Admin },
  { path: '/review', component: Review },
  { path: '/orderhistory', component: OrderHistory },
  { path: '/mypage', component: MyPage },
  { path: '/cart', component: Cart },
  { path: '/checkout', component: Checkout },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/products', component: ProductList },
  { path: '/products/:id', component: ProductDetail }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router