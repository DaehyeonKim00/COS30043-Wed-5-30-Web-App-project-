<template>
  <div>

    <!-- Loading state -->
    <div v-if="isLoading" class="text-center py-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- Error state -->
    <div v-else-if="err" class="alert alert-danger">
      {{ err }}
    </div>

    <!-- Product detail -->
    <div v-else-if="product">

      <!-- Back button -->
      <router-link to="/products" class="btn btn-outline-secondary mb-4">
        &larr; Back to Products
      </router-link>

      <div class="row g-5">

        <!-- Left: Product image -->
        <div class="col-12 col-md-6">
          <img
            :src="product.image"
            :alt="product.name"
            class="img-fluid rounded shadow-sm w-100 product-image"
          />
        </div>

        <!-- Right: Product info -->
        <div class="col-12 col-md-6">

          <p class="text-muted mb-1">{{ product.category }}</p>
          <h2 class="fw-bold mb-3">{{ product.name }}</h2>
          <h4 class="text-dark mb-3">${{ product.price }}</h4>
          <p class="mb-4">{{ product.description }}</p>

          <!-- Stock status -->
          <p class="mb-4">
            <span v-if="product.stock > 0" class="badge bg-success">
              In Stock ({{ product.stock }} available)
            </span>
            <span v-else class="badge bg-danger">Out of Stock</span>
          </p>

          <!-- Action buttons (logged in only) -->
          <div v-if="user" class="d-flex flex-wrap gap-2">

            <!-- Wishlist toggle button -->
            <button
              class="btn"
              :class="inWishlist ? 'btn-danger' : 'btn-outline-danger'"
              @click="toggleWishlist"
            >
              {{ inWishlist ? '&hearts; Remove from Wishlist' : '&hearts; Add to Wishlist' }}
            </button>

            <!-- Add to Cart button -->
            <button class="btn btn-dark" :disabled="product.stock === 0">
              Add to Cart
            </button>

          </div>

          <!-- Not logged in -->
          <div v-else>
            <p class="text-muted">
              <router-link to="/login">Log in</router-link> to add to wishlist or cart.
            </p>
          </div>

          <!-- Feedback message -->
          <div v-if="msg" class="alert alert-success mt-3 py-2">{{ msg }}</div>

        </div>
      </div>

    </div>

  </div>
</template>

<script>
import { getProductById } from '../api/productDetail.js'
import { getWishlist, addToWishlist, removeFromWishlist } from '../api/wishlist.js'

export default {
  name: 'ProductDetail',
  data() {
    return {
      product: null,
      isLoading: false,
      err: '',
      msg: '',
      user: null,
      inWishlist: false
    }
  },
  mounted() {
    var self = this
    var productId = self.$route.params.id
    self.user = JSON.parse(localStorage.getItem('user'))

    self.isLoading = true
    getProductById(productId)
      .then(data => {
        self.product = data
        self.isLoading = false

        // Check wishlist status if user is logged in
        if (self.user) {
          getWishlist(self.user.id)
            .then(items => {
              self.inWishlist = items.some(item => item.product_id == productId)
            })
        }
      })
      .catch(error => {
        self.err = 'Failed to load product. Please try again later.'
        self.isLoading = false
      })
  },
  methods: {
    toggleWishlist() {
      var self = this
      if (!self.user) return

      if (self.inWishlist) {
        removeFromWishlist(self.user.id, self.product.id)
          .then(data => {
            if (data.success) {
              self.inWishlist = false
              self.msg = 'Removed from wishlist.'
            }
          })
          .catch(error => {
            self.err = 'Failed to update wishlist.'
          })
      } else {
        addToWishlist(self.user.id, self.product.id)
          .then(data => {
            if (data.success) {
              self.inWishlist = true
              self.msg = 'Added to wishlist!'
            }
          })
          .catch(error => {
            self.err = 'Failed to update wishlist.'
          })
      }
    }
  }
}
</script>

<style scoped>
.product-image {
  max-height: 450px;
  object-fit: cover;
}
</style>
