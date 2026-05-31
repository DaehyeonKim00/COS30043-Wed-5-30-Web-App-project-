const productsApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_products.php'

// Fetch the full list of products
export function getProducts() {
  return fetch(productsApiUrl)
    .then(response => response.json())
}

// Fetch products filtered by category
export function getProductsByCategory(category) {
  return fetch(productsApiUrl + '?category=' + encodeURIComponent(category))
    .then(response => response.json())
}
