# DB & API Structure Guide

---

## Part 1 — MySQL DB & Table Creation

| Table | Columns |
|-------|---------|
| `users` | id, name, email, password, created_at |
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
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  category VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(10, 2) NOT NULL,
  image VARCHAR(500),
  stock INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
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
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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
  rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  comment TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);
```

---

## Part 2 — PHP API Files (Mercury Server)

| PHP File | Role |
|----------|------|
| `api_auth.php` | Login, Register |
| `api_products.php` | Get products, Search |
| `api_cart.php` | Get cart, Add, Remove |
| `api_checkout.php` | Place order |
| `api_orders.php` | Get order history |
| `api_mypage.php` | Get user info |
| `api_review.php` | Get, Post, Update, Delete reviews |
| `api_admin.php` | Add, Update, Delete products |

---

## Part 3 — src/api/ ↔ PHP File Mapping

| src/api/ JS File | Mercury PHP File |
|------------------|------------------|
| `home.js` | `api_products.php` |
| `productList.js` | `api_products.php` |
| `productDetail.js` | `api_products.php` |
| `cart.js` | `api_cart.php` |
| `checkout.js` | `api_checkout.php` |
| `login.js` | `api_auth.php` |
| `register.js` | `api_auth.php` |
| `myPage.js` | `api_mypage.php` |
| `orderHistory.js` | `api_orders.php` |
| `review.js` | `api_review.php` |
| `admin.js` | `api_admin.php` |
| `searchResult.js` | `api_products.php` |
| `about.js` | (static page, no API) |
