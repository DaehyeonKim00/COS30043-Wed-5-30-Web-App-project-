<template>
  <div>

    <!-- Loading state -->
    <LoadingSpinner v-if="isLoading" />

    <!-- Error state -->
    <ErrorAlert v-else-if="err" :message="err" />

    <!-- Product detail -->
    <div v-else-if="product">

      <!-- Back button -->
      <BackButton to="/products" label="Back to Products" />

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
            <button class="btn btn-dark" :disabled="product.stock === 0" @click="addToCartHandler">
              Add to Cart
            </button>

            <!-- View Reviews link -->
            <router-link
              :to="{ path: '/review', query: { product_id: product.id } }"
              class="btn btn-outline-dark"
            >
              View Reviews
            </router-link>

          </div>

          <!-- Not logged in -->
          <div v-else>
            <p class="text-muted">
              <router-link to="/login">Log in</router-link> to add to wishlist or cart.
            </p>
            <router-link
              :to="{ path: '/review', query: { product_id: product.id } }"
              class="btn btn-outline-dark"
            >
              View Reviews
            </router-link>
          </div>

          <!-- Feedback message -->
          <div v-if="msg" class="alert alert-success mt-3 py-2">{{ msg }}</div>

        </div>
      </div>

    </div>

    <!-- Recommended Products - For avdanced feature --> 
    <div v-if="recommendedProducts.length > 0" class="mt-5">
      <h4 class="fw-bold mb-4">You May Also Like</h4>
      <div class="row g-4">
        <div
          v-for="item in recommendedProducts"
          :key="item.id"
          class="col-12 col-sm-6 col-md-3"
        >
          <ProductCard :product="item" />
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import { getCart, addToCart } from '../api/cart.js'
import { getWishlist, addToWishlist, removeFromWishlist } from '../api/wishlist.js'
import { getProductById, getRecommendedProducts } from '../api/productDetail.js' // For advanced feature (tuan)
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'
import BackButton from '../components/BackButton.vue'
import ProductCard from '../components/ProductCard.vue'

export default {
  name: 'ProductDetail',
  components: { LoadingSpinner, ErrorAlert, BackButton, ProductCard },
  data() {
    return {
      product: null,
      isLoading: false,
      err: '',
      msg: '',
      inWishlist: false,
      recommendedProducts: [] // For advanced feature (tuan)
    }
  },
  computed: {
    // Reactive: reflects Vuex user (clears immediately on logout)
    user() {
      return this.$store.state.user
    }
  },
  mounted() {
    this.loadProduct(this.$route.params.id)
  },
  watch: {
    // Re-fetch when navigating between products (e.g. via "You May Also Like")
    '$route.params.id'(newId) {
      if (newId) {
        // Scroll to top so the user clearly sees the new product
        window.scrollTo({ top: 0, behavior: 'smooth' })
        this.loadProduct(newId)
      }
    }
  },
  methods: {
    loadProduct(productId) {
      var self = this
      self.err = ''
      self.msg = ''
      self.product = null
      self.recommendedProducts = []
      self.inWishlist = false
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

          // Fetch recommended products based on the same category
          getRecommendedProducts(data.category, productId)
            .then(products => {
              self.recommendedProducts = products
            })
        })
        .catch(error => {
          self.err = 'Failed to load product. Please try again later.'
          self.isLoading = false
        })
    },
    toggleWishlist() {
      var self = this
      if (!self.user) return
      self.err = ''
      self.msg = ''

      if (self.inWishlist) {
        removeFromWishlist(self.user.id, self.product.id)
          .then(data => {
            if (data && data.success) {
              self.inWishlist = false
              self.msg = 'Removed from wishlist.'
            } else {
              self.err = (data && data.error) || 'Failed to update wishlist.'
            }
          })
          .catch(error => {
            self.err = 'Failed to update wishlist.'
          })
      } else {
        addToWishlist(self.user.id, self.product.id)
          .then(data => {
            if (data && data.success) {
              self.inWishlist = true
              self.msg = 'Added to wishlist!'
            } else if (data && data.message === 'Already in wishlist') {
              // Backend says it's already saved — sync the UI state instead of erroring.
              self.inWishlist = true
              self.msg = 'Already in wishlist.'
            } else {
              self.err = 'Failed to update wishlist.'
            }
          })
          .catch(error => {
            self.err = 'Failed to update wishlist.'
          })
      }
    },
    addToCartHandler() {
    var self = this
    if (!self.user) {
      self.$router.push('/login')
      return
    }
    addToCart(self.user.id, self.product.id, 1)
      .then(data => {
        if (data.success) {
          self.msg = 'Added to cart!'
          // Refresh Vuex cart so the Navbar count updates immediately
          self.$store.dispatch('fetchCart')
        }
      })
      .catch(error => {
        self.err = 'Failed to add to cart.'
      })
  }
  }
}
</script>

