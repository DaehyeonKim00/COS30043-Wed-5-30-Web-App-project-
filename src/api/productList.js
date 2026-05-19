var productsApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_products.php'

export function getProducts() {
  return fetch(productsApiUrl)
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error fetching products:', error)
      throw error
    })
}

export function getProductsByCategory(category) {
  return fetch(productsApiUrl + '?category=' + encodeURIComponent(category))
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error fetching products by category:', error)
      throw error
    })
}
