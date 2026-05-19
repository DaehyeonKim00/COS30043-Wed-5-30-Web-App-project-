<template>
  <div class="container py-4">
    <h2 class="mb-4">Order History</h2>

    <div v-for="order in orders" :key="order.id" class="card mb-3 shadow-sm">
      <div class="card-body">
        <h5>Order #{{ order.id }}</h5>
        <p><strong>Date:</strong> {{ order.date }}</p>

        <p>
          <strong>Status:</strong>
          <span
            class="badge"
            :class="order.status === 'Delivered' ? 'bg-success' : 'bg-warning text-dark'"
          >
            {{ order.status }}
          </span>
        </p>

        <ul>
          <li v-for="item in order.items" :key="item.name">
            {{ item.name }} — Qty: {{ item.quantity }} — ${{ item.price }}
          </li>
        </ul>

        <h6>Total: ${{ order.total }}</h6>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OrderHistory',
  data() {
    return {
      orders: [
        {
          id: 1001,
          date: '2026-05-19',
          status: 'Shipping',
          items: [
            { name: 'Acoustic Guitar', quantity: 1, price: 299 },
            { name: 'Guitar Picks', quantity: 2, price: 10 }
          ],
          total: 319
        },
        {
          id: 1002,
          date: '2026-05-18',
          status: 'Delivered',
          items: [
            { name: 'Keyboard', quantity: 1, price: 499 }
          ],
          total: 499
        }
      ]
    }
  }
}
</script>npm run dev