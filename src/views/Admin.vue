<template>
  <div class="container py-5">
    <h1 class="mb-4">Admin Product Management</h1>

    <!-- Product form -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h3>{{ editingId ? 'Edit Product' : 'Add Product' }}</h3>

        <div class="mb-3">
          <label class="form-label">Product Name</label>
          <input v-model="form.name" class="form-control" type="text">
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
          <label class="form-label">Description</label>
          <textarea v-model="form.description" class="form-control" rows="3"></textarea>
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
          <label class="form-label">Image URL</label>
          <input v-model="form.image" class="form-control" type="text">
        </div>

        <img v-if="form.image" :src="form.image" class="preview-img mb-3">

        <br>

        <button class="btn btn-dark me-2" @click="saveProduct">
          {{ editingId ? 'Update Product' : 'Add Product' }}
        </button>

        <button v-if="editingId" class="btn btn-secondary" @click="cancelEdit">
          Cancel
        </button>

        <p v-if="msg" class="text-success mt-3">{{ msg }}</p>
      </div>
    </div>

    <h3>Product List</h3>

    <!-- Loading state -->
    <div v-if="isLoading" class="text-center py-5">
      <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- Error state -->
    <div v-else-if="err" class="alert alert-danger">
      {{ err }}
    </div>

    <!-- Product table -->
    <table v-else class="table table-bordered align-middle">
      <thead>
        <tr>
          <th>Image</th>
          <th>Name</th>
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
          <td>{{ product.category }}</td>
          <td>${{ product.price }}</td>
          <td>{{ product.stock }}</td>
          <td>
            <button class="btn btn-sm btn-primary me-2" @click="editProduct(product)">Edit</button>
            <button class="btn btn-sm btn-danger" @click="removeProduct(product.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <p v-if="!isLoading && !err && products.length === 0" class="text-muted">
      No products found.
    </p>
  </div>
</template>

<script>
import { getProducts, addProduct, updateProduct, deleteProduct } from '../api/admin.js'

export default {
  name: 'Admin',
  data() {
    return {
      products: [],
      isLoading: false,
      err: '',
      msg: '',
      editingId: null,
      form: {
        name: '',
        category: 'Guitar',
        description: '',
        price: 0,
        stock: 0,
        image: ''
      }
    }
  },
  mounted() {
    this.loadProducts()
  },
  methods: {
    loadProducts() {
      var self = this
      self.isLoading = true
      getProducts()
        .then(data => {
          self.products = data
          self.isLoading = false
        })
        .catch(error => {
          self.err = 'Failed to load products. Please try again later.'
          self.isLoading = false
        })
    },
    saveProduct() {
      var self = this
      if (!self.form.name || self.form.price <= 0) {
        alert('Please fill product name and price.')
        return
      }

      if (self.editingId) {
        updateProduct({ id: self.editingId, ...self.form })
          .then(data => {
            self.msg = 'Product updated successfully.'
            self.resetForm()
            self.loadProducts()
          })
          .catch(error => {
            self.err = 'Failed to update product.'
          })
      } else {
        addProduct({ ...self.form })
          .then(data => {
            self.msg = 'Product added successfully.'
            self.resetForm()
            self.loadProducts()
          })
          .catch(error => {
            self.err = 'Failed to add product.'
          })
      }
    },
    editProduct(product) {
      this.editingId = product.id
      this.form = {
        name: product.name,
        category: product.category,
        description: product.description,
        price: product.price,
        stock: product.stock,
        image: product.image
      }
    },
    removeProduct(id) {
      var self = this
      deleteProduct(id)
        .then(data => {
          self.msg = 'Product deleted successfully.'
          self.loadProducts()
        })
        .catch(error => {
          self.err = 'Failed to delete product.'
        })
    },
    cancelEdit() {
      this.resetForm()
    },
    resetForm() {
      this.editingId = null
      this.form = {
        name: '',
        category: 'Guitar',
        description: '',
        price: 0,
        stock: 0,
        image: ''
      }
    }
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
