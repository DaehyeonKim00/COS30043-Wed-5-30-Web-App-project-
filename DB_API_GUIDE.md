# DB & API Structure Guide

---

## Part 0 — Current Project Database Info

- **Database name:** `s104838522_db`
- **Backend API folder:** `backend/`
- **Frontend API folder:** `src/api/`
- **Backend style:** PHP + MySQLi + JSON response

---

## Part 1 — MySQL DB & Table Structure

| Table | Columns |
|-------|---------|
| `users` | id, name, email, password, created_at, role |
| `products` | id, name, category, description, price, image, stock, created_at |
| `cart` | id, user_id, product_id, quantity |
| `orders` | id, user_id, total_price, status, created_at |
| `order_items` | id, order_id, product_id, quantity, price |
| `wishlist` | id, user_id, product_id |
| `reviews` | id, user_id, product_id, rating, comment, created_at |

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  role VARCHAR(20) DEFAULT 'user'
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  category VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  image VARCHAR(500) DEFAULT NULL,
  stock INT DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cart (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT DEFAULT 1,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  total_price DECIMAL(10, 2) NOT NULL,
  status VARCHAR(50) DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE wishlist (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  rating INT NOT NULL,
  comment TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);
```

---

## Part 2 — PHP API Files

| PHP File | Role |
|----------|------|
| `api_auth.php` | Login, Register |
| `api_products.php` | Get products, Search, Filter, Single product |
| `api_cart.php` | Get cart, Add to cart, Remove from cart |
| `api_checkout.php` | Place order and clear cart |
| `api_orders.php` | Get order history |
| `api_mypage.php` | Get user info, Update user info |
| `api_review.php` | Get, Post, Update, Delete reviews |
| `api_admin.php` | Get all products, Add, Update, Delete products |
| `api_wishlist.php` | Get wishlist, Add to wishlist, Remove from wishlist |

---

## Part 3 — src/api/ to PHP Mapping

| src/api JS File | Backend PHP File |
|-----------------|------------------|
| `home.js` | `api_products.php` |
| `productList.js` | `api_products.php` |
| `productDetail.js` | `api_products.php` |
| `login.js` | `api_auth.php` |
| `register.js` | `api_auth.php` |
| `cart.js` | `api_cart.php` |
| `checkout.js` | `api_checkout.php` |
| `myPage.js` | `api_mypage.php` |
| `orderHistory.js` | `api_orders.php` |
| `review.js` | `api_review.php` |
| `admin.js` | `api_admin.php` |
| `wishlist.js` | `api_wishlist.php` |
| `about.js` | static page, no API |

---

## Part 4 — Current Backend API Behaviour

### `api_products.php`
- `GET` returns all products
- `GET ?search=keyword` searches by product `name` or `category`
- `GET ?category=...` filters by category
- `GET ?id=...` returns a single product

### `api_auth.php`
- `POST` with `action: 'login'`
- `POST` with `action: 'register'`
- Uses `password_hash()` for storing passwords
- Uses `password_verify()` for login check

### `api_cart.php`
- `GET ?user_id=...` returns cart items joined with product data
- `POST` adds an item to cart
- If the same product is already in cart, quantity is increased
- `DELETE` removes one cart row by cart `id`

### `api_checkout.php`
- `POST` creates a new order
- Inserts one row into `orders`
- Inserts multiple rows into `order_items`
- Deletes the user's cart after success
- Uses MySQL transaction for safer order creation

### `api_orders.php`
- `GET ?user_id=...` returns order history ordered by `created_at DESC`

### `api_mypage.php`
- `GET ?user_id=...` returns `id`, `name`, `email`, `role`
- `PUT` updates `name` and `email`

### `api_review.php`
- `GET ?product_id=...` returns reviews for one product
- `POST` adds a review
- `PUT` updates a review
- `DELETE` deletes a review

### `api_admin.php`
- `GET` returns all products for admin page
- `POST` inserts a new product
- `PUT` updates a product
- `DELETE` deletes a product

### `api_wishlist.php`
- `GET ?user_id=...` returns wishlist items joined with product data
- `POST` adds a product to wishlist
- Duplicate wishlist items are prevented
- `DELETE` can remove by wishlist `id`
- `DELETE` can also remove by `user_id` and `product_id`

---

## Part 5 — Important Notes

- All backend files currently connect to:
  - host: `localhost`
  - user: `s104838522`
  - database: `s104838522_db`
- All responses are JSON
- The frontend should use `fetch()` to call these APIs
- `wishlist` is now implemented as a separate API, not inside `cart`
- `admin` role exists in the `users` table, but server-side admin permission checking is not fully enforced yet
