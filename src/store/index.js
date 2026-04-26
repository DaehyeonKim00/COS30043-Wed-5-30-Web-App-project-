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

  // !!!!Below codes are Example codes!!!!!
  // read state (used in Phase 3+)
  // getters: {
  //   cartCount: state => {
  //     return state.cart.length
  //   },
  //   cartTotal: state => {
  //     return state.cart.reduce((total, item) => total + item.price * item.quantity, 0)
  //   },
  //   wishlistCount: state => {
  //     return state.wishlist.length
  //   }
  // },

  // change state (used in Phase 3+)
  // mutations: {
  //   setUser(state, user) {
  //     state.user = user
  //     state.isLoggedIn = true
  //   },
  //   logout(state) {
  //     state.user = null
  //     state.isLoggedIn = false
  //     state.cart = []
  //     state.wishlist = []
  //   },
  //   addToCart(state, product) {
  //     state.cart.push(product)
  //   },
  //   removeFromCart(state, productId) {
  //     state.cart = state.cart.filter(item => item.id !== productId)
  //   },
  //   addToWishlist(state, product) {
  //     state.wishlist.push(product)
  //   },
  //   removeFromWishlist(state, productId) {
  //     state.wishlist = state.wishlist.filter(item => item.id !== productId)
  //   }
  // },

  // async operations (used in Phase 3+)
  // actions: {
  //   loginUser({ commit }, userData) {
  //     commit('setUser', userData)
  //   }
  // }
})
