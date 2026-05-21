<template>
  <div>
    <PageHeader title="Checkout" />

    <LoadingSpinner v-if="isLoading" />

    <ErrorAlert v-else-if="err" :message="err" />

    <EmptyState
      v-else-if="items.length === 0"
      message="Your cart is empty."
      link-to="/products"
      link-label="Browse Products"
    />

    <div v-else class="row g-4">

      <!-- Left: Shipping + Payment form -->
      <div class="col-12 col-lg-7">

        <!-- Shipping details -->
        <div class="card shadow-sm mb-4">
          <div class="card-body">
            <h4 class="mb-3">Shipping Details</h4>

            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input v-model="form.name" type="text" class="form-control" placeholder="Letters only, min 2 characters" />
            </div>

            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input v-model="form.phone" type="text" class="form-control" placeholder="Digits only, 8-15 characters" />
            </div>

            <div class="mb-3">
              <label class="form-label">Address</label>
              <textarea v-model="form.address" class="form-control" rows="2" placeholder="Street, city, postcode"></textarea>
            </div>
          </div>
        </div>

        <!-- Payment method -->
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="mb-3">Payment Method</h4>

            <div class="form-check mb-2">
              <input
                id="payCash"
                v-model="form.payment"
                class="form-check-input"
                type="radio"
                value="cash"
              />
              <label class="form-check-label" for="payCash">
                Cash on Delivery
              </label>
            </div>

            <div class="form-check">
              <input
                id="payCard"
                v-model="form.payment"
                class="form-check-input"
                type="radio"
                value="card"
              />
              <label class="form-check-label" for="payCard">
                Credit / Debit Card
              </label>
            </div>

            <p class="text-muted small mt-3 mb-0">
              Payment processing is simulated for this demo.
            </p>
          </div>
        </div>
      </div>

      <!-- Right: Order summary -->
      <div class="col-12 col-lg-5">
        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="mb-3">Order Summary</h4>

            <ul class="list-group list-group-flush mb-3">
              <li
                v-for="item in items"
                :key="item.id"
                class="list-group-item d-flex justify-content-between align-items-center px-0"
              >
                <span>
                  {{ item.name }}
                  <span class="text-muted">&times; {{ item.quantity }}</span>
                </span>
                <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
              </li>
            </ul>

            <div class="d-flex justify-content-between fw-bold fs-5 mb-3">
              <span>Total</span>
              <span>${{ totalPrice }}</span>
            </div>

            <ErrorAlert :message="submitErr" />

            <button
              class="btn btn-primary w-100"
              :disabled="isSubmitting"
              @click="submitOrder"
            >
              {{ isSubmitting ? 'Placing Order...' : 'Place Order' }}
            </button>

            <SuccessMessage :message="msg" />
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import { getCart } from '../api/cart.js'
import { placeOrder } from '../api/checkout.js'
import LoadingSpinner from '../components/LoadingSpinner.vue'
import ErrorAlert from '../components/ErrorAlert.vue'
import SuccessMessage from '../components/SuccessMessage.vue'
import PageHeader from '../components/PageHeader.vue'
import EmptyState from '../components/EmptyState.vue'

export default {
  name: 'Checkout',
  components: { LoadingSpinner, ErrorAlert, SuccessMessage, PageHeader, EmptyState },
  data() {
    return {
      items: [],
      isLoading: false,
      isSubmitting: false,
      err: '',
      submitErr: '',
      msg: '',
      user: null,
      form: {
        name: '',
        phone: '',
        address: '',
        payment: 'cash'
      }
    }
  },
  computed: {
    totalPrice() {
      return this.items.reduce(function(sum, item) {
        return sum + item.price * item.quantity
      }, 0).toFixed(2)
    }
  },
  mounted() {
    var self = this
    self.user = JSON.parse(localStorage.getItem('user'))
    if (!self.user) {
      self.$router.push('/login')
      return
    }

    self.form.name = self.user.name || ''

    self.isLoading = true
    getCart(self.user.id)
      .then(function(data) {
        self.items = Array.isArray(data) ? data : []
        self.isLoading = false
      })
      .catch(function() {
        self.err = 'Failed to load cart.'
        self.isLoading = false
      })
  },
  methods: {
    submitOrder() {
      var self = this
      self.submitErr = ''
      self.msg = ''

      // Form validation
      var nameRegex = /^[A-Za-z ]+$/
      if (!self.form.name || !nameRegex.test(self.form.name) || self.form.name.trim().length < 2) {
        self.submitErr = 'Please enter a valid name (letters only, min 2 characters).'
        return
      }
      var phoneRegex = /^[0-9]{8,15}$/
      if (!phoneRegex.test(self.form.phone)) {
        self.submitErr = 'Please enter a valid phone number (digits only, 8-15 characters).'
        return
      }
      if (!self.form.address || self.form.address.trim().length < 5) {
        self.submitErr = 'Please enter a valid shipping address.'
        return
      }
      if (!self.form.payment) {
        self.submitErr = 'Please select a payment method.'
        return
      }

      var payload = self.items.map(function(it) {
        return {
          product_id: it.product_id,
          quantity: it.quantity,
          price: it.price
        }
      })

      self.isSubmitting = true
      placeOrder(self.user.id, self.totalPrice, payload)
        .then(function(data) {
          self.isSubmitting = false
          console.log('Checkout response:', data)
          if (data && data.success) {
            self.msg = 'Order #' + data.order_id + ' placed successfully! Redirecting...'
            // Refresh Vuex cart so Navbar count resets to 0
            self.$store.dispatch('fetchCart')
            setTimeout(function() {
              self.$router.push('/orderhistory')
            }, 1500)
          } else {
            self.submitErr = (data && data.error) ? data.error : 'Failed to place order.'
          }
        })
        .catch(function(error) {
          self.isSubmitting = false
          console.error('Checkout error:', error)
          self.submitErr = 'Failed to place order. Please try again.'
        })
    }
  }
}
</script>
