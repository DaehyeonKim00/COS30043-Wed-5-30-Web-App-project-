<template>
  <div class="container py-5">
    <PageHeader title="Admin Product Management" />

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
          <select v-model="form.category" class="form-select mb-2">
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
            <option value="__new__">+ Add new category…</option>
          </select>
          <!-- Show free-text input when user picks "Add new category" -->
          <input
            v-if="form.category === '__new__'"
            v-model="newCategory"
            type="text"
            class="form-control"
            placeholder="Enter new category name"
          />
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

        <SuccessMessage :message="msg" />
      </div>
    </div>

    <h3>Product List</h3>

    <!-- Loading state -->
    <LoadingSpinner v-if="isLoading" />

    <!-- Error state -->
    <ErrorAlert v-else-if="err" :message="err" />

    <!-- Product table (responsive: horizontal scroll on small screens) -->
    <div v-else class="table-responsive">
      <table class="table table-bordered align-middle">
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
            <td class="text-nowrap">
              <button class="btn btn-sm btn-primary me-2 mb-1" @click="editProduct(product)">Edit</button>
              <button class="btn btn-sm btn-danger mb-1" @click="removeProduct(product.id)">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <p v-if="!isLoading && !err && products.length === 0" class="text-muted">
      No products found.
    </p>
  </div>
</template>

<script>
import { getProducts, addProduct, updateProduct, deleteProduct } from '../api/admin.js'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'
import SuccessMessage from '../components/SuccessMessage.vue'
import PageHeader from '../components/PageHeader.vue'

export default {
  name: 'Admin',
  components: { LoadingSpinner, ErrorAlert, SuccessMessage, PageHeader },
  data() {
    return {
      products: [],
      isLoading: false,
      err: '',
      msg: '',
      editingId: null,
      newCategory: '',
      form: {
        name: '',
        category: '',
        description: '',
        price: 0,
        stock: 0,
        image: ''
      }
    }
  },
  computed: {
    // Derive the category list from the products already in the DB,
    // so the dropdown always reflects every category actually used.
    categories() {
      var set = {}
      this.products.forEach(function(p) {
        if (p.category) set[p.category] = true
      })
      return Object.keys(set).sort()
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
          // Pre-select the first category once data is available
          if (!self.form.category && self.categories.length) {
            self.form.category = self.categories[0]
          }
        })
        .catch(error => {
          self.err = 'Failed to load products. Please try again later.'
          self.isLoading = false
        })
    },
    saveProduct() {
      var self = this

      // Resolve "Add new category" → actual category name
      if (self.form.category === '__new__') {
        var typed = (self.newCategory || '').trim()
        if (!typed) {
          alert('Please enter a name for the new category.')
          return
        }
        self.form.category = typed
      }

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
      this.newCategory = ''
      this.form = {
        name: '',
        category: this.categories[0] || '',
        description: '',
        price: 0,
        stock: 0,
        image: ''
      }
    }
  }
}
</script>

