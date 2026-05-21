<template>
  <div class="container py-5">
    <PageHeader title="Product Reviews" />

    <!-- View mode toggle -->
    <div class="btn-group mb-4" role="group" aria-label="Review view mode">
      <button
        type="button"
        class="btn"
        :class="viewMode === 'all' ? 'btn-dark' : 'btn-outline-dark'"
        @click="setViewMode('all')"
      >
        All Reviews
      </button>
      <button
        type="button"
        class="btn"
        :class="viewMode === 'product' ? 'btn-dark' : 'btn-outline-dark'"
        @click="setViewMode('product')"
      >
        By Product
      </button>
    </div>

    <!-- Product selector (only in "By Product" mode) -->
    <div v-if="viewMode === 'product'" class="card shadow-sm mb-4">
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

    <!-- Login prompt (when not logged in and in product mode) -->
    <div v-if="viewMode === 'product' && selectedProductId && !user" class="alert alert-info">
      <router-link to="/login">Log in</router-link> to write a review.
    </div>

    <!-- Review form (only when a product is selected and user is logged in) -->
    <div v-if="viewMode === 'product' && selectedProductId && user" class="card shadow-sm mb-4">
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

        <SuccessMessage :message="msg" />
      </div>
    </div>

    <!-- Loading state -->
    <LoadingSpinner v-if="isLoading" />

    <!-- Error state -->
    <ErrorAlert v-else-if="err" :message="err" />

    <!-- Review list -->
    <div v-else-if="viewMode === 'all' || selectedProductId">
      <h4 class="mb-3">
        {{ viewMode === 'all' ? 'All Reviews' : 'Reviews for this product' }}
        <span class="badge bg-secondary">{{ reviews.length }}</span>
      </h4>

      <p v-if="reviews.length === 0" class="text-muted">No reviews yet.</p>

      <div v-else class="row g-4">
        <div v-for="review in reviews" :key="review.id" class="col-md-6">
          <div class="card shadow-sm h-100">
            <div class="card-body">
              <!-- Product info (image + name) shown for every review -->
              <router-link
                v-if="review.product_image"
                :to="'/products/' + review.product_id"
                class="d-flex align-items-center gap-2 mb-3 text-decoration-none text-dark"
              >
                <img
                  :src="review.product_image"
                  :alt="review.product_name"
                  class="rounded review-thumb"
                />
                <span class="fw-bold">{{ review.product_name }}</span>
              </router-link>

              <h5>{{ review.name }}</h5>
              <p class="text-warning fs-4 mb-1">{{ stars(review.rating) }}</p>
              <p class="text-muted small mb-2" v-if="review.created_at">
                {{ formatDate(review.created_at) }}
              </p>
              <p>{{ review.comment }}</p>

              <!-- Edit/Delete only visible for the review author -->
              <template v-if="user && user.id == review.user_id">
                <button class="btn btn-sm btn-outline-primary me-2" @click="editReview(review)">
                  Edit
                </button>

                <button class="btn btn-sm btn-outline-danger" @click="removeReview(review.id)">
                  Delete
                </button>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getReviews, getAllReviews, postReview, updateReview, deleteReview } from '../api/review.js'
import { getProducts } from '../api/productList.js'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'
import SuccessMessage from '../components/SuccessMessage.vue'
import PageHeader from '../components/PageHeader.vue'

export default {
  name: 'Review',
  components: { LoadingSpinner, ErrorAlert, SuccessMessage, PageHeader },
  data() {
    return {
      products: [],
      selectedProductId: '',
      reviews: [],
      viewMode: 'all',
      isLoading: false,
      err: '',
      msg: '',
      editingId: null,
      form: {
        rating: '5',
        comment: ''
      }
    }
  },
  computed: {
    // Reactive: updates automatically when Vuex user changes (e.g. on logout)
    user() {
      return this.$store.state.user
    }
  },
  mounted() {
    var self = this
    getProducts()
      .then(data => {
        self.products = data
      })
      .catch(error => {
        self.err = 'Failed to load products. Please try again later.'
      })

    // If arriving with ?product_id=X (e.g. from ProductDetail), open "By Product"
    // mode with that product preselected. Otherwise default to "All Reviews".
    var qid = self.$route.query.product_id
    if (qid) {
      self.viewMode = 'product'
      self.selectedProductId = qid
      self.loadReviews()
    } else {
      self.loadReviews()
    }
  },
  watch: {
    selectedProductId() {
      this.resetForm()
      if (this.viewMode === 'product') {
        this.loadReviews()
      }
    },
    // React to query changes if user navigates to /review?product_id=Y while
    // already on the Review page.
    '$route.query.product_id'(newId) {
      if (newId) {
        this.viewMode = 'product'
        this.selectedProductId = newId
      }
    }
  },
  methods: {
    setViewMode(mode) {
      this.viewMode = mode
      this.resetForm()
      this.err = ''
      this.msg = ''
      this.loadReviews()
    },
    loadReviews() {
      var self = this
      self.err = ''

      if (self.viewMode === 'all') {
        self.isLoading = true
        getAllReviews()
          .then(data => {
            self.reviews = data
            self.isLoading = false
          })
          .catch(error => {
            self.err = 'Failed to load reviews. Please try again later.'
            self.isLoading = false
          })
      } else {
        if (!self.selectedProductId) {
          self.reviews = []
          return
        }
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
      }
    },
    stars(rating) {
      return '★'.repeat(rating) + '☆'.repeat(5 - rating)
    },
    formatDate(dateStr) {
      var d = new Date(dateStr)
      if (isNaN(d.getTime())) return dateStr
      return d.toLocaleDateString()
    },
    saveReview() {
      var self = this
      if (!self.user) {
        self.$router.push('/login')
        return
      }
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
        postReview(self.user.id, self.selectedProductId, Number(self.form.rating), self.form.comment)
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
