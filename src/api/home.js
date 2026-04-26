const productsApiUrl = 'http://localhost/backend/api_products.php'

export function getFeaturedProducts() {
  return fetch(productsApiUrl)
    .then(response => {
      return response.json()
    })
}
