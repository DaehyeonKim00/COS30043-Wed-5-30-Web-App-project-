<template>
  <div v-if="message" class="alert alert-danger">{{ message }}</div>
</template>

<script>
export default {
  name: 'ErrorAlert',
  props: {
    message: {
      type: String,
      default: ''
    }
  }
}
</script>
