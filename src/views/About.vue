<template>
  <div>
    <!-- Hero Section -->
    <section class="hero-latte rounded-3 text-center py-5 mb-5 px-3">
      <h1 class="display-5 fw-bold mb-3">About SwinMusic Shop</h1>
      <p class="lead mb-0">
        SwinMusic Shop is an online music store for instruments, accessories, and music lovers.
      </p>
    </section>

    <!-- Music Shop Introduction -->
    <section class="mb-5">
      <div class="row align-items-center g-4">
        <div class="col-12 col-md-7">
          <h2 class="fw-bold mb-3">Music Shop Introduction</h2>
          <p class="text-muted">
            SwinMusic Shop is a modern online music store created to help customers
            easily browse and purchase musical instruments and accessories.
          </p>
          <p class="text-muted mb-0">
            Our shop includes guitars, drums, keyboards, microphones, strings,
            stands, and other music accessories. The goal of this website is to give
            users a simple, responsive, and user-friendly shopping experience.
          </p>
        </div>

        <div class="col-12 col-md-5">
          <div class="brand-card rounded-3 text-center py-5">
            <p class="display-6 fw-bold mb-1">SwinMusic</p>
            <p class="mb-0 brand-sub">Your sound starts here</p>
          </div>
        </div>
      </div>
    </section>

    <!-- What We Offer -->
    <section class="mb-5">
      <h2 class="fw-bold mb-4 text-center">What We Offer</h2>

      <div class="row g-4">
        <div class="col-12 col-md-4" v-for="feature in features" :key="feature.title">
          <div class="card h-100 border-0 shadow-sm text-center p-4">
            <h5 class="fw-bold mb-2">{{ feature.title }}</h5>
            <p class="text-muted mb-0">{{ feature.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact and Location -->
    <section class="mb-5">
      <h2 class="fw-bold mb-4 text-center">Contact Information & Location</h2>

      <div class="row g-4">
        <div class="col-12 col-md-6">
          <div class="card h-100 border-0 shadow-sm p-4">
            <h4 class="fw-bold mb-3">Contact Info</h4>
            <p><strong>Email:</strong> support@swinmusicshop.com</p>
            <p><strong>Phone:</strong> (03) 9000 1234</p>
            <p><strong>Opening Hours:</strong> Monday - Friday, 9:00 AM - 5:00 PM</p>
            <p class="mb-0"><strong>Customer Support:</strong> Available for product and order enquiries.</p>
          </div>
        </div>

        <div class="col-12 col-md-6">
          <div class="card h-100 border-0 shadow-sm p-4">
            <h4 class="fw-bold mb-3">Location</h4>
            <p><strong>Store:</strong> SwinMusic Shop</p>
            <p><strong>Address:</strong> 1 John Street, Hawthorn VIC 3122, Australia</p>
            <p><strong>City:</strong> Melbourne</p>
            <p class="mb-0"><strong>Service Area:</strong> Online orders available across Australia.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="text-center py-4 border-top mt-4">
      <h4 class="fw-bold mb-3">Ready to find your sound?</h4>
      <router-link to="/products" class="btn btn-dark btn-lg me-2">Shop Now</router-link>
      <router-link to="/" class="btn btn-outline-dark btn-lg">Back to Home</router-link>
    </section>
  </div>
</template>

<script>
export default {
  name: 'About',
  data() {
    return {
      features: [
        {
          title: 'Musical Instruments',
          desc: 'Browse guitars, drums, keyboards, and accessories.'
        },
        {
          title: 'Easy Shopping',
          desc: 'Search, view details, add to cart, and checkout easily.'
        },
        {
          title: 'Trusted Service',
          desc: 'Helpful support and reliable information for every customer.'
        }
      ]
    }
  }
}
</script>

