// src/composables/useCart.js
// Advanced feature - This file defines a Vue 3 composable that provides reactive state and functions for managing the shopping cart.
// It uses the cart API functions defined in src/api/cart.js and synchronises with the Vuex store so components can easily access and modify the cart without dealing with API calls directly.
import { useStore } from 'vuex'
import { addToCart } from '../api/cart.js'

export function useCart() {
  var store = useStore()

  function addItem(userId, productId, quantity = 1) {
    return addToCart(userId, productId, quantity)
      .then(data => {
        if (data.success) {
          store.dispatch('fetchCart')
        }
        return data
      })
  }

  return { addItem }
}
