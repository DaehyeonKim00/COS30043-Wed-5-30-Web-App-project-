// src/composables/useCart.js
// Advanced feature - This file defines a Vue 3 composable that provides reactive state
// and functions for managing the shopping cart.
// It uses the cart API functions defined in src/api/cart.js and synchronises with the
// Vuex store so components can easily access and modify the cart without dealing with API calls directly.
import { computed } from 'vue'
import { useStore } from 'vuex'
import { addToCart, removeFromCart, updateCartQuantity } from '../api/cart.js'

export function useCart() {
  const store = useStore()

  const cart = computed(() => store.state.cart)
  const cartCount = computed(() => store.state.cart.length)
  const cartTotal = computed(() => {
    return store.state.cart.reduce((total, item) => {
      return total + (parseFloat(item.price) * parseInt(item.quantity))
    }, 0).toFixed(2)
  })

  function addItem(userId, productId, quantity = 1) {
    return addToCart(userId, productId, quantity)
      .then(data => {
        if (data.success) {
          store.dispatch('fetchCart')
        }
        return data
      })
  }

  function removeItem(cartId) {
    return removeFromCart(cartId)
      .then(data => {
        store.dispatch('fetchCart')
        return data
      })
  }

  function updateQuantity(cartId, quantity) {
    return updateCartQuantity(cartId, quantity)
      .then(data => {
        if (data.success) {
          store.dispatch('fetchCart')
        }
        return data
      })
  }

  return { cart, cartCount, cartTotal, addItem, removeItem, updateQuantity }
}