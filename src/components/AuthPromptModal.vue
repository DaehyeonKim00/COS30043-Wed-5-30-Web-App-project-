<template>
  <div v-if="show" class="auth-modal-overlay" @click.self="cancel">
    <div class="auth-modal">
      <p class="auth-modal-msg">{{ message }}</p>
      <div class="auth-modal-actions">
        <button class="btn btn-outline-secondary btn-sm" @click="cancel">Cancel</button>
        <router-link to="/login" class="btn btn-dark btn-sm" @click="cancel">Login</router-link>
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

<style scoped>
.auth-modal-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}
.auth-modal {
  background: #fff;
  border-radius: 12px;
  padding: 24px 28px;
  min-width: 280px;
  max-width: 360px;
  text-align: center;
  box-shadow: 0 8px 32px rgba(0,0,0,0.18);
}
.auth-modal-msg {
  font-size: 15px;
  margin-bottom: 20px;
  color: #333;
}
.auth-modal-actions {
  display: flex;
  gap: 10px;
  justify-content: center;
}
</style>