const productsApiUrl = 'http://localhost/backend/api_products.php'

export function getProducts() {
  return fetch(productsApiUrl)
    .then(response => {
      return response.json()
    })
}

export function getProductsByCategory(category) {
  return fetch(`${productsApiUrl}?category=${encodeURIComponent(category)}`)
    .then(response => {
      return response.json()
    })
}
