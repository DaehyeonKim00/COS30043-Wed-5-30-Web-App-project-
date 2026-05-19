# Frontend File Flow Guide

> This document explains the file connection chain for each implemented View.
> Other team members should follow the same pattern when implementing their own Views.

---

## Summary Table — File & Data Flow by Page

| View (`/views`) | Components (`/components`) | API JS (`/api`) | Backend PHP (`/backend`) | DB Table | Operation |
|-----------------|---------------------------|-----------------|--------------------------|----------|-----------|
| `Home.vue` | `ProductCard.vue` `PaginationBar.vue` | `home.js` | `api_products.php` | `products` | GET all products |
| `ProductList.vue` | `ProductCard.vue` `PaginationBar.vue` | `productList.js` | `api_products.php` | `products` | GET all products (client-side filter + sort) |
| `ProductDetail.vue` | — | `productDetail.js` | `api_products.php` | `products` | GET single product by id |
| `ProductDetail.vue` | — | `wishlist.js` | `api_wishlist.php` | `wishlist` | GET wishlist by user_id |
| `ProductDetail.vue` | — | `wishlist.js` | `api_wishlist.php` | `wishlist` | POST add product to wishlist |
| `ProductDetail.vue` | — | `wishlist.js` | `api_wishlist.php` | `wishlist` | DELETE remove product from wishlist |
| `About.vue` | — | — (no API file) | — | — | Fully static, no fetch |

> **Note:** `SearchResult.vue` and `searchResult.js` have been removed.
> Navbar search navigates to `/products?q=keyword` and `ProductList.vue` reads `$route.query.q` on mount.

---

## Completed Views

- [Home.vue](#1-homevue)
- [ProductList.vue](#2-productlistvue)
- [ProductDetail.vue](#3-productdetailvue)
- [About.vue](#4-aboutvue)

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

| Function                                              | Usage in Home.vue         |
| ----------------------------------------------------- | ------------------------- |
| `calcPageCount(products, perPage)`                  | `getPageCount` computed |
| `getPaginatedItems(products, currentPage, perPage)` | `getItems` computed     |

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

| Function                                                      | Usage in ProductList.vue  |
| ------------------------------------------------------------- | ------------------------- |
| `calcPageCount(filteredProducts, perPage)`                  | `getPageCount` computed |
| `getPaginatedItems(filteredProducts, currentPage, perPage)` | `getItems` computed     |

### Navbar Search Integration

```
components/Navbar.vue
  submitSearch()
    → this.$router.push('/products?q=' + keyword)

views/ProductList.vue
  mounted()
    → this.filter.name = this.$route.query.q || ''   (read URL query on load)

  watch: '$route.query.q'
    → this.filter.name = newQ || ''                  (react to new Navbar searches)
```

### Data flow inside ProductList.vue

```
mounted()
  var self = this
  self.filter.name = self.$route.query.q || ''   // Navbar search keyword
  getProducts()
    .then(data → self.products = data)
    .catch(error → self.err = '...')

computed:
  categories        →  unique list from products (for category dropdown)
  filteredProducts  →  products filtered by filter.name + filter.category
  sortedProducts    →  filteredProducts sorted by sortBy (price/name asc/desc)
  getPageCount      →  calcPageCount(this.sortedProducts, this.perPage)
  getItems          →  getPaginatedItems(this.sortedProducts, this.currentPage, this.perPage)

watch:
  perPage           → reset currentPage = 1
  filter.name       → reset currentPage = 1
  filter.category   → reset currentPage = 1
  sortBy            → reset currentPage = 1
  $route.query.q    → update filter.name + reset currentPage = 1

methods:
  clickCallback(pageNum) → this.currentPage = Number(pageNum)
```

### Search & Sort criteria (meets assignment requirement)

| Criteria | Method |
|----------|--------|
| Keyword search | Navbar search bar → `/products?q=keyword` |
| Category filter | Dropdown on ProductList page |
| Sort by price | Low to High / High to Low |
| Sort by name | A to Z / Z to A |

---

## 3. ProductDetail.vue

### Connection Chain

```
router/index.js
  { path: '/products/:id', component: ProductDetail }
        ↓
views/ProductDetail.vue
  ├── import getProductById()        →   api/productDetail.js
  │                                          ↓ fetch GET ?id={productId}
  │                                  backend/api_products.php
  │                                          ↓ SELECT * FROM products WHERE id=?
  │                                  DB: products table
  │
  ├── import getWishlist()           →   api/wishlist.js
  │     (on mount, if user logged in)        ↓ fetch GET ?user_id=
  │                                  backend/api_wishlist.php
  │                                          ↓ SELECT wishlist JOIN products WHERE user_id=?
  │                                  DB: wishlist table
  │
  ├── import addToWishlist()         →   api/wishlist.js
  │     (on Add to Wishlist click)           ↓ fetch POST
  │                                  backend/api_wishlist.php
  │                                          ↓ INSERT INTO wishlist
  │                                  DB: wishlist table
  │
  └── import removeFromWishlist()    →   api/wishlist.js
        (on Remove from Wishlist click)      ↓ fetch DELETE
                                     backend/api_wishlist.php
                                             ↓ DELETE FROM wishlist WHERE user_id=? AND product_id=?
                                     DB: wishlist table
```

### Backend change required

`api_wishlist.php` GET query must include `products.id as product_id`:

```sql
SELECT wishlist.id, products.id as product_id, products.name, products.price, products.image, products.category
FROM wishlist JOIN products ON wishlist.product_id = products.id
WHERE wishlist.user_id = $user_id
```

This allows the frontend to check if the current product is already in the wishlist.

### Data flow inside ProductDetail.vue

```
mounted()
  var self = this
  var productId = self.$route.params.id       // get ID from URL
  self.user = JSON.parse(localStorage.getItem('user'))  // check login

  getProductById(productId)
    .then(data → self.product = data)
    .then → if user logged in:
      getWishlist(user.id)
        .then(items → self.inWishlist = items.some(item => item.product_id == productId))
    .catch(error → self.err = '...')

methods:
  toggleWishlist()
    if inWishlist → removeFromWishlist(userId, productId) → inWishlist = false
    else          → addToWishlist(userId, productId)      → inWishlist = true
```

### Wishlist button behavior

| User state    | inWishlist | Button shown                          |
| ------------- | ---------- | ------------------------------------- |
| Not logged in | -          | "Log in to add to wishlist" link      |
| Logged in     | false      | `Add to Wishlist` (outline-danger)  |
| Logged in     | true       | `Remove from Wishlist` (btn-danger) |

---

## 4. About.vue

### Connection Chain

```
router/index.js
  { path: '/about', component: About }
        ↓
views/About.vue
  └── No API file, no backend call — fully static page
```

> **Note:** `About.vue` is a fully static informational page. No `api/` file and no PHP backend
> are involved. All content is defined directly in `data()` and the template.

### Data flow inside About.vue

```
data():
  features → hardcoded array [ { title, desc } × 3 ]
  team     → hardcoded array [ { initial, name, role } × 5 ]

No mounted(), no fetch, no isLoading/err states.
```

### Sections rendered

| Section        | Content source             | Template block      |
| -------------- | -------------------------- | ------------------- |
| Hero banner    | `info.title`, `info.description` from `about.js` | `v-else` top        |
| Mission text   | Static in template         | row + col-md-7      |
| Features cards | `features` array in `data()` | `v-for` card grid   |
| Team cards     | `team` array in `data()`   | `v-for` card grid   |
| CTA buttons    | Static router-links        | border-top section  |

---

## Shared Component: PaginationBar.vue

All Views use `PaginationBar.vue` the same way.

### What PaginationBar.vue exports

```js
// Import in any View like this:
import PaginationBar, { calcPageCount, getPaginatedItems } from '../components/PaginationBar.vue'
```

| Export                                             | Type      | Description                                         |
| -------------------------------------------------- | --------- | --------------------------------------------------- |
| `default`                                        | Component | `<PaginationBar>` UI (Prev / page numbers / Next) |
| `calcPageCount(items, perPage)`                  | Function  | Returns total page count                            |
| `getPaginatedItems(items, currentPage, perPage)` | Function  | Returns sliced items for current page               |

### Usage in template

```vue
<PaginationBar
  :pageCount="getPageCount"
  @page-change="clickCallback"
/>
```

---

## Pattern Reference (Week 8/9 Sample Code)

| Pattern                                   | Source                  | Used in           |
| ----------------------------------------- | ----------------------- | ----------------- |
| `var self = this` + `mounted()` fetch | Week 9 api1.js          | All Views         |
| `filteredProducts` computed             | Week 9 applookup2.js    | ProductList.vue   |
| `getItems` computed (slice)             | Week 9 applookup2.js    | Home, ProductList |
| `getPageCount` computed (Math.ceil)     | Week 9 applookup2.js    | Home, ProductList |
| `clickCallback(pageNum)` method         | Week 9 applookup2.js    | Home, ProductList |
| `fetch().then().catch()` chain GET      | Week 8 fetch requesting | api/*.js          |
| `fetch POST` with JSON body             | Week 8 fetch inserting  | wishlist.js       |
| `fetch DELETE` with JSON body           | Week 8 fetch deleting   | wishlist.js       |
| Nested `.then()` (fetch inside fetch)   | Week 8 fetch patterns   | ProductDetail.vue |

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
