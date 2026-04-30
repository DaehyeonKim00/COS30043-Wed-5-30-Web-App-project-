<template>
  <div>

    <!-- Hero Section -->
    <section class="about-hero rounded-3 text-white text-center py-5 mb-5 px-3">
      <h1 class="display-5 fw-bold mb-3">About SwinMusic Shop</h1>
      <p class="lead mb-0">SwinMusic Shop is a modern music store web application project.</p>
    </section>

    <!-- Mission Section -->
    <section class="mb-5">
      <div class="row align-items-center g-4">
        <div class="col-12 col-md-7">
          <h2 class="fw-bold mb-3">Our Mission</h2>
          <p class="text-muted">
            SwinMusic Shop is dedicated to bringing the best musical instruments and
            accessories to musicians of all levels. Whether you're a beginner picking
            up your first guitar or a professional looking for premium gear, we have
            everything you need.
          </p>
          <p class="text-muted mb-0">
            We believe music should be accessible to everyone. That's why we offer
            a wide range of products across all categories — from guitars and keyboards
            to studio equipment and accessories.
          </p>
        </div>
        <div class="col-12 col-md-5">
          <div class="bg-dark rounded-3 text-white text-center py-5">
            <p class="display-6 fw-bold mb-1">SwinMusic</p>
            <p class="text-muted mb-0">Est. 2024</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="mb-5">
      <h2 class="fw-bold mb-4 text-center">What We Offer</h2>
      <div class="row g-4">
        <div
          class="col-12 col-md-4"
          v-for="feature in features"
          :key="feature.title"
        >
          <div class="card h-100 border-0 shadow-sm text-center p-4">
            <h5 class="fw-bold mb-2">{{ feature.title }}</h5>
            <p class="text-muted mb-0">{{ feature.desc }}</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section class="mb-5">
      <h2 class="fw-bold mb-4 text-center">Meet the Team</h2>
      <div class="row g-4 justify-content-center">
        <div
          class="col-12 col-sm-6 col-md-4 col-lg-2"
          v-for="member in team"
          :key="member.name"
        >
          <div class="card h-100 border-0 shadow-sm text-center p-3">
            <div class="rounded-circle bg-dark text-white fw-bold d-flex align-items-center justify-content-center mx-auto mb-3 member-avatar">
              {{ member.initial }}
            </div>
            <h6 class="fw-bold mb-1">{{ member.name }}</h6>
            <p class="text-muted small mb-0">{{ member.role }}</p>
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
          title: 'Wide Selection',
          desc: 'Guitars, keyboards, drums, and more across all categories.'
        },
        {
          title: 'Fast Delivery',
          desc: 'Quick and reliable shipping directly to your door.'
        },
        {
          title: 'Trusted Quality',
          desc: 'Only the best brands and verified products in our catalog.'
        }
      ],
      team: [
        { initial: 'M1', name: 'Member 1', role: 'Home & Products' },
        { initial: 'M2', name: 'Member 2', role: 'Cart & Auth' },
        { initial: 'M3', name: 'Member 3', role: 'My Page & Admin' },
        { initial: 'M4', name: 'Member 4', role: 'Router & Vuex' },
        { initial: 'M5', name: 'Member 5', role: 'DB & API' }
      ]
    }
  }
}
</script>

<style scoped>
.about-hero {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
}

.member-avatar {
  width: 56px;
  height: 56px;
  font-size: 1rem;
}
</style>
