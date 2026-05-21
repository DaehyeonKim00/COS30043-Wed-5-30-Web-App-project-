<template>
  <p v-if="message" class="text-success mt-3 mb-0">{{ message }}</p>
</template>

<script>
export default {
  name: 'SuccessMessage',
  props: {
    message: {
      type: String,
      default: ''
    }
  }
}
</script>
