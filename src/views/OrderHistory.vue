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
import { getOrders } from "../api/orderHistory.js";
import LoadingSpinner from "../components/LoadingSpinner.vue";
import ErrorAlert from "../components/ErrorAlert.vue";
import PageHeader from "../components/PageHeader.vue";

export default {
  components: { LoadingSpinner, ErrorAlert, PageHeader },
  name: "OrderHistory",
  data() {
    return {
      orders: [],
      isLoading: false,
      err: "",
      msg: "",

      // ===== TEMPORARY (login not implemented yet) =====
      // Using a fixed user id so the page can be tested against the DB.
      //userId: 1
      // ===== REAL CODE (use after login is implemented) =====
      userId: null,
      // (or from Vuex: this.$store.state.user.id)
    };
  },
  mounted() {
    var self = this;

    // Determine the user id from the store; if absent, skip loading (guard should redirect).
    self.userId = this.$store.state.user ? this.$store.state.user.id : null;
    if (!self.userId) return;
    self.isLoading = true;
    getOrders(self.userId)
      .then((data) => {
        self.orders = data;
        self.msg = "Successful!";
        self.isLoading = false;
      })
      .catch((error) => {
        self.err = "Failed to load orders. Please try again later.";
        self.isLoading = false;
      });
  },
};
</script>
