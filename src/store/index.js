import { createStore } from 'vuex'

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

    // ===== Below are example mutations for Phase 3+ (not used yet) =====
    // addToCart(state, product) {
    //   state.cart.push(product)
    // },
    // removeFromCart(state, productId) {
    //   state.cart = state.cart.filter(item => item.id !== productId)
    // },
    // addToWishlist(state, product) {
    //   state.wishlist.push(product)
    // },
    // removeFromWishlist(state, productId) {
    //   state.wishlist = state.wishlist.filter(item => item.id !== productId)
    // }
  }

  // getters / actions stay disabled until Phase 3+ (see git history)
})
