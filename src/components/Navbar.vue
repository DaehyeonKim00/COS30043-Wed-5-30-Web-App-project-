<template>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="container">
      <div class="row w-100 align-items-center">

        <!-- Logo -->
        <div class="col-8 col-md-3 col-lg-2">
            <router-link class="navbar-brand mb-0" to="/">Music Shop</router-link>
        </div>

        <!-- Hamburger button (mobile) / Hide when bigger than md -->
        <div class="col-4 d-md-none d-flex justify-content-end ">
            <!-- button -->
            <!-- 1. Navbar toggle button (mobile menu trigger) -->
            <!-- 2. Accessibility settings for screen readers -->
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
        

        <!-- Menu (Home/Products/About - (Register/Login or Cart/Mypage/Logout))-->
        <div class="col-12 col-md-9 col-lg-10">
            <div class="collapse navbar-collapse" id="navbarMenu">
                <div class="row w-100 pt-3 pt-md-0">
                    <!-- Left links (Home/Products/About)-->
                    <div class="col-12 col-md-6 col-lg-7">
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

                    <!-- Right links -->
                    <div class="col-12 col-md-6 col-lg-5 mt-2 mt-md-0">
                        <ul class="navbar-nav justify-content-md-end">
                            <!-- When not logged in (Register/Login) -->
                            <template v-if="!$store.state.isLoggedIn">
                                <li class="nav-item">
                                    <router-link class="nav-link" to="/register">Register</router-link>
                                </li>
                                <li class="nav-item">
                                    <router-link class="nav-link" to="/login">Login</router-link>
                                </li>
                            </template>

                            <!-- When logged in (Cart/Mypage/Logout)-->
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
