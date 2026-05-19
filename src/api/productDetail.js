var productsApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_products.php'

export function getProductById(productId) {
  return fetch(productsApiUrl + '?id=' + productId)
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error fetching product:', error)
      throw error
    })
}
// Advanced feature (tuan)
export function getRecommendedProducts(category, excludeId) {
  return fetch(productsApiUrl + '?recommend=1&category=' + encodeURIComponent(category) + '&exclude=' + excludeId)
    .then(response => response.json())
    .catch(error => {
      console.error('Error fetching recommendations:', error)
      throw error
    })
}

