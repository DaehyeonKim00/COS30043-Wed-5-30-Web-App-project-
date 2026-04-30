<template>
  <div>

    <h2 class="mb-4">All Products</h2>

    <!-- Filter Section -->
    <div class="row g-3 mb-4">

      <!-- Name search -->
      <div class="col-12 col-md-5">
        <label for="filterName" class="form-label">Search by Name</label>
        <input
          id="filterName"
          type="text"
          class="form-control"
          placeholder="e.g. Guitar"
          v-model="filter.name"
        />
      </div>

      <!-- Category filter -->
      <div class="col-12 col-md-4">
        <label for="filterCategory" class="form-label">Category</label>
        <select
          id="filterCategory"
          class="form-select"
          v-model="filter.category"
        >
          <option value="">All</option>
          <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
        </select>
      </div>

      <!-- Items per page -->
      <div class="col-12 col-md-3">
        <label for="perPage" class="form-label">Items per page</label>
        <select
          id="perPage"
          class="form-select"
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

      <!-- Result count -->
      <p class="text-muted mb-3">{{ filteredProducts.length }} product(s) found</p>

      <!-- No products found -->
      <p v-if="filteredProducts.length === 0" class="text-muted">No products match your search.</p>

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

    </div>

  </div>
</template>

<script>
import { getProducts } from '../api/productList.js'
import ProductCard from '../components/ProductCard.vue'
import PaginationBar, { calcPageCount, getPaginatedItems } from '../components/PaginationBar.vue'

export default {
  name: 'ProductList',
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
      perPage: 12,
      filter: {
        name: '',
        category: ''
      }
    }
  },
  computed: {
    // Unique category list derived from loaded products
    categories() {
      return [...new Set(this.products.map(p => p.category))]
    },
    // Filter by name and category (applookup2.js filteredUnits pattern)
    filteredProducts() {
      return this.products.filter(p =>
        (p.name || '').toLowerCase().match(this.filter.name.toLowerCase()) &&
        (p.category || '').match(this.filter.category)
      )
    },
    // Imported from PaginationBar.vue
    getPageCount() {
      return calcPageCount(this.filteredProducts, this.perPage)
    },
    getItems() {
      return getPaginatedItems(this.filteredProducts, this.currentPage, this.perPage)
    }
  },
  watch: {
    perPage() {
      this.currentPage = 1
    },
    'filter.name'() {
      this.currentPage = 1
    },
    'filter.category'() {
      this.currentPage = 1
    }
  },
  mounted() {
    var self = this
    self.isLoading = true
    getProducts()
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
</style>
