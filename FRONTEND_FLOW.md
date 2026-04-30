# Frontend File Flow Guide

> This document explains the file connection chain for each implemented View.
> Other team members should follow the same pattern when implementing their own Views.

---

## Completed Views

- [Home.vue](#1-homevue)
- [ProductList.vue](#2-productlistvue)

---

## 1. Home.vue

### Connection Chain

```
router/index.js
  { path: '/', component: Home }
        ↓
views/Home.vue
  ├── import getFeaturedProducts()   →   api/home.js
  │                                          ↓ fetch GET
  │                                  backend/api_products.php
  │                                          ↓ SELECT * FROM products
  │                                  DB: products table
  │
  ├── import ProductCard             →   components/ProductCard.vue
  │     props: product { id, name, category, description, price, image }
  │     router-link → /products/:id
  │
  └── import PaginationBar           →   components/PaginationBar.vue
        props: :pageCount="getPageCount"
        emit:  @page-change="clickCallback"
        └── uses vuejs-paginate-next (npm package)
```

### Functions imported from PaginationBar.vue

| Function | Usage in Home.vue |
|----------|------------------|
| `calcPageCount(products, perPage)` | `getPageCount` computed |
| `getPaginatedItems(products, currentPage, perPage)` | `getItems` computed |

### Data flow inside Home.vue

```
mounted()
  var self = this
  getFeaturedProducts()
    .then(data → self.products = data)
    .catch(error → self.err = '...')

computed:
  getPageCount  →  calcPageCount(this.products, this.perPage)
  getItems      →  getPaginatedItems(this.products, this.currentPage, this.perPage)

watch:
  perPage → reset currentPage = 1

methods:
  clickCallback(pageNum) → this.currentPage = Number(pageNum)
```

---

## 2. ProductList.vue

### Connection Chain

```
router/index.js
  { path: '/products', component: ProductList }
        ↓
views/ProductList.vue
  ├── import getProducts()           →   api/productList.js
  │                                          ↓ fetch GET
  │                                  backend/api_products.php
  │                                          ↓ SELECT * FROM products
  │                                  DB: products table
  │
  ├── import ProductCard             →   components/ProductCard.vue
  │     props: product { id, name, category, description, price, image }
  │     router-link → /products/:id
  │
  └── import PaginationBar           →   components/PaginationBar.vue
        props: :pageCount="getPageCount"
        emit:  @page-change="clickCallback"
        └── uses vuejs-paginate-next (npm package)
```

### Functions imported from PaginationBar.vue

| Function | Usage in ProductList.vue |
|----------|--------------------------|
| `calcPageCount(filteredProducts, perPage)` | `getPageCount` computed |
| `getPaginatedItems(filteredProducts, currentPage, perPage)` | `getItems` computed |

### Data flow inside ProductList.vue

```
mounted()
  var self = this
  getProducts()
    .then(data → self.products = data)
    .catch(error → self.err = '...')

computed:
  categories        →  unique list from products (for category dropdown)
  filteredProducts  →  products filtered by filter.name + filter.category
  getPageCount      →  calcPageCount(this.filteredProducts, this.perPage)
  getItems          →  getPaginatedItems(this.filteredProducts, this.currentPage, this.perPage)

watch:
  perPage           → reset currentPage = 1
  filter.name       → reset currentPage = 1
  filter.category   → reset currentPage = 1

methods:
  clickCallback(pageNum) → this.currentPage = Number(pageNum)
```

---

## Shared Component: PaginationBar.vue

All Views use `PaginationBar.vue` the same way.

### What PaginationBar.vue exports

```js
// Import in any View like this:
import PaginationBar, { calcPageCount, getPaginatedItems } from '../components/PaginationBar.vue'
```

| Export | Type | Description |
|--------|------|-------------|
| `default` | Component | `<PaginationBar>` UI (Prev / page numbers / Next) |
| `calcPageCount(items, perPage)` | Function | Returns total page count |
| `getPaginatedItems(items, currentPage, perPage)` | Function | Returns sliced items for current page |

### Usage in template

```vue
<PaginationBar
  :pageCount="getPageCount"
  @page-change="clickCallback"
/>
```

---

## Pattern Reference (Week 8/9 Sample Code)

| Pattern | Source | Used in |
|---------|--------|---------|
| `var self = this` + `mounted()` fetch | Week 9 api1.js | All Views |
| `filteredProducts` computed | Week 9 applookup2.js | ProductList.vue |
| `getItems` computed (slice) | Week 9 applookup2.js | All Views |
| `getPageCount` computed (Math.ceil) | Week 9 applookup2.js | All Views |
| `clickCallback(pageNum)` method | Week 9 applookup2.js | All Views |
| `fetch().then().catch()` chain | Week 8 fetch examples | api/*.js |

---

## For Other Team Members

When implementing a new View, follow this checklist:

1. **router/index.js** — confirm the route is already connected
2. **src/api/yourPage.js** — write `fetch().then().catch()` pointing to the correct PHP file
3. **src/views/YourPage.vue** — follow the pattern below:

```js
import YourApi from '../api/yourPage.js'
import PaginationBar, { calcPageCount, getPaginatedItems } from '../components/PaginationBar.vue'

data() {
  return { items: [], isLoading: false, err: '', msg: '', currentPage: 1, perPage: 12 }
},
computed: {
  getPageCount() { return calcPageCount(this.items, this.perPage) },
  getItems()     { return getPaginatedItems(this.items, this.currentPage, this.perPage) }
},
watch: {
  perPage() { this.currentPage = 1 }
},
mounted() {
  var self = this
  self.isLoading = true
  YourApi()
    .then(data => { self.items = data; self.isLoading = false })
    .catch(error => { self.err = 'Failed to load.'; self.isLoading = false })
},
methods: {
  clickCallback(pageNum) { this.currentPage = Number(pageNum) }
}
```
