const cartApiUrl = 'https://mercury.swin.edu.au/cos30043/s104838522/test/backend/api_cart.php'
// Advanced feature - This file handles all HTTP requests to the backend cart API (api_cart.php).
// This file is consumed by src/composables/useCart.js,
// which wraps these functions with Vue 3 reactive state (computed(), ref()) and Vuex store synchronisation so components never need to call cart.js directly.
export function getCart(userId) {
  return fetch(cartApiUrl + '?user_id=' + userId)
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error fetching cart:', error)
      throw error
    })
}

export function addToCart(userId, productId, quantity, forceQuantity = false) {
  return fetch(cartApiUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      user_id: userId,
      product_id: productId,
      quantity: quantity,
      force_quantity: forceQuantity
    })
  })
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error adding to cart:', error)
      throw error
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
    .catch(error => {
      console.error('Error removing from cart:', error)
      throw error
    })
}

export function updateCartQuantity(cartId, quantity) {
  return fetch(cartApiUrl, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({
      id: cartId,
      quantity: quantity
    })
  })
    .then(response => {
      return response.json()
    })
    .catch(error => {
      console.error('Error updating cart:', error)
      throw error
    })
}
