<template>
  <div class="container py-4">
    <h2 class="mb-4">My Page</h2>

    <div class="card p-4 shadow-sm mb-4">
      <h5>Edit Personal Info</h5>

      <input v-model="user.name" class="form-control mb-2" placeholder="Full name" />
      <input v-model="user.email" class="form-control mb-2" placeholder="Email" />
      <input v-model="user.phone" class="form-control mb-2" placeholder="Phone number" />

      <button class="btn btn-primary" @click="saveProfile">Save Profile</button>

      <div v-if="saved" class="alert alert-success mt-3">
        Profile saved successfully.
      </div>
    </div>

    <div class="card p-4 shadow-sm mb-4">
      <h5>Wishlist</h5>
      <ul>
        <li v-for="item in wishlist" :key="item">{{ item }}</li>
      </ul>
    </div>

    <div class="card p-4 shadow-sm">
      <h5>Recent Orders</h5>
      <p>Order #1001 — Shipping</p>
      <p>Order #1002 — Delivered</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MyPage',
  data() {
    return {
      saved: false,
      user: JSON.parse(localStorage.getItem('userProfile')) || {
        name: 'Dinupa Senevirathne',
        email: 'dinupa@example.com',
        phone: '0400000000'
      },
      wishlist: ['Acoustic Guitar', 'Keyboard', 'Drum Sticks']
    }
  },
  methods: {
    saveProfile() {
      localStorage.setItem('userProfile', JSON.stringify(this.user))
      this.saved = true
    }
  }
}
</script>