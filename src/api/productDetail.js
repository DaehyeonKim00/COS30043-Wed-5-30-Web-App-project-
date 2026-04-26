const productsApiUrl = 'http://localhost/backend/api_products.php'

export function getProductById(productId) {
  return fetch(`${productsApiUrl}?id=${productId}`)
    .then(response => {
      return response.json()
    })
}
