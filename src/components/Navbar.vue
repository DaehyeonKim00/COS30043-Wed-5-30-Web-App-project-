<template>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">

      <!-- Logo -->
      <router-link class="navbar-brand" to="/">Music Shop</router-link>

      <!-- Hamburger button (mobile) -->
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu -->
      <div class="collapse navbar-collapse" id="navbarMenu">

        <!-- Left links -->
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <router-link class="nav-link" to="/">Home</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/products">Products</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/about">About</router-link>
          </li>
        </ul>

        <!-- Right links -->
        <ul class="navbar-nav ms-auto">

          <!-- When not logged in -->
          <template v-if="!$store.state.isLoggedIn">
            <li class="nav-item">
              <router-link class="nav-link" to="/register">Register</router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/login">Login</router-link>
            </li>
          </template>

          <!-- When logged in -->
          <template v-else>
            <li class="nav-item">
              <router-link class="nav-link" to="/cart">
                Cart ({{ $store.state.cart.length }})
              </router-link>
            </li>
            <li class="nav-item">
              <router-link class="nav-link" to="/mypage">My Page</router-link>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" @click="logout">Logout</a>
            </li>
          </template>

        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar',
  methods: {
    logout() {
      this.$store.commit('logout')
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
</style>
