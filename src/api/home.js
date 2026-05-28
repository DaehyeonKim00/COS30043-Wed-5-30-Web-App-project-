const productsApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_products.php'

export function getFeaturedProducts() {
  return fetch(productsApiUrl)
    .then(response => response.json())
}
