const productsApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_products.php'

// Fetch products to feature on the home page
export function getFeaturedProducts() {
  return fetch(productsApiUrl)
    .then(response => response.json())
}
