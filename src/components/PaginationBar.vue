<template>
  <div v-if="pageCount > 1">
    <paginate
      :page-count="pageCount"
      :page-range="5"
      :margin-pages="1"
      :click-handler="clickCallback"
      :prev-text="'Prev'"
      :next-text="'Next'"
      :container-class="'pagination justify-content-center flex-wrap'"
      :page-class="'page-item'"
      :page-link-class="'page-link'"
      :prev-class="'page-item'"
      :prev-link-class="'page-link'"
      :next-class="'page-item'"
      :next-link-class="'page-link'"
      :active-class="'active'"
    />
  </div>
</template>

<script>
import VuejsPaginateNext from 'vuejs-paginate-next'

export function calcPageCount(items, perPage) {
  return Math.ceil(items.length / perPage)
}

export function getPaginatedItems(items, currentPage, perPage) {
  let current = currentPage * perPage
  let start = current - perPage
  return items.slice(start, current)
}

export default {
  name: 'PaginationBar',
  components: {
    paginate: VuejsPaginateNext
  },
  props: {
    pageCount: {
      type: Number,
      required: true
    }
  },
  emits: ['page-change'],
  methods: {
    clickCallback(pageNum) {
      this.$emit('page-change', Number(pageNum))
    }
  }
}
</script>
