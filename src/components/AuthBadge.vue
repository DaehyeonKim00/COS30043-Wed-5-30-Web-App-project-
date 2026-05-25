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
import { formatDuration } from "../utils/authSession.js";

export default {
  name: "AuthBadge",
  data() {
    return {
      now: Date.now(),
      timerId: null,
    };
  },
  computed: {
    expiresAt() {
      return this.$store.state.expiresAt;
    },
    isExpired() {
      return !this.expiresAt || this.now >= this.expiresAt;
    },
    badgeText() {
      if (this.isExpired) {
        return "Expired";
      }

      const remaining = this.expiresAt - this.now;
      return `Expires in ${formatDuration(remaining)}`;
    },
  },
  mounted() {
    this.timerId = window.setInterval(() => {
      this.now = Date.now();
      if (this.isExpired && this.timerId) {
        window.clearInterval(this.timerId);
        this.timerId = null;
      }
    }, 1000);
  },
  beforeUnmount() {
    if (this.timerId) {
      window.clearInterval(this.timerId);
      this.timerId = null;
    }
  },
};
</script>

<style scoped>
.auth-badge {
  position: fixed;
  right: 1rem;
  bottom: 1rem;
  z-index: 1080;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.6rem 0.9rem;
  border-radius: 999px;
  background: rgba(20, 20, 20, 0.92);
  color: #fff;
  font-size: 0.8rem;
  font-weight: 600;
  box-shadow: 0 10px 24px rgba(0, 0, 0, 0.18);
  backdrop-filter: blur(10px);
}

.auth-badge__dot {
  width: 0.55rem;
  height: 0.55rem;
  border-radius: 50%;
  background: #7cff7a;
  box-shadow: 0 0 0 4px rgba(124, 255, 122, 0.16);
}

.auth-badge__dot--expired {
  background: #ff6b6b;
  box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.16);
}
</style>
