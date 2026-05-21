<template>
  <div class="container mt-5" style="max-width: 500px">
    <PageHeader title="Login" />

    <ErrorAlert :message="err" />

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input v-model="form.email" type="email" class="form-control" placeholder="you@email.com" />
    </div>

    <div class="mb-3">
      <label class="form-label">Password</label>
      <input v-model="form.password" type="password" class="form-control" placeholder="Min. 8 characters" />
    </div>

    <button class="btn btn-primary w-100" @click="submit" :disabled="isLoading">
      {{ isLoading ? 'Logging in...' : 'Login' }}
    </button>

    <p class="mt-3 text-center">
      No account yet? <router-link to="/register">Register here</router-link>
    </p>
  </div>
</template>

<script>
import { loginUser } from '../api/login.js'
import ErrorAlert from '../components/ErrorAlert.vue'
import PageHeader from '../components/PageHeader.vue'

export default {
  name: 'Login',
  components: { ErrorAlert, PageHeader },
  data() {
    return {
      form: { email: '', password: '' },
      isLoading: false,
      err: ''
    }
  },
  methods: {
    submit() {
      var self = this
      self.err = ''

      // Form Validation
      if (!self.form.email || !self.form.password) {
        self.err = 'Email and password are required.'
        return
      }
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!emailRegex.test(self.form.email)) {
        self.err = 'Please enter a valid email address.'
        return
      }
      if (self.form.password.length < 8) {
        self.err = 'Password must be at least 8 characters.'
        return
      }

      self.isLoading = true
      loginUser(self.form.email, self.form.password)
        .then(function(user) {
          self.isLoading = false
          if (!user || user.error) {
            self.err = 'Invalid email or password.'
          } else {
            // Save to localStorage (used by router auth guard)
            localStorage.setItem('user', JSON.stringify(user))
            // Save to Vuex store (used by Navbar to show/hide links)
            self.$store.commit('setUser', user)
            // Sync cart so Navbar count reflects this user's saved items
            self.$store.dispatch('fetchCart')
            self.$router.push('/')
          }
        })
        .catch(function() {
          self.isLoading = false
          self.err = 'Login failed. Please try again.'
        })
    }
  }
}
</script>
