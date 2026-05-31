<template>
  <!-- Empty-state placeholder: message plus an optional call-to-action link -->
  <div class="text-center text-muted py-5">
    <!-- Empty message -->
    <p class="fs-5 mb-3">{{ message }}</p>
    <!-- Optional action link  -->
    <router-link v-if="linkTo" :to="linkTo" class="btn btn-primary">
      {{ linkLabel }}
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'EmptyState',
  props: {
    message: { type: String, default: 'Nothing to show.' },
    linkTo: { type: String, default: '' },
    linkLabel: { type: String, default: '' }
  }
}
</script>
