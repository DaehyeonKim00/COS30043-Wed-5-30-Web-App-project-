<template>
  <div class="container py-5">
    <h1 class="mb-4">Admin Product Management</h1>

    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h3>{{ editingId ? 'Edit Product' : 'Add Product' }}</h3>

        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <input v-model="form.name" class="form-control" type="text">
        </div>

        <div class="mb-3">
          <label class="form-label">Brand</label>
          <input v-model="form.brand" class="form-control" type="text">
        </div>

        <div class="mb-3">
          <label class="form-label">Category</label>
          <select v-model="form.category" class="form-select">
            <option>Guitar</option>
            <option>Drums</option>
            <option>Keyboard</option>
            <option>Accessories</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Price</label>
          <input v-model.number="form.price" class="form-control" type="number">
        </div>

        <div class="mb-3">
          <label class="form-label">Stock</label>
          <input v-model.number="form.stock" class="form-control" type="number">
        </div>

        <div class="mb-3">
          <label class="form-label">Product Image</label>
          <input class="form-control" type="file" accept="image/*" @change="uploadImage">
        </div>

        <img v-if="form.image" :src="form.image" class="preview-img mb-3">

        <br>

        <button class="btn btn-dark me-2" @click="saveProduct">
          {{ editingId ? 'Update Product' : 'Add Product' }}
        </button>

        <button v-if="editingId" class="btn btn-secondary" @click="cancelEdit">
          Cancel
        </button>
      </div>
    </div>

    <h3>Product List</h3>

    <table class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Brand</th>
          <th>Category</th>
          <th>Price</th>
          <th>Stock</th>
          <th>Action</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>
            <img v-if="product.image" :src="product.image" class="table-img">
            <span v-else>No image</span>
          </td>
          <td>{{ product.name }}</td>
          <td>{{ product.brand }}</td>
          <td>{{ product.category }}</td>
          <td>${{ product.price }}</td>
          <td>{{ product.stock }}</td>
          <td>
            <button class="btn btn-sm btn-primary me-2" @click="editProduct(product)">Edit</button>
            <button class="btn btn-sm btn-danger" @click="deleteProduct(product.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const products = ref([
  {
    id: 1,
    name: 'Acoustic Guitar',
    brand: 'Yamaha',
    category: 'Guitar',
    price: 299,
    stock: 10,
    image: ''
  }
])

const form = ref({
  name: '',
  brand: '',
  category: 'Guitar',
  price: 0,
  stock: 0,
  image: ''
})

const editingId = ref(null)

function uploadImage(event) {
  const file = event.target.files[0]
  if (!file) return

  const reader = new FileReader()
  reader.onload = () => {
    form.value.image = reader.result
  }
  reader.readAsDataURL(file)
}

function saveProduct() {
  if (!form.value.name || !form.value.brand || form.value.price <= 0) {
    alert('Please fill product name, brand, and price.')
    return
  }

  if (editingId.value) {
    const product = products.value.find(p => p.id === editingId.value)
    Object.assign(product, form.value)
  } else {
    products.value.push({
      id: Date.now(),
      ...form.value
    })
  }

  resetForm()
}

function editProduct(product) {
  editingId.value = product.id
  form.value = { ...product }
}

function deleteProduct(id) {
  products.value = products.value.filter(product => product.id !== id)
}

function cancelEdit() {
  resetForm()
}

function resetForm() {
  editingId.value = null
  form.value = {
    name: '',
    brand: '',
    category: 'Guitar',
    price: 0,
    stock: 0,
    image: ''
  }
}
</script>

<style scoped>
.preview-img {
  width: 220px;
  height: 160px;
  object-fit: cover;
  border-radius: 10px;
  border: 1px solid #ddd;
}

.table-img {
  width: 70px;
  height: 55px;
  object-fit: cover;
  border-radius: 6px;
}
</style>