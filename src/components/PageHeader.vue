<template>
  <h1 class="mb-4">{{ title }}</h1>
</template>

<script>
export default {
  name: 'PageHeader',
  props: {
    title: { type: String, required: true }
  }
}
</script>
