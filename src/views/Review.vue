<template>
  <div class="container py-5">
    <h1 class="mb-4">Product Reviews</h1>

    <!-- Product selector -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <label for="productSelect" class="form-label">Select a Product</label>
        <select
          id="productSelect"
          v-model="selectedProductId"
          class="form-select"
        >
          <option disabled value="">-- Choose a product --</option>
          <option
            v-for="product in products"
            :key="product.id"
            :value="product.id"
          >
            {{ product.name }}
          </option>
        </select>
      </div>
    </div>

    <!-- Review form (only when a product is selected) -->
    <div v-if="selectedProductId" class="card shadow-sm mb-4">
      <div class="card-body">
        <h3>{{ editingId ? 'Edit Review' : 'Write a Review' }}</h3>

        <div class="mb-3">
          <label class="form-label">Star Rating</label>
          <select v-model="form.rating" class="form-select">
            <option value="5">★★★★★ 5 Stars</option>
            <option value="4">★★★★ 4 Stars</option>
            <option value="3">★★★ 3 Stars</option>
            <option value="2">★★ 2 Stars</option>
            <option value="1">★ 1 Star</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Review</label>
          <textarea v-model="form.comment" class="form-control" rows="4"></textarea>
        </div>

        <button class="btn btn-primary me-2" @click="saveReview">
          {{ editingId ? 'Update Review' : 'Add Review' }}
        </button>

        <button v-if="editingId" class="btn btn-secondary" @click="cancelEdit">
          Cancel
        </button>

        <p v-if="msg" class="text-success mt-3">{{ msg }}</p>
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

    <!-- Review list -->
    <div v-else-if="selectedProductId">
      <p v-if="reviews.length === 0" class="text-muted">No reviews yet.</p>

      <div v-else class="row g-4">
        <div v-for="review in reviews" :key="review.id" class="col-md-6">
          <div class="card shadow-sm h-100">
            <div class="card-body">
              <h5>{{ review.name }}</h5>
              <p class="text-warning fs-4">{{ stars(review.rating) }}</p>
              <p>{{ review.comment }}</p>

              <button class="btn btn-sm btn-outline-primary me-2" @click="editReview(review)">
                Edit
              </button>

              <button class="btn btn-sm btn-outline-danger" @click="removeReview(review.id)">
                Delete
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getReviews, postReview, updateReview, deleteReview } from '../api/review.js'
import { getProducts } from '../api/productList.js'

export default {
  name: 'Review',
  data() {
    return {
      products: [],
      selectedProductId: '',
      reviews: [],
      isLoading: false,
      err: '',
      msg: '',
      editingId: null,
      form: {
        rating: '5',
        comment: ''
      },

      // ===== TEMPORARY (login not implemented yet) =====
      // Fixed user id so the page can be tested against the DB.
      userId: 1
      // ===== REAL CODE (use after login is implemented) =====
      // userId comes from the logged-in user:
      // userId: JSON.parse(localStorage.getItem('user')).id
      // (or from Vuex: this.$store.state.user.id)
    }
  },
  watch: {
    // Reload reviews whenever a different product is selected
    selectedProductId() {
      this.resetForm()
      this.loadReviews()
    }
  },
  mounted() {
    var self = this
    // Load the product list for the selector
    getProducts()
      .then(data => {
        self.products = data
      })
      .catch(error => {
        self.err = 'Failed to load products. Please try again later.'
      })
  },
  methods: {
    loadReviews() {
      var self = this
      if (!self.selectedProductId) return
      self.isLoading = true
      getReviews(self.selectedProductId)
        .then(data => {
          self.reviews = data
          self.isLoading = false
        })
        .catch(error => {
          self.err = 'Failed to load reviews. Please try again later.'
          self.isLoading = false
        })
    },
    stars(rating) {
      return '★'.repeat(rating) + '☆'.repeat(5 - rating)
    },
    saveReview() {
      var self = this
      if (!self.form.comment) {
        alert('Please enter your review.')
        return
      }

      if (self.editingId) {
        updateReview(self.editingId, Number(self.form.rating), self.form.comment)
          .then(data => {
            self.msg = 'Review updated successfully.'
            self.resetForm()
            self.loadReviews()
          })
          .catch(error => {
            self.err = 'Failed to update review.'
          })
      } else {
        postReview(self.userId, self.selectedProductId, Number(self.form.rating), self.form.comment)
          .then(data => {
            self.msg = 'Review added successfully.'
            self.resetForm()
            self.loadReviews()
          })
          .catch(error => {
            self.err = 'Failed to add review.'
          })
      }
    },
    editReview(review) {
      this.editingId = review.id
      this.form = {
        rating: String(review.rating),
        comment: review.comment
      }
    },
    removeReview(id) {
      var self = this
      deleteReview(id)
        .then(data => {
          self.msg = 'Review deleted successfully.'
          self.loadReviews()
        })
        .catch(error => {
          self.err = 'Failed to delete review.'
        })
    },
    cancelEdit() {
      this.resetForm()
    },
    resetForm() {
      this.editingId = null
      this.form = {
        rating: '5',
        comment: ''
      }
    }
  }
}
</script>

<style scoped>
</style>
