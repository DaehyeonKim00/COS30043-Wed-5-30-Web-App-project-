const cartApiUrl = 'http://localhost/backend/api_cart.php'

export function getCart(userId) {
  return fetch(`${cartApiUrl}?user_id=${userId}`)
    .then(response => {
      return response.json()
    })
}

export function addToCart(userId, productId, quantity) {
  return fetch(cartApiUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      user_id: userId,
      product_id: productId,
      quantity: quantity
    })
  })
    .then(response => {
      return response.json()
    })
}

export function removeFromCart(id) {
  return fetch(cartApiUrl, {
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
