<template>
  <div class="container py-5">
    <h1 class="mb-4">My Page</h1>

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

            <p v-if="msg" class="text-success mt-3">{{ msg }}</p>
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
                  class="list-group-item d-flex justify-content-between align-items-center"
                >
                  <router-link :to="'/products/' + item.product_id">
                    {{ item.name }}
                  </router-link>
                  <span>${{ item.price }}</span>
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
import { getWishlist } from '../api/wishlist.js'

export default {
  name: 'MyPage',
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

      // ===== TEMPORARY (login not implemented yet) =====
      // Using a fixed user id so the page can be tested against the DB.
      userId: 1
      // ===== REAL CODE (use after login is implemented) =====
      // Read the logged-in user saved at login time:
      // userId: JSON.parse(localStorage.getItem('user')).id
      // (or from Vuex: this.$store.state.user.id)
    }
  },
  mounted() {
    var self = this

    // Load profile
    self.isLoading = true
    getUserInfo(self.userId)
      .then(data => {
        // api_mypage.php returns an array of user rows
        self.user = data[0]
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
      updateUserInfo(self.userId, self.user.name, self.user.email)
        .then(data => {
          self.msg = 'Profile updated successfully.'
        })
        .catch(error => {
          self.err = 'Failed to update profile.'
        })
    }
  }
}
</script>

<style scoped>
</style>
