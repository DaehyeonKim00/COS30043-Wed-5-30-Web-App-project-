<!--
  ============================================================
  TEMPORARY FAKE LOGIN — for testing only
  ------------------------------------------------------------
  Login.vue is officially assigned to a teammate (Hung) and is
  still "Not Started".

  This is a FAKE login: it does NOT call the backend at all.
  The Mercury server's PHP does not support password_hash(),
  so api_auth.php cannot be used. Instead, clicking "Login"
  just saves a dummy user (id = 1) so the user-specific pages
  (MyPage / OrderHistory / Review / Admin) can be tested.

  TO REMOVE LATER: revert this file back to the original stub
  (just <h1>Login</h1>) so the teammate can implement real auth.
  ============================================================
-->
<template>
  <div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-4">
      <h1 class="mb-4">Login</h1>

      <div class="alert alert-warning py-2">
        Test mode: any email/password works. No real authentication.
      </div>

      <form @submit.prevent="submitLogin">
        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input
            id="email"
            v-model="email"
            type="email"
            class="form-control"
            required
          />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input
            id="password"
            v-model="password"
            type="password"
            class="form-control"
            required
          />
        </div>

        <!-- Test-only: choose role so the Admin page can be tested -->
        <div class="form-check mb-3">
          <input
            id="asAdmin"
            v-model="asAdmin"
            type="checkbox"
            class="form-check-input"
          />
          <label for="asAdmin" class="form-check-label">
            Log in as admin (test only)
          </label>
        </div>

        <button class="btn btn-dark w-100" type="submit">Login</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data() {
    return {
      email: '',
      password: '',
      asAdmin: false
    }
  },
  methods: {
    submitLogin() {
      var self = this
      // FAKE login — no backend call. Save a dummy user so other
      // pages have a logged-in user to work with.
      var dummyUser = {
        id: 1,
        name: 'Test User',
        email: self.email,
        role: self.asAdmin ? 'admin' : 'user'
      }
      localStorage.setItem('user', JSON.stringify(dummyUser))
      self.$store.commit('setUser', dummyUser)
      self.$router.push('/home')
    }
  }
}
</script>

<style scoped>
</style>
