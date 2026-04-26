const productsApiUrl = 'http://localhost/backend/api_products.php'

export function searchProducts(keyword) {
  return fetch(`${productsApiUrl}?search=${encodeURIComponent(keyword)}`)
    .then(response => {
      return response.json()
    })
}

export function searchProductsByCategory(category) {
  return fetch(`${productsApiUrl}?category=${encodeURIComponent(category)}`)
    .then(response => {
      return response.json()
    })
}
