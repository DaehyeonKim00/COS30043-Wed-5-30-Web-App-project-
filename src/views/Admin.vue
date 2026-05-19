<template>
  <div class="container py-5">
    <h1 class="mb-4">Admin Panel</h1>

    <div class="card p-4 mb-4">
      <h3 class="mb-3">Add Product</h3>

      <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input v-model="newProduct.name" type="text" class="form-control" />
      </div>

      <div class="mb-3">
        <label class="form-label">Category</label>
        <select v-model="newProduct.category" class="form-select">
          <option>Guitar</option>
          <option>Keyboard</option>
          <option>Drums</option>
          <option>Accessories</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea
          v-model="newProduct.description"
          class="form-control"
        ></textarea>
      </div>

      <div class="mb-3">
        <label class="form-label">Price</label>
        <input
          v-model="newProduct.price"
          type="number"
          class="form-control"
        />
      </div>

      <div class="mb-3">
        <label class="form-label">Stock</label>
        <input
          v-model="newProduct.stock"
          type="number"
          class="form-control"
        />
      </div>

      <div class="mb-3">
        <label class="form-label">Upload Product Image</label>
        <input
          type="file"
          class="form-control"
          @change="handleImageUpload"
        />
      </div>

      <div v-if="newProduct.image" class="mb-3">
        <img
          :src="newProduct.image"
          style="width: 150px; height: 150px; object-fit: cover;"
          class="rounded border"
        />
      </div>

      <button class="btn btn-dark" @click="addProduct">
        Add Product
      </button>
    </div>

    <div class="card p-4">
      <h3 class="mb-3">Product List</h3>

      <div
        v-for="(product, index) in products"
        :key="index"
        class="border rounded p-3 mb-3"
      >
        <div class="row align-items-center">
          <div class="col-md-2">
            <img
              :src="product.image"
              style="width: 100px; height: 100px; object-fit: cover;"
              class="rounded border"
            />
          </div>

          <div class="col-md-7">
            <h5>{{ product.name }}</h5>
            <p>{{ product.description }}</p>
            <p><strong>Category:</strong> {{ product.category }}</p>
            <p><strong>Price:</strong> ${{ product.price }}</p>
            <p><strong>Stock:</strong> {{ product.stock }}</p>
          </div>

          <div class="col-md-3 text-end">
            <button
              class="btn btn-warning me-2"
              @click="editProduct(index)"
            >
              Edit
            </button>

            <button
              class="btn btn-danger"
              @click="deleteProduct(index)"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const products = ref([])

const newProduct = ref({
  name: '',
  category: 'Guitar',
  description: '',
  price: '',
  stock: '',
  image: ''
})

const loadProducts = () => {
  const saved = localStorage.getItem('musicProducts')

  if (saved) {
    products.value = JSON.parse(saved)
  }
}

const saveProducts = () => {
  localStorage.setItem(
    'musicProducts',
    JSON.stringify(products.value)
  )
}

const handleImageUpload = (event) => {
  const file = event.target.files[0]

  if (!file) return

  const reader = new FileReader()

  reader.onload = () => {
    newProduct.value.image = reader.result
  }

  reader.readAsDataURL(file)
}

const addProduct = () => {
  if (
    !newProduct.value.name ||
    !newProduct.value.price ||
    !newProduct.value.image
  ) {
    alert('Please fill all required fields')
    return
  }

  products.value.push({
    ...newProduct.value
  })

  saveProducts()

  newProduct.value = {
    name: '',
    category: 'Guitar',
    description: '',
    price: '',
    stock: '',
    image: ''
  }

  alert('Product added successfully')
}

const deleteProduct = (index) => {
  products.value.splice(index, 1)
  saveProducts()
}

const editProduct = (index) => {
  const product = products.value[index]

  newProduct.value = { ...product }

  products.value.splice(index, 1)

  saveProducts()
}

onMounted(() => {
  loadProducts()
})
</script>