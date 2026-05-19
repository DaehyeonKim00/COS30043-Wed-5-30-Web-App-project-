<template>
  <div class="container py-5">
    <h1 class="mb-4">Product Reviews</h1>

    <div class="card p-4 mb-4">
      <label class="form-label">Select a Product</label>

      <select v-model="selectedProduct" class="form-select mb-3">
        <option value="">-- Choose a product --</option>

        <option
          v-for="(product, index) in products"
          :key="index"
          :value="product.name"
        >
          {{ product.name }}
        </option>
      </select>

      <div v-if="selectedProduct">
        <textarea
          v-model="newReview"
          class="form-control mb-3"
          placeholder="Write your review..."
        ></textarea>

        <button class="btn btn-dark" @click="addReview">
          Submit Review
        </button>
      </div>
    </div>

    <div
      v-if="selectedProduct && reviews[selectedProduct]"
      class="card p-4"
    >
      <h3 class="mb-3">
        Reviews for {{ selectedProduct }}
      </h3>

      <div
        v-for="(review, index) in reviews[selectedProduct]"
        :key="index"
        class="border rounded p-3 mb-3"
      >
        {{ review }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const products = ref([])
const selectedProduct = ref('')
const newReview = ref('')

const reviews = ref({})

onMounted(() => {
  const savedProducts = localStorage.getItem('musicProducts')

  if (savedProducts) {
    products.value = JSON.parse(savedProducts)
  }

  const savedReviews = localStorage.getItem('musicReviews')

  if (savedReviews) {
    reviews.value = JSON.parse(savedReviews)
  }
})

const addReview = () => {
  if (!newReview.value) return

  if (!reviews.value[selectedProduct.value]) {
    reviews.value[selectedProduct.value] = []
  }

  reviews.value[selectedProduct.value].push(
    newReview.value
  )

  localStorage.setItem(
    'musicReviews',
    JSON.stringify(reviews.value)
  )

  newReview.value = ''

  alert('Review added successfully')
}
</script>