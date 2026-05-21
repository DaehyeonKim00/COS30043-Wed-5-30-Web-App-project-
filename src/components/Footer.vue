<template>
  <footer class="wood-footer mt-5 pt-4 pb-3">
    <div class="container">
      <div class="row">
        <div class="col-12 col-md-4 mb-4">
          <h5>SwinMusic Shop</h5>
          <p class="mb-0">
            Your trusted store for instruments and accessories.
          </p>
        </div>

        <div class="col-12 col-md-4 mb-4">
          <h5>Quick Links</h5>
          <ul class="list-unstyled mb-0">
            <li>
              <router-link class="footer-link" to="/">Home</router-link>
            </li>
            <li>
              <router-link class="footer-link" to="/products">Products</router-link>
            </li>
            <li>
              <router-link class="footer-link" to="/about">About</router-link>
            </li>
          </ul>
        </div>

        <div class="col-12 col-md-4 mb-4">
          <h5>Support</h5>
          <p class="mb-1">Email: support@swinmusicshop.com</p>
          <p class="mb-1">Phone: +61 3 9000 1234</p>
          <p class="mb-0">Open: Mon - Fri, 9:00 AM - 5:00 PM</p>
        </div>
      </div>

      <div class="row pt-3 border-top">
        <div class="col-12 text-center">
          <p class="mb-0">© 2026 SwinMusic Shop. All rights reserved.</p>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'Footer'
}
</script>
