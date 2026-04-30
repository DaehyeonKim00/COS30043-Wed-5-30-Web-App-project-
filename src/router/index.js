import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import ProductList from '../views/ProductList.vue'
import ProductDetail from '../views/ProductDetail.vue'
import Cart from '../views/Cart.vue'
import Checkout from '../views/Checkout.vue'
import Register from '../views/Register.vue'
import Login from '../views/Login.vue'
import MyPage from '../views/MyPage.vue'
import OrderHistory from '../views/OrderHistory.vue'
import Review from '../views/Review.vue'
import Admin from '../views/Admin.vue'
import About from '../views/About.vue'

const routes = [
  { path: '/', component: Home },
  { path: '/products', component: ProductList },
  { path: '/products/:id', component: ProductDetail },
  { path: '/register', component: Register },
  { path: '/login', component: Login },
  { path: '/about', component: About },
  //Authentication required routes 
  { path: '/cart', component: Cart, meta: { requiresAuth: true } },
  { path: '/checkout', component: Checkout, meta: { requiresAuth: true } },
  { path: '/mypage', component: MyPage, meta: { requiresAuth: true } },
  { path: '/orders', component: OrderHistory, meta: { requiresAuth: true } },
  { path: '/review', component: Review, meta: { requiresAuth: true } },
  { path: '/admin', component: Admin, meta: { requiresAuth: true } }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
    // Check if user is logged in by retrieving user data from localStorage
  const isLoggedIn = localStorage.getItem('user') // we can change 'user'
  if (to.meta.requiresAuth && !isLoggedIn) {
    next('/login') // move to login page if not authenticated
  } else {
    next() // move to the requested page
  }
})

export default router

