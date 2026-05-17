<template>
  <div class="container py-5">
    <h1 class="mb-4">Product Reviews</h1>

    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h3>{{ editingId ? 'Edit Review' : 'Write a Review' }}</h3>

        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <input v-model="form.product" type="text" class="form-control" />
        </div>

        <div class="mb-3">
          <label class="form-label">Star Rating</label>
          <select v-model="form.rating" class="form-select">
            <option value="5">★★★★★ 5 Stars</option>
            <option value="4">★★★★ 4 Stars</option>
            <option value="3">★★★ 3 Stars</option>
            <option value="2">★★ 2 Stars</option>
            <option value="1">★ 1 Star</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Review</label>
          <textarea v-model="form.comment" class="form-control" rows="4"></textarea>
        </div>

        <button class="btn btn-primary me-2" @click="saveReview">
          {{ editingId ? 'Update Review' : 'Add Review' }}
        </button>

        <button v-if="editingId" class="btn btn-secondary" @click="cancelEdit">
          Cancel
        </button>
      </div>
    </div>

    <div class="row g-4">
      <div v-for="review in reviews" :key="review.id" class="col-md-6">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <h4>{{ review.product }}</h4>
            <p class="text-warning fs-4">{{ stars(review.rating) }}</p>
            <p>{{ review.comment }}</p>

            <button class="btn btn-sm btn-outline-success me-2" @click="likeReview(review)">
              👍 {{ review.likes }}
            </button>

            <button class="btn btn-sm btn-outline-primary me-2" @click="editReview(review)">
              Edit
            </button>

            <button class="btn btn-sm btn-outline-danger" @click="deleteReview(review.id)">
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'

const reviews = ref([
  {
    id: 1,
    product: 'Acoustic Guitar',
    rating: 5,
    comment: 'Great sound quality and comfortable to play.',
    likes: 3
  },
  {
    id: 2,
    product: 'Electric Keyboard',
    rating: 4,
    comment: 'Good keyboard for beginners and students.',
    likes: 1
  }
])

const form = ref({
  product: '',
  rating: 5,
  comment: ''
})

const editingId = ref(null)

function stars(rating) {
  return '★'.repeat(rating) + '☆'.repeat(5 - rating)
}

function saveReview() {
  if (!form.value.product || !form.value.comment) {
    alert('Please enter product name and review.')
    return
  }

  if (editingId.value) {
    const review = reviews.value.find(item => item.id === editingId.value)
    review.product = form.value.product
    review.rating = Number(form.value.rating)
    review.comment = form.value.comment
  } else {
    reviews.value.push({
      id: Date.now(),
      product: form.value.product,
      rating: Number(form.value.rating),
      comment: form.value.comment,
      likes: 0
    })
  }

  resetForm()
}

function editReview(review) {
  editingId.value = review.id
  form.value = {
    product: review.product,
    rating: review.rating,
    comment: review.comment
  }
}

function deleteReview(id) {
  reviews.value = reviews.value.filter(review => review.id !== id)
}

function likeReview(review) {
  review.likes++
}

function cancelEdit() {
  resetForm()
}

function resetForm() {
  editingId.value = null
  form.value = {
    product: '',
    rating: 5,
    comment: ''
  }
}
</script>