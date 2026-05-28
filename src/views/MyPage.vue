<template>
  <div class="container py-5">
    <PageHeader title="My Page" />

    <!-- Loading state -->
    <LoadingSpinner v-if="isLoading" />

    <!-- Error state -->
    <ErrorAlert v-else-if="err" :message="err" />

    <!-- Profile + Wishlist -->
    <div v-else class="row g-4">
      <!-- Profile -->
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3>My Profile</h3>

            <div class="mb-3">
              <label class="form-label">Name</label>
              <input v-model="user.name" type="text" class="form-control" />
            </div>

            <div class="mb-3">
              <label class="form-label">Email</label>
              <input v-model="user.email" type="email" class="form-control" />
            </div>

            <button class="btn btn-primary" @click="saveProfile">
              Save Changes
            </button>

            <SuccessMessage :message="msg" />
          </div>
        </div>
      </div>

      <!-- Wishlist -->
      <div class="col-md-6">
        <div class="card shadow-sm">
          <div class="card-body">
            <h3>Wishlist</h3>

            <!-- Wishlist loading -->
            <div v-if="wishlistLoading" class="text-center py-3">
              <div class="spinner-border spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
              </div>
            </div>

            <!-- Wishlist error -->
            <p v-else-if="wishlistErr" class="text-danger mb-0">
              {{ wishlistErr }}
            </p>

            <!-- Wishlist list -->
            <div v-else>
              <p v-if="wishlist.length === 0" class="text-muted mb-0">
                Your wishlist is empty.
              </p>

              <ul v-else class="list-group">
                <li
                  v-for="item in wishlist"
                  :key="item.id"
                  class="list-group-item d-flex justify-content-between align-items-center gap-2"
                >
                  <router-link
                    :to="'/products/' + item.product_id"
                    class="flex-grow-1"
                  >
                    {{ item.name }}
                  </router-link>
                  <span>${{ item.price }}</span>
                  <button
                    class="btn btn-sm btn-outline-danger"
                    :disabled="removingId === item.id"
                    @click="removeWishlistItem(item.id)"
                  >
                    {{ removingId === item.id ? '...' : 'Remove' }}
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Order History link -->
        <div class="card shadow-sm mt-4">
          <div class="card-body">
            <h3>Order History</h3>
            <p class="text-muted">View all your past orders.</p>
            <router-link to="/orderhistory" class="btn btn-dark">
              View Order History
            </router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { getUserInfo, updateUserInfo } from '../api/myPage.js'
import { getWishlist, removeWishlistById } from '../api/wishlist.js'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'
import SuccessMessage from '../components/SuccessMessage.vue'
import PageHeader from '../components/PageHeader.vue'

export default {
  name: 'MyPage',
  components: { LoadingSpinner, ErrorAlert, SuccessMessage, PageHeader },
  data() {
    return {
      user: {
        name: '',
        email: ''
      },
      isLoading: false,
      err: '',
      msg: '',
      wishlist: [],
      wishlistLoading: false,
      wishlistErr: '',
      removingId: null,
      userId: null
    }
  },
  mounted() {
    var self = this

    // The router guard owns access control. Read the current user from the store.
    self.userId = self.$store.state.user ? self.$store.state.user.id : null
    if (!self.userId) return

    // Load profile
    self.isLoading = true
    getUserInfo(self.userId)
      .then(data => {
        self.user = data
        self.isLoading = false
      })
      .catch(error => {
        self.err = 'Failed to load profile. Please try again later.'
        self.isLoading = false
      })

    // Load wishlist
    self.wishlistLoading = true
    getWishlist(self.userId)
      .then(data => {
        self.wishlist = data
        self.wishlistLoading = false
      })
      .catch(error => {
        self.wishlistErr = 'Failed to load wishlist.'
        self.wishlistLoading = false
      })
  },
  methods: {
    saveProfile() {
      var self = this
      self.err = ''
      self.msg = ''

      // Form validation
      if (!self.user.name || !self.user.email) {
        self.err = 'Name and email are required.'
        return
      }
      var nameRegex = /^[A-Za-z ]+$/
      if (!nameRegex.test(self.user.name) || self.user.name.trim().length < 2) {
        self.err = 'Name must contain letters only (min 2 characters).'
        return
      }
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!emailRegex.test(self.user.email)) {
        self.err = 'Please enter a valid email address.'
        return
      }

      updateUserInfo(self.userId, self.user.name, self.user.email)
        .then(data => {
          self.msg = 'Profile updated successfully.'
        })
        .catch(error => {
          self.err = 'Failed to update profile.'
        })
    },
    removeWishlistItem(wishlistId) {
      var self = this
      self.removingId = wishlistId
      self.wishlistErr = ''

      removeWishlistById(wishlistId)
        .then(data => {
          if (data && data.success) {
            self.wishlist = self.wishlist.filter(w => w.id !== wishlistId)
          } else {
            self.wishlistErr = (data && data.error) || 'Failed to remove item.'
          }
          self.removingId = null
        })
        .catch(error => {
          self.wishlistErr = 'Failed to remove item.'
          self.removingId = null
        })
    }
  }
}
</script>
