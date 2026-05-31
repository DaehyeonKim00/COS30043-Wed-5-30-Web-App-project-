const productsApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_products.php'

// Fetch a single product by its id
export function getProductById(productId) {
  return fetch(productsApiUrl + '?id=' + productId)
    .then(response => response.json())
}
// Advanced feature (tuan)
// Fetch recommended products for the given category, excluding the current product
export function getRecommendedProducts(category, excludeId) {
  return fetch(productsApiUrl + '?recommend=1&category=' + encodeURIComponent(category) + '&exclude=' + excludeId)
    .then(response => response.json())
}
