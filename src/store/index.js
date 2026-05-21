import { createStore } from 'vuex'
import { getCart } from '../api/cart.js'

export const store = createStore({
  // shared data
  state() {
    return {
      user: null,
      isLoggedIn: false,
      cart: [],
      wishlist: []
    }
  },

  // change state
  mutations: {
    setUser(state, user) {
      state.user = user
      state.isLoggedIn = true
    },
    logout(state) {
      state.user = null
      state.isLoggedIn = false
      state.cart = []
      state.wishlist = []
    },

    setCart(state, cart) {
      state.cart = cart
    },
    addToCart(state, product) {
      state.cart.push(product)
    },
    removeFromCart(state, productId) {
      state.cart = state.cart.filter(item => item.id !== productId)
    }
  },

  actions: {
    // Pull the server-side cart into Vuex so Navbar count stays in sync.
    // Call this after login, after add/update/remove, and on app startup
    // (when restoring a logged-in user from localStorage).
    fetchCart({ commit, state }) {
      if (!state.user) return Promise.resolve()
      return getCart(state.user.id)
        .then(data => {
          commit('setCart', Array.isArray(data) ? data : [])
        })
        .catch(err => {
          console.error('fetchCart failed:', err)
        })
    }
  }
})
