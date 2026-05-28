<template>
  <div class="auth-badge" role="status" aria-live="polite">
    <span
      class="auth-badge__dot"
      :class="{ 'auth-badge__dot--expired': isExpired }"
    ></span>
    <span>{{ badgeText }}</span>
  </div>
</template>

<script>
import { formatDuration } from '../utils/authSession.js'

export default {
  name: 'AuthBadge',
  data() {
    return {
      now: Date.now(),
      timerId: null
    }
  },
  computed: {
    expiresAt() {
      return this.$store.state.expiresAt
    },
    isExpired() {
      return !this.expiresAt || this.now >= this.expiresAt
    },
    badgeText() {
      if (this.isExpired) {
        return 'Session expired — please log in again'
      }
      var remaining = this.expiresAt - this.now
      return 'Expires in ' + formatDuration(remaining)
    }
  },
  mounted() {
    var self = this
    self.timerId = window.setInterval(() => {
      self.now = Date.now()
      if (self.isExpired && self.timerId) {
        window.clearInterval(self.timerId)
        self.timerId = null
      }
    }, 1000)
  },
  beforeUnmount() {
    if (this.timerId) {
      window.clearInterval(this.timerId)
      this.timerId = null
    }
  }
}
</script>
