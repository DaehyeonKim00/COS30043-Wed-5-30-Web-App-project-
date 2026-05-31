<template>
  <!-- Login prompt modal: overlay click outside closes it -->
  <div v-if="show" class="auth-modal-overlay" @click.self="cancel">
    <div class="auth-modal">
      <!-- Prompt message -->
      <p class="auth-modal-msg">{{ message }}</p>
      <!-- Action buttons: dismiss or go to login -->
      <div class="auth-modal-actions">
        <button class="btn btn-outline-secondary btn-sm" @click="cancel">Cancel</button>
        <router-link to="/login" class="btn btn-dark btn-sm">Login</router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AuthPromptModal',
  props: {
    show: { type: Boolean, default: false },
    message: { type: String, default: 'Please log in to continue.' }
  },
  emits: ['cancel'],
  methods: {
    cancel() {
      this.$emit('cancel')
    }
  }
}
</script>
