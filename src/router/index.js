import { createRouter, createWebHistory } from 'vue-router'
import { store } from '../store'

import Home from '../views/Home.vue'
import ProductList from '../views/ProductList.vue'
import ProductDetail from '../views/ProductDetail.vue'
import Cart from '../views/Cart.vue'
import Checkout from '../views/Checkout.vue'
import Register from '../views/Register.vue'
import Login from '../views/Login.vue'

import About from '../views/About.vue'
import MyPage from '../views/MyPage.vue'
import OrderHistory from '../views/OrderHistory.vue'
import Review from '../views/Review.vue'
import Admin from '../views/Admin.vue'

const routes = [
  {
    path: '/',
    redirect: '/home'
  },
  {
    path: '/home',
    name: 'Home',
    component: Home
  },
  {
    path: '/products',
    name: 'Products',
    component: ProductList
  },
  {
    path: '/products/:id',
    name: 'ProductDetail',
    component: ProductDetail
  },
  {
    path: '/cart',
    name: 'Cart',
    component: Cart
  },
  {
    path: '/checkout',
    name: 'Checkout',
    component: Checkout
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },

  // Dinupa pages
  {
    path: '/about',
    name: 'About',
    component: About
  },
  {
    path: '/mypage',
    name: 'MyPage',
    component: MyPage
  },
  {
    path: '/orderhistory',
    name: 'OrderHistory',
    component: OrderHistory
  },
  {
    path: '/review',
    name: 'Review',
    component: Review
  },
  {
    path: '/admin',
    name: 'Admin',
    component: Admin,
    meta: { requiresAdmin: true }
  },

  {
    path: '/:pathMatch(.*)*',
    redirect: '/home'
  }
]

const router = createRouter({
  history: createWebHistory('/cos30043/s104838522/test/'),
  routes
})

// Route guard: block non-admin users from admin-only pages
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAdmin) {
    var user = store.state.user
    if (user && user.role === 'admin') {
      next()
    } else {
      next('/home')
    }
  } else {
    next()
  }
})

export default router