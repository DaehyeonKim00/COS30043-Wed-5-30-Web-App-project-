import { createRouter, createWebHistory } from "vue-router";
import { store } from "../store";
import { clearAuthSession, readAuthSession } from "../utils/authSession.js";

import Home from "../views/Home.vue";
import ProductList from "../views/ProductList.vue";
import ProductDetail from "../views/ProductDetail.vue";
import Cart from "../views/Cart.vue";
import Checkout from "../views/Checkout.vue";
import Register from "../views/Register.vue";
import Login from "../views/Login.vue";

import About from "../views/About.vue";
import MyPage from "../views/MyPage.vue";
import OrderHistory from "../views/OrderHistory.vue";
import Review from "../views/Review.vue";
import Admin from "../views/Admin.vue";

const routes = [
  {
    path: "/",
    redirect: "/home",
  },
  {
    path: "/home",
    name: "Home",
    component: Home,
  },
  {
    path: "/products",
    name: "Products",
    component: ProductList,
  },
  {
    path: "/products/:id",
    name: "ProductDetail",
    component: ProductDetail,
  },
  {
    path: "/cart",
    name: "Cart",
    component: Cart,
  },
  {
    path: "/checkout",
    name: "Checkout",
    component: Checkout,
  },
  {
    path: "/register",
    name: "Register",
    component: Register,
  },
  {
    path: "/login",
    name: "Login",
    component: Login,
  },
  {
    path: "/about",
    name: "About",
    component: About,
  },
  {
    path: "/mypage",
    name: "MyPage",
    component: MyPage,
    meta: { requiresAuth: true, requiresFreshAuth: true },
  },
  {
    path: "/orderhistory",
    name: "OrderHistory",
    component: OrderHistory,
    meta: { requiresAuth: true, requiresFreshAuth: true },
  },
  {
    path: "/review",
    name: "Review",
    component: Review,
  },
  {
    path: "/admin",
    name: "Admin",
    component: Admin,
    meta: { requiresAdmin: true, requiresFreshAuth: true },
  },

  {
    path: "/:pathMatch(.*)*",
    redirect: "/home",
  },
];

const router = createRouter({
  history: createWebHistory("/cos30043/s104838522/test/"),
  routes,
});

// Route guard: handle admin and auth-protected pages
router.beforeEach(async (to) => {
  // Retrieve from browser storage if needed.
  if (!store.state.user) {
    const savedSession = readAuthSession();
    if (savedSession.user) {
      store.commit("setUser", savedSession.user);
      store.commit("setRememberMe", savedSession.rememberMe);
      store.commit("setExpiresAt", savedSession.expiresAt);
    }
  }

  const hasFreshAuth = Boolean(
    store.state.expiresAt && Date.now() < store.state.expiresAt,
  );

  // Routes that require a fresh JWT check only do so when the user did not
  // choose "Keep me logged in" before authentication.
  if (to.meta.requiresFreshAuth && !store.state.rememberMe) {
    if (!hasFreshAuth) {
      clearAuthSession();
      store.commit("logout");
      return "/login";
    }

    // If the session is fresh, keep the store in sync and allow navigation.
    if (!store.state.user) {
      const savedSession = readAuthSession();
      if (savedSession.user) {
        store.commit("setUser", savedSession.user);
        store.commit("setRememberMe", savedSession.rememberMe);
        store.commit("setExpiresAt", savedSession.expiresAt);
      }
    }
  }

  // Admin-only routes
  if (to.meta.requiresAdmin) {
    var user = store.state.user;
    if (!user || user.role !== "admin") {
      return "/home";
    }
    return true;
  }

  // General auth-required routes
  if (to.meta.requiresAuth) {
    // If we already have a user in the store, allow navigation.
    if (store.state.user) return true;

    // Try to restore from browser storage one more time if the store is empty.
    const savedSession = readAuthSession();
    if (savedSession.user) {
      store.commit("setUser", savedSession.user);
      store.commit("setRememberMe", savedSession.rememberMe);
      store.commit("setExpiresAt", savedSession.expiresAt);
      return true;
    }

    // Nothing found: redirect to login
    return "/login";
  }

  return true;
});

export default router;
