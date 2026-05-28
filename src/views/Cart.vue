<template>
  <div class="container mt-4">
    <PageHeader title="Your Cart" />

    <ErrorAlert :message="err" />
    <div v-if="isLoading" class="text-center py-5">Loading cart...</div>

    <!-- Empty cart -->
    <EmptyState
      v-else-if="items.length === 0"
      message="Your cart is empty."
      link-to="/products"
      link-label="Browse Products"
    />

    <!-- Cart items -->
    <div v-else>
      <div v-for="item in items" :key="item.id" class="card mb-3">
        <div class="card-body d-flex align-items-center gap-3">
          <img :src="item.image" :alt="item.name" class="cart-thumb" />

          <div class="flex-grow-1">
            <h5 class="mb-1">{{ item.name }}</h5>
            <p class="text-muted mb-2">
              ${{ Number(item.price).toFixed(2) }} each
            </p>

            <!-- Quantity controls -->
            <div class="d-flex align-items-center gap-2">
              <button
                class="btn btn-sm btn-outline-secondary"
                @click="decreaseQty(item)"
              >
                −
              </button>
              <span class="px-2">{{ item.quantity }}</span>
              <button
                class="btn btn-sm btn-outline-secondary"
                @click="increaseQty(item)"
              >
                +
              </button>
            </div>
          </div>

          <div class="text-end">
            <p class="fw-bold mb-2">
              ${{ (item.price * item.quantity).toFixed(2) }}
            </p>
            <button class="btn btn-sm btn-danger" @click="deleteItem(item.id)">
              Remove
            </button>
          </div>
        </div>
      </div>

      <!-- Total + Checkout -->
      <div class="card mt-3">
        <div
          class="card-body d-flex justify-content-between align-items-center"
        >
          <h4 class="mb-0">Total: ${{ totalPrice }}</h4>
          <router-link to="/checkout" class="btn btn-primary"
            >Proceed to Checkout</router-link
          >
        </div>
      </div>
    </div>

    <!-- Auth Modal — shown when unauthenticated user tries to view cart (advanced feature - tuan) -->
    <AuthPromptModal
      :show="showAuthModal"
      :message="authModalMessage"
      @cancel="onModalCancel"
    />
  </div>
</template>

<script>
import { removeFromCart, updateCartQuantity, getCart } from '../api/cart.js'
import ErrorAlert from '../components/ErrorAlert.vue'
import PageHeader from '../components/PageHeader.vue'
import EmptyState from '../components/EmptyState.vue'
import AuthPromptModal from '../components/AuthPromptModal.vue'
import { useAuth } from '../composables/useAuth.js'
import { readAuthSession, clearAuthSession } from '../utils/authSession.js'

export default {
  name: 'Cart',
  components: { ErrorAlert, PageHeader, EmptyState, AuthPromptModal },
  setup() {
    var auth = useAuth()
    return {
      showAuthModal: auth.showAuthModal,
      authModalMessage: auth.authModalMessage,
      closeAuthModal: auth.closeAuthModal
    }
  },
  data() {
    return {
      items: [],
      isLoading: false,
      err: ''
    }
  },
  computed: {
    totalPrice() {
      return this.items
        .reduce((sum, item) => sum + item.price * item.quantity, 0)
        .toFixed(2)
    }
  },
  mounted() {
    var self = this

    // Retrieve stored details from authSession.
    var session = readAuthSession()
    if (session && session.user) {
      self.$store.commit('setUser', session.user)
      self.$store.commit('setRememberMe', !!session.rememberMe)
      if (session.expiresAt) {
        self.$store.commit('setExpiresAt', session.expiresAt)
      }
    }

    // Check if session is fresh (not just if user exists)
    var hasFreshAuth = Boolean(
      session && session.expiresAt && Date.now() < session.expiresAt
    )
    var isExpired =
      session && session.user && !hasFreshAuth && !self.$store.state.rememberMe

    // Handle expiry directly
    if (isExpired) {
      clearAuthSession()
      self.$store.commit('logout')
      self.showAuthModal = true
      self.authModalMessage = 'Your session has expired. Please log in again.'
      return
    }

    // Handle no user/guest edge case
    if (!self.$store.state.user) {
      self.showAuthModal = true
      return
    }

    // If session is valid, load cart
    var userId = self.$store.state.user.id

    self.isLoading = true
    getCart(userId)
      .then(data => {
        self.items = data
        self.isLoading = false
        self.$store.commit('setCart', data)
      })
      .catch(error => {
        self.err = 'Failed to load cart.'
        self.isLoading = false
      })
  },
  methods: {
    onModalCancel() {
      // User clicked "Cancel" on auth modal (advanced feature - tuan)
      this.closeAuthModal()
      this.$router.push('/home')
    },
    deleteItem(cartId) {
      var self = this
      removeFromCart(cartId)
        .then(() => {
          self.items = self.items.filter(i => i.id !== cartId)
          self.$store.dispatch('fetchCart')
        })
        .catch(() => {
          self.err = 'Failed to remove item.'
        })
    },
    increaseQty(item) {
      var self = this
      if (parseInt(item.quantity) >= parseInt(item.stock)) {
        self.err =
          'Cannot exceed available stock (' + item.stock + ' available)'
        return
      }

      var newQty = parseInt(item.quantity) + 1
      updateCartQuantity(item.id, newQty)
        .then(data => {
          if (data.success) {
            item.quantity = newQty
            self.err = ''
            self.$store.dispatch('fetchCart')
          }
        })
        .catch(() => {
          self.err = 'Failed to update quantity.'
        })
    },
    decreaseQty(item) {
      var self = this
      if (parseInt(item.quantity) <= 1) {
        self.deleteItem(item.id)
        return
      }

      var newQty = parseInt(item.quantity) - 1
      updateCartQuantity(item.id, newQty)
        .then(data => {
          if (data.success) {
            item.quantity = newQty
            self.$store.dispatch('fetchCart')
          }
        })
        .catch(() => {
          self.err = 'Failed to update quantity.'
        })
    }
  }
}
</script>
