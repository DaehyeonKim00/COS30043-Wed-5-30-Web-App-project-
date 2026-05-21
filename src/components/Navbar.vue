<template>
  <nav class="navbar navbar-expand-xl navbar-dark wood-navbar sticky-top">
    <div class="container">
      <div class="row w-100 align-items-center">

        <!-- Logo -->
        <div class="col-8 col-xl-2">
          <router-link class="navbar-brand mb-0" to="/">SwinMusic Shop</router-link>
        </div>

        <!-- Hamburger button (mobile only: < 768px) -->
        <div class="col-4 d-xl-none d-flex justify-content-end">
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
        <div class="col-12 col-xl-10">
          <div class="collapse navbar-collapse" id="navbarMenu">
            <div class="row w-100 pt-3 pt-xl-0 align-items-center">

              <!-- Left links -->
              <div class="col-12 col-xl-5">
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
                  <li class="nav-item">
                    <router-link class="nav-link" to="/review">Reviews</router-link>
                  </li>
                  <!-- Admin link: only visible to users with the admin role -->
                  <li
                    v-if="$store.state.user && $store.state.user.role === 'admin'"
                    class="nav-item"
                  >
                    <router-link class="nav-link" to="/admin">Admin</router-link>
                  </li>
                </ul>
              </div>

              <!-- Search bar (visible on mobile collapse + desktop xl) -->
              <div class="col-12 col-xl-3 my-2 my-xl-0">
                <form class="d-flex" @submit.prevent="submitSearch">
                  <input
                    v-model="searchKeyword"
                    class="form-control form-control-sm me-2"
                    type="search"
                    placeholder="Search products..."
                    aria-label="Search"
                  />
                  <button class="btn btn-primary btn-sm text-nowrap" type="submit">Search</button>
                </form>
              </div>

              <!-- Right links -->
              <div class="col-12 col-xl-4 mt-2 mt-xl-0">
                <ul class="navbar-nav justify-content-xl-end">
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
                      <span class="nav-link disabled">
                        Hi, {{ $store.state.user.name }}
                      </span>
                    </li>
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
      localStorage.removeItem('user')
      this.$store.commit('logout')
      this.$router.push('/login')
    }
  }
}
</script>
