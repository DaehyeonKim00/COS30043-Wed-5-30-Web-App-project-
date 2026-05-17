<template>
  <div class="container py-5">
    <h1 class="mb-4">Order History</h1>

    <div class="card shadow-sm">
      <div class="card-body">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Date</th>
              <th>Product</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="order in orders" :key="order.id">
              <td>{{ order.id }}</td>
              <td>{{ order.date }}</td>
              <td>{{ order.product }}</td>
              <td>${{ order.total }}</td>
              <td>
                <span
                  class="badge"
                  :class="order.status === 'Delivered' ? 'bg-success' : 'bg-warning text-dark'"
                >
                  {{ order.status }}
                </span>
              </td>
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

<script setup>
import { ref } from 'vue'

const orders = ref([
  {
    id: 'ORD1001',
    date: '2026-05-01',
    product: 'Acoustic Guitar',
    total: 299,
    status: 'Delivered'
  },
  {
    id: 'ORD1002',
    date: '2026-05-04',
    product: 'Electric Keyboard',
    total: 499,
    status: 'Shipping'
  },
  {
    id: 'ORD1003',
    date: '2026-05-06',
    product: 'Drum Kit',
    total: 899,
    status: 'Processing'
  }
])
</script>