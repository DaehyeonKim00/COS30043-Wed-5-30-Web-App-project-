<template>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
      <div class="row w-100 align-items-center">

        <!-- Logo -->
        <div class="col-8 col-md-3 col-lg-2">
          <router-link class="navbar-brand mb-0" to="/">SwinMusic Shop</router-link>
        </div>

        <!-- Hamburger button (mobile) -->
        <div class="col-4 d-md-none d-flex justify-content-end">
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
            aria-controls="navbarMenu"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>

        <!-- Menu -->
        <div class="col-12 col-md-9 col-lg-10">
          <div class="collapse navbar-collapse" id="navbarMenu">
            <div class="row w-100 pt-3 pt-md-0 align-items-center">

              <!-- Left links -->
              <div class="col-12 col-md-4 col-lg-4">
                <ul class="navbar-nav">
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
              </div>

              <!-- Search bar -->
              <div class="col-12 col-md-4 col-lg-5 my-2 my-md-0">
                <form class="d-flex" @submit.prevent="submitSearch">
                  <input
                    v-model="searchKeyword"
                    class="form-control form-control-sm me-2"
                    type="search"
                    placeholder="Search products..."
                    aria-label="Search"
                  />
                  <button class="btn btn-outline-light btn-sm text-nowrap" type="submit">Search</button>
                </form>
              </div>

              <!-- Right links -->
              <div class="col-12 col-md-4 col-lg-3 mt-2 mt-md-0">
                <ul class="navbar-nav justify-content-md-end">
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
          </div>
        </div>

      </div>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'Navbar',
  data() {
    return {
      searchKeyword: ''
    }
  },
  methods: {
    submitSearch() {
      var keyword = this.searchKeyword.trim()
      if (keyword) {
        this.$router.push('/products?q=' + encodeURIComponent(keyword))
        this.searchKeyword = ''
      }
    },
    logout() {
      this.$store.commit('logout')
      this.$router.push('/login')
    }
  }
}
</script>

<style scoped>
</style>
