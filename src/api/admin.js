const adminApiUrl = 'http://localhost/backend/api_admin.php'

export function getProducts() {
  return fetch(adminApiUrl)
    .then(response => {
      return response.json()
    })
}

export function addProduct(product) {
  return fetch(adminApiUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(product)
  })
    .then(response => {
      return response.json()
    })
}

export function updateProduct(product) {
  return fetch(adminApiUrl, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(product)
  })
    .then(response => {
      return response.json()
    })
}

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
    .then(response => {
      return response.json()
    })
}
