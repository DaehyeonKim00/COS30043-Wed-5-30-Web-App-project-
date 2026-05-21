<template>
  <router-link :to="to" class="btn btn-outline-secondary mb-4">
    &larr; {{ label }}
  </router-link>
</template>

<script>
export default {
  name: 'BackButton',
  props: {
    to: { type: String, required: true },
    label: { type: String, default: 'Back' }
  }
}
</script>
