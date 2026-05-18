<template>
  <div class="container mt-4">
    <h2 class="mb-4">Your Cart</h2>
 
    <div v-if="err" class="alert alert-danger">{{ err }}</div>
    <div v-if="isLoading" class="text-center py-5">Loading cart...</div>
 
    <!-- Empty cart -->
    <div v-else-if="items.length === 0" class="text-center text-muted py-5">
      <p class="fs-5">Your cart is empty.</p>
      <router-link to="/products" class="btn btn-primary mt-3">Browse Products</router-link>
    </div>
 
    <!-- Cart items -->
    <div v-else>
      <div v-for="item in items" :key="item.id" class="card mb-3">
        <div class="card-body d-flex align-items-center gap-3">
 
          <img
            :src="item.image"
            :alt="item.name"
            style="width: 80px; height: 80px; object-fit: cover; border-radius: 6px;"
          />
 
          <div class="flex-grow-1">
            <h5 class="mb-1">{{ item.name }}</h5>
            <p class="text-muted mb-2">${{ Number(item.price).toFixed(2) }} each</p>
 
            <!-- Quantity controls -->
            <div class="d-flex align-items-center gap-2">
              <button class="btn btn-sm btn-outline-secondary" @click="decreaseQty(item)">−</button>
              <span class="px-2">{{ item.quantity }}</span>
              <button class="btn btn-sm btn-outline-secondary" @click="increaseQty(item)">+</button>
            </div>
          </div>
 
          <div class="text-end">
            <p class="fw-bold mb-2">${{ (item.price * item.quantity).toFixed(2) }}</p>
            <button class="btn btn-sm btn-danger" @click="deleteItem(item.id)">Remove</button>
          </div>
 
        </div>
      </div>
 
      <!-- Total + Checkout -->
      <div class="card mt-3">
        <div class="card-body d-flex justify-content-between align-items-center">
          <h4 class="mb-0">Total: ${{ totalPrice }}</h4>
          <router-link to="/checkout" class="btn btn-primary">Proceed to Checkout</router-link>
        </div>
      </div>
    </div>
  </div>
</template>
 
<script>
import { getCart, addToCart, removeFromCart } from '../api/cart.js'
 
export default {
  name: 'Cart',
  data() {
    return {
      items: [],
      isLoading: false,
      err: '',
      user: null
    }
  },
  computed: {
    totalPrice() {
      return this.items.reduce(function(sum, item) {
        return sum + item.price * item.quantity
      }, 0).toFixed(2)
    }
  },
  mounted() {
    var self = this
    self.user = JSON.parse(localStorage.getItem('user'))
    if (!self.user) {
      self.$router.push('/login')
      return
    }
    self.isLoading = true
    getCart(self.user.id)
      .then(function(data) {
        self.items = data
        self.isLoading = false
      })
      .catch(function() {
        self.err = 'Failed to load cart.'
        self.isLoading = false
      })
  },
  methods: {
    deleteItem(cartId) {
      var self = this
      removeFromCart(cartId)
        .then(function() {
          self.items = self.items.filter(function(i) { return i.id !== cartId })
        })
        .catch(function() { self.err = 'Failed to remove item.' })
    },
    increaseQty(item) {
      var self = this
      // No PUT in api_cart.php — delete then re-add with new quantity
      removeFromCart(item.id)
        .then(function() {
          return addToCart(self.user.id, item.product_id, item.quantity + 1)
        })
        .then(function() {
          item.quantity += 1
        })
        .catch(function() { self.err = 'Failed to update quantity.' })
    },
    decreaseQty(item) {
      var self = this
      if (item.quantity <= 1) {
        self.deleteItem(item.id)
        return
      }
      removeFromCart(item.id)
        .then(function() {
          return addToCart(self.user.id, item.product_id, item.quantity - 1)
        })
        .then(function() {
          item.quantity -= 1
        })
        .catch(function() { self.err = 'Failed to update quantity.' })
    }
  }
}
</script>
 
<style scoped>
</style>