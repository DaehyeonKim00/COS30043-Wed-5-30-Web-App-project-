<template>
  <div class="container py-5">
    <PageHeader title="Order History" />

    <!-- Loading state -->
    <LoadingSpinner v-if="isLoading" />

    <!-- Error state -->
    <ErrorAlert v-else-if="err" :message="err" />

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

        <p v-if="orders.length === 0" class="text-muted">No orders found.</p>
      </div>
    </div>
  </div>
</template>

<script>
import { getOrders } from '../api/orderHistory.js'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'
import PageHeader from '../components/PageHeader.vue'

export default {
  name: 'OrderHistory',
  components: { LoadingSpinner, ErrorAlert, PageHeader },
  data() {
    return {
      orders: [],
      isLoading: false,
      err: '',
      msg: '',
      userId: null
    }
  },
  mounted() {
    var self = this

    // The router guard owns access control. Read the current user from the store.
    self.userId = self.$store.state.user ? self.$store.state.user.id : null
    if (!self.userId) return

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
