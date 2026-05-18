<template>
  <div class="container py-5">
    <h1 class="mb-4">Order History</h1>

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

    <!-- Orders table -->
    <div v-else class="card shadow-sm">
      <div class="card-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Date</th>
              <th>Total</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="order in orders" :key="order.id">
              <td>{{ order.id }}</td>
              <td>{{ order.created_at }}</td>
              <td>${{ order.total_price }}</td>
            </tr>
          </tbody>
        </table>

        <p v-if="orders.length === 0" class="text-muted">
          No orders found.
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import { getOrders } from '../api/orderHistory.js'

export default {
  name: 'OrderHistory',
  data() {
    return {
      orders: [],
      isLoading: false,
      err: '',
      msg: '',

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
    self.isLoading = true
    getOrders(self.userId)
      .then(data => {
        self.orders = data
        self.msg = 'Successful!'
        self.isLoading = false
      })
      .catch(error => {
        self.err = 'Failed to load orders. Please try again later.'
        self.isLoading = false
      })
  }
}
</script>

<style scoped>
</style>
