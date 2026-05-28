<!-- Daehyeon Kim Advanced Feature -->
<template>
  <div class="search-autocomplete position-relative">
    <form class="d-flex" @submit.prevent="onEnter">
      <input
        v-model="keyword"
        type="search"
        class="form-control form-control-sm me-2"
        placeholder="Search products..."
        aria-label="Search"
        autocomplete="off"
        @input="onInput"
        @focus="onFocus"
        @blur="onBlur"
        @keydown.down.prevent="onArrowDown"
        @keydown.up.prevent="onArrowUp"
        @keydown.enter.prevent="onEnter"
        @keydown.esc="closeDropdown"
      />
      <button class="btn btn-primary btn-sm text-nowrap" type="submit">Search</button>
    </form>

    <ul
      v-if="showDropdown && suggestions.length > 0"
      class="list-group autocomplete-dropdown shadow"
    >
      <li
        v-for="(item, index) in suggestions"
        :key="item.id"
        class="list-group-item list-group-item-action d-flex align-items-center gap-2"
        :class="{ active: index === activeIndex }"
        @mousedown.prevent="selectItem(item)"
        @mouseenter="activeIndex = index"
      >
        <img
          v-if="item.image"
          :src="item.image"
          :alt="item.name"
          class="autocomplete-thumb"
        />
        <div class="flex-grow-1">
          <div class="fw-bold">{{ item.name }}</div>
          <small class="text-muted">{{ item.category }} — ${{ item.price }}</small>
        </div>
      </li>
    </ul>

    <div
      v-else-if="showDropdown && keyword.trim() && suggestions.length === 0"
      class="list-group autocomplete-dropdown shadow"
    >
      <div class="list-group-item text-muted">
        No products match "{{ keyword }}"
      </div>
    </div>
  </div>
</template>

<script>
import { getProducts } from '../api/productList.js'

export default {
  name: 'SearchAutocomplete',
  data() {
    return {
      keyword: '',
      suggestions: [],
      activeIndex: -1,
      showDropdown: false,
      debounceTimer: null,
      allProducts: []
    }
  },
  mounted() {
    var self = this
    getProducts()
      .then(data => {
        self.allProducts = Array.isArray(data) ? data : []
      })
      .catch(error => {
        self.allProducts = []
      })
  },
  methods: {
    onInput() {
      var self = this
      clearTimeout(self.debounceTimer)
      self.debounceTimer = setTimeout(function () {
        self.updateSuggestions()
      }, 300)
    },
    updateSuggestions() {
      var key = this.keyword.trim().toLowerCase()
      if (!key) {
        this.suggestions = []
        this.showDropdown = false
        return
      }
      this.suggestions = this.allProducts
        .filter(p => (p.name || '').toLowerCase().includes(key))
        .slice(0, 8)
      this.activeIndex = -1
      this.showDropdown = true
    },
    onArrowDown() {
      if (!this.showDropdown || this.suggestions.length === 0) return
      if (this.activeIndex < this.suggestions.length - 1) {
        this.activeIndex++
      }
    },
    onArrowUp() {
      if (!this.showDropdown || this.suggestions.length === 0) return
      if (this.activeIndex > 0) {
        this.activeIndex--
      }
    },
    onEnter() {
      // If a dropdown item is highlighted, jump straight to it.
      if (this.activeIndex >= 0 && this.suggestions[this.activeIndex]) {
        this.selectItem(this.suggestions[this.activeIndex])
        return
      }
      // Otherwise fall back to the full /products search page.
      var keyword = this.keyword.trim()
      if (keyword) {
        this.$router.push('/products?q=' + encodeURIComponent(keyword))
        this.closeDropdown()
        this.keyword = ''
      }
    },
    selectItem(product) {
      this.$router.push('/products/' + product.id)
      this.closeDropdown()
      this.keyword = ''
    },
    onFocus() {
      // Re-open dropdown if the user comes back with text still in the box.
      if (this.keyword.trim() && this.suggestions.length > 0) {
        this.showDropdown = true
      }
    },
    onBlur() {
      // @mousedown.prevent on items fires before blur, so the click is
      // already handled. Closing on the next tick avoids race conditions.
      var self = this
      setTimeout(function () {
        self.closeDropdown()
      }, 100)
    },
    closeDropdown() {
      this.showDropdown = false
      this.activeIndex = -1
    }
  }
}
</script>
