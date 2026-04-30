<template>
  <div>

    <!-- Hero Section -->
    <section class="hero rounded-3 bg-dark text-white text-center py-5 mb-5 px-3">
      <h1 class="display-5 fw-bold mb-3">Welcome to SwinMusic Shop</h1>
      <p class="lead mb-4">Find the best instruments and accessories for every musician</p>
      <router-link to="/products" class="btn btn-light btn-lg">Shop Now</router-link>
    </section>

    <!-- Featured Products Section -->
    <section>
      <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
        <h2 class="mb-0">Featured Products</h2>

        <!-- Items per page dropdown -->
        <div class="d-flex align-items-center gap-2">
          <label for="perPage" class="mb-0 text-nowrap">Items per page:</label>
          <select
            id="perPage"
            class="form-select w-auto"
            v-model="perPage"
          >
            <option :value="4">4</option>
            <option :value="8">8</option>
            <option :value="12">12</option>
            <option :value="24">24</option>
          </select>
        </div>
      </div>

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

      <!-- Products grid + pagination -->
      <div v-else>

        <!-- No products found -->
        <p v-if="products.length === 0" class="text-muted">No products available.</p>

        <!-- Product cards grid -->
        <div v-else class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
          <div
            class="col"
            v-for="product in getItems"
            :key="product.id"
          >
            <ProductCard :product="product" />
          </div>
        </div>

        <!-- Pagination bar -->
        <PaginationBar
          class="mt-4"
          :pageCount="getPageCount"
          @page-change="clickCallback"
        />

        <!-- View all products button -->
        <div class="text-center mt-4">
          <router-link to="/products" class="btn btn-outline-dark">
            View All Products
          </router-link>
        </div>

      </div>
    </section>

  </div>
</template>

<script>
import { getFeaturedProducts } from '../api/home.js'
import ProductCard from '../components/ProductCard.vue'
import PaginationBar, { calcPageCount, getPaginatedItems } from '../components/PaginationBar.vue'

export default {
  name: 'Home',
  components: {
    ProductCard,
    PaginationBar
  },
  data() {
    return {
      products: [],
      isLoading: false,
      err: '',
      msg: '',
      currentPage: 1,
      perPage: 12
    }
  },
  computed: {
    getPageCount() {
      return calcPageCount(this.products, this.perPage)
    },
    getItems() {
      return getPaginatedItems(this.products, this.currentPage, this.perPage)
    }
  },
  watch: {
    perPage() {
      this.currentPage = 1
    }
  },
  mounted() {
    var self = this
    self.isLoading = true
    getFeaturedProducts()
      .then(data => {
        self.products = data
        self.msg = 'Successful!'
        self.isLoading = false
      })
      .catch(error => {
        self.err = 'Failed to load products. Please try again later.'
        self.isLoading = false
      })
  },
  methods: {
    clickCallback(pageNum) {
      this.currentPage = Number(pageNum)
    }
  }
}
</script>

<style scoped>
.hero {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
}
</style>
