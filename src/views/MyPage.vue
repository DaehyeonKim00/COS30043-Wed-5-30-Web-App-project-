<template>
  <div class="container py-5">
    <h1 class="mb-4">My Page</h1>

    <div class="row g-4">
      <div class="col-md-5">
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

            <p v-if="message" class="text-success mt-3">{{ message }}</p>
          </div>
        </div>
      </div>

      <div class="col-md-7">
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <h3>Wishlist</h3>

            <ul class="list-group">
              <li
                v-for="item in wishlist"
                :key="item.id"
                class="list-group-item d-flex justify-content-between"
              >
                <span>{{ item.name }}</span>
                <span>${{ item.price }}</span>
              </li>
            </ul>
          </div>
        </div>

        <div class="card shadow-sm">
          <div class="card-body">
            <h3>Recent Orders</h3>

            <table class="table">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Item</th>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                <tr v-for="order in orders" :key="order.id">
                  <td>{{ order.id }}</td>
                  <td>{{ order.item }}</td>
                  <td>
                    <span class="badge bg-success">{{ order.status }}</span>
                  </td>
                </tr>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const user = ref({
  name: 'Dinupa Senevirathne',
  email: 'dinupa@example.com'
})

const wishlist = ref([
  { id: 1, name: 'Acoustic Guitar', price: 299 },
  { id: 2, name: 'Keyboard Stand', price: 89 },
  { id: 3, name: 'Drum Sticks', price: 25 }
])

const orders = ref([
  { id: 'ORD1001', item: 'Acoustic Guitar', status: 'Delivered' },
  { id: 'ORD1002', item: 'Keyboard', status: 'Shipping' }
])

const message = ref('')

function saveProfile() {
  message.value = 'Profile updated successfully.'
}
</script>