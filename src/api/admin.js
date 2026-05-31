const adminApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_admin.php'

// Fetch the full list of products for the admin dashboard
export function getProducts() {
  return fetch(adminApiUrl)
    .then(response => response.json())
}

// Create a new product
export function addProduct(product) {
  return fetch(adminApiUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(product)
  })
    .then(response => response.json())
}

// Update an existing product
export function updateProduct(product) {
  return fetch(adminApiUrl, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(product)
  })
    .then(response => response.json())
}

// Delete a product by its id
export function deleteProduct(id) {
  return fetch(adminApiUrl, {
    method: 'DELETE',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: id
    })
  })
    .then(response => response.json())
}
