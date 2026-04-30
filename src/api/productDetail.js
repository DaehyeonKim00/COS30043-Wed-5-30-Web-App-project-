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
