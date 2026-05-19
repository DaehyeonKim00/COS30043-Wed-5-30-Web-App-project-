<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
      <router-link class="navbar-brand" to="/">Music Shop</router-link>

      <div class="navbar-nav">
        <router-link class="nav-link" to="/">Home</router-link>
        <router-link class="nav-link" to="/mypage">My Page</router-link>
        <router-link class="nav-link" to="/orderhistory">Order History</router-link>
        <router-link class="nav-link" to="/review">Reviews</router-link>
        <router-link class="nav-link" to="/admin">Admin</router-link>
        <router-link class="nav-link" to="/about">About</router-link>
      </div>
    </nav>

    <router-view />
  </div>
</template>