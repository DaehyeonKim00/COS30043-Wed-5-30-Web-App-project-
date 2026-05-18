<!--
  ============================================================
  TEMPORARY REGISTER (test mode) — for testing only
  ------------------------------------------------------------
  Register.vue is officially assigned to a teammate (Hung) and
  is still "Not Started".

  Real registration cannot work right now: the Mercury server's
  PHP does not support password_hash(), so api_auth.php fails.
  Since login is currently a FAKE login (see Login.vue), there
  is nothing for Register to do, so this page just guides the
  user to the test login.

  TO REMOVE LATER: revert this file back to the original stub
  (just <h1>Register</h1>) so the teammate can implement it.
  ============================================================
-->
<template>
  <div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-6">
      <h1 class="mb-4">Register</h1>

      <div class="alert alert-info">
        <p class="mb-2">
          Registration is not available in test mode.
        </p>
        <p class="mb-0">
          The login is currently a temporary test login — just go to the
          Login page and sign in with any email and password.
        </p>
      </div>

      <router-link to="/login" class="btn btn-dark">Go to Login</router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Register'
}
</script>

<style scoped>
</style>
