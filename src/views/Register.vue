<template>
  <div class="container mt-5" style="max-width: 500px">
    <PageHeader title="Create Account" />

    <ErrorAlert :message="err" />
    <div v-if="msg" class="alert alert-success">{{ msg }}</div>
 
    <div class="mb-3">
      <label class="form-label">Name</label>
      <input v-model="form.name" type="text" class="form-control" placeholder="Letters only, min 2 characters" />
    </div>
 
    <div class="mb-3">
      <label class="form-label">Email</label>
      <input v-model="form.email" type="email" class="form-control" placeholder="you@email.com" />
    </div>
 
    <div class="mb-3">
      <label class="form-label">Password</label>
      <input v-model="form.password" type="password" class="form-control" placeholder="Min. 8 characters" />
    </div>
 
    <button class="btn btn-primary w-100" @click="submit" :disabled="isLoading">
      {{ isLoading ? 'Registering...' : 'Register' }}
    </button>
 
    <p class="mt-3 text-center">
      Already have an account? <router-link to="/login">Login here</router-link>
    </p>
  </div>
</template>
 
<script>
import { registerUser } from '../api/register.js'
import ErrorAlert from '../components/ErrorAlert.vue'
import PageHeader from '../components/PageHeader.vue'

export default {
  name: 'Register',
  components: { ErrorAlert, PageHeader },
  data() {
    return {
      form: { name: '', email: '', password: '' },
      isLoading: false,
      err: '',
      msg: ''
    }
  },
  methods: {
    submit() {
      var self = this
      self.err = ''
      self.msg = ''
 
      // T7 — Form Validation
      if (!self.form.name || !self.form.email || !self.form.password) {
        self.err = 'All fields are required.'
        return
      }
      // Name: letters only (a-z, A-Z), no digits, no special chars, no spaces, min 2
      var nameRegex = /^[A-Za-z]+$/
      if (!nameRegex.test(self.form.name)) {
        self.err = 'Name must contain letters only (no numbers, spaces or special characters).'
        return
      }
      if (self.form.name.length < 2) {
        self.err = 'Name must be at least 2 characters.'
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
      registerUser(self.form.name, self.form.email, self.form.password)
        .then(function(data) {
          self.isLoading = false
          if (data && data.error) {
            self.err = data.error
          } else {
            self.msg = 'Account created successfully! Redirecting to login...'
            setTimeout(function() { self.$router.push('/login') }, 1500)
          }
        })
        .catch(function() {
          self.isLoading = false
          self.err = 'Registration failed. Please try again.'
        })
    }
  }
}
</script>
 
